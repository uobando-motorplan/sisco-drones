<?php

namespace App\Http\Controllers;

use App\City;
use App\Item;
use App\Plan;
use App\Role;
use App\User;
use App\Media;
use App\Score;
use App\Source;
use App\Status;
use App\Channel;
use App\Product;
use App\Brochure;
use App\Customer;
use App\Quotation;
use App\Preference;
use App\ContactMethod;
use App\ContactSchedule;
use App\Rules\ValidarRuc;
use App\Mail\BrochureMail;
use Illuminate\Support\Str;
use App\Rules\ValidarCedula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\BrochureRequest;
use App\Rules\ValidarDigitosIdentificacion;
use App\Notifications\NewReferredNotification;
use PDF;

class BrochureController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('show');
        if (! session()->has('items')) {
            session()->put('items', []);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function create(Product $product)
    {
        if (! session()->has('items')) {
            return redirect()->back();
        }

        $cities = City::select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id');
        $plans = Plan::select('id', 'amount')
            ->whereProductId($product->id)
            ->whereActive(true)
            ->orderBy('amount', 'asc')
            ->pluck('amount', 'id');
        $preferences = Preference::select('id', 'name')
            ->whereProductId($product->id)
            ->whereActive(true)
            ->orderBy('name', 'asc')
            ->pluck('name', 'id');

        // Obtengo las cotizaciones abiertas del producto
        $quotations = Quotation::select('id', 'customer_id', 'plan_id')
            ->whereDroneId(auth()->user()->id)
            ->where('status_id', '!=', Status::CERRADO)
            ->whereHas('plan', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->with(['customer:id,names,surnames,identification', 'plan:id,amount'])
            ->get();

        // Obtengo los artículos agregados al brochure
        $items = Item::whereIn('id', session()->get('items'))
            ->whereHas('category', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->with(['category', 'preference', 'real_estate_project'])
            ->get();

        // Obtengo los planes de los artículos agregados al brochure
        $plans_in_brochure = Plan::select('id', 'product_id', 'amount', 'monthly_payment')
            ->whereIn('id', $items->pluck('plan_id'))
            ->orderBy('amount')
            ->get();

        if ($product->id == Product::MOTORPLAN) {
            return view('brochures.motorplan.create', compact('items', 'plans', 'product', 'plans_in_brochure', 'cities', 'plans', 'preferences', 'quotations'));
        } else {
            // Items con planes combinados
            if ($items->where('plan_id', 999999)->count()) {
                // Obtengo todos los planes de CasaPlan
                $all_plans = Plan::select('id', 'product_id', 'amount', 'monthly_payment')
                    ->whereProductId(Product::CASAPLAN)
                    ->whereActive(true)
                    ->get();

                $last_plan = $all_plans->last();

                $combined_plan_items = collect();

                // Agrego los planes combinados
                foreach ($items->where('plan_id', 999999) as $item) {
                    $combined_plan_items->push((object)[
                        'item' => $item,
                        'combined_plans' => $this->combine_plans($item->price, $all_plans),
                    ]);
                }
            } else {
                $combined_plan_items = null;
                $last_plan = null;
                $all_plans = null;
            }

            return view('brochures.casaplan.create', compact('items', 'plans', 'product', 'plans_in_brochure', 'cities', 
                'plans', 'preferences', 'quotations', 'all_plans', 'last_plan', 'combined_plan_items'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\BrochureRequest  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function store(BrochureRequest $request, Product $product)
    {
        if (! session()->has('items')) {
            return redirect()->back();
        }

        // Es un nuevo prospecto
        if ($request->new_referred == 1) {
            // Valido la cédula
            if ($request->identification_type == Customer::CEDULA) {
                $this->validate($request, [
                    'identification' => new ValidarCedula
                ]);
            }
            // Valido el ruc
            if ($request->identification_type == Customer::RUC) {
                $this->validate($request, [
                    'identification' => new ValidarRuc
                ]);
            }
            // Valido que los 10 primeros dígitos no existan en otro prospecto
            if ($request->identification_type == Customer::CEDULA OR $request->identification_type == Customer::RUC) {
                $this->validate($request, ['identification' => new ValidarDigitosIdentificacion]);
            }

            // Consulto la ciudad
            $city = City::select('id', 'province_id')
                ->whereId($request->city_id)
                ->first();

            // Busco el vendedor web con menos oportunidades creadas, que pertenezca a la misma provincia del prospecto
            $sellers = User::select('id', 'group_id', 'name', 'last_name', 'email', 'assigned_prospects')
                ->whereRoleId(Role::ONLINE_SELLER)
                ->whereProvinceId($city->province_id)
                ->whereActive(true)
                ->with('group')
                ->inRandomOrder()
                ->get();
            $seller = $sellers->sortBy('assigned_prospects')->first();

            // No encuentro vendedor en la provincia del prospecto
            if (! $seller) {
                // Busco el vendedor web con menos oportunidades creadas, sin importar la provincia
                $sellers = User::select('id', 'group_id', 'name', 'last_name', 'email', 'assigned_prospects')
                    ->whereRoleId(Role::ONLINE_SELLER)
                    ->whereActive(true)
                    ->with('group')
                    ->inRandomOrder()
                    ->get();
                $seller = $sellers->sortBy('assigned_prospects')->first();
            }

            // Busco la fuente de información
            $source = Source::whereChannelId(Channel::DRONES)->first();

            // Registro el prospecto
            $customer = (new Customer)->fill($request->all());
            $customer->drone_id = auth()->user()->id;
            $customer->seller_id = $seller->id;
            $customer->assignator_id = User::SISTEMA;
            $customer->source_id = $source->id;
            $customer->contact_method_id = ContactMethod::VISITA_ASESOR_COMERCIAL;
            $customer->contact_schedule_id = ContactSchedule::POR_LA_NOCHE;
            $customer->media_id = Media::REFERIDO;
            $customer->status = Customer::COMPLETO;
            $customer->created_from = Customer::DRONES_WEB;
            $customer->locked = true;
            $customer->save();

            // Registro la cotización
            $quotation = (new Quotation)->fill($request->all());
            $quotation->customer_id = $customer->id;
            $quotation->drone_id = auth()->user()->id;
            $quotation->source_id = $source->id;
            $quotation->status_id = Status::POR_GESTIONAR;
            $quotation->score_id = Score::CLIENTE_INDECISO;
            $quotation->seller_id = $seller->id;
            $quotation->group_id = $seller->group_id;
            $quotation->supervisor_id = $seller->group->supervisor_id;
            $quotation->created_from = Quotation::DRONES_WEB;
            $quotation->condition = Quotation::NUEVO;
            $quotation->save();

            // Id de la cotización
            $quotation_id = $quotation->id;

            // Actualizo el contador de prospectos asignados
            $seller->assigned_prospects = $seller->assigned_prospects + 1;
            $seller->timestamps = false;
            $seller->save();

            // Envío la notificación al vendedor
            $seller->notify(new NewReferredNotification($quotation));
        } else {
            // Id de la cotización
            $quotation_id = $request->quotation_id;
        }

        // Obtengo los artículos agregados al brochure
        $items = Item::select('id', 'item_category_id')
            ->whereIn('id', session()->get('items'))
            ->whereHas('category', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->get();

        // Creo el brochure
        $brochure = Brochure::create([
            'quotation_id' => $quotation_id,
            'user_id' => auth()->user()->id,
            'slug' => Str::random(40)
        ]);
        foreach ($items as $item) {
            $brochure->details()->create([
                'item_id' => $item->id
            ]);
        }

        // Destruyo la sesión del brochure
        session()->forget('items');
        session()->forget('product');

        try {
            // Envio un correo electrónico al prospecto
            Mail::to($brochure->quotation->customer->email)->send(new BrochureMail($brochure));

            return redirect()
                ->route('quotations.show', $quotation_id)
                ->with('success-brochure', 'El referido fue creado correctamente. El brochure fue enviado correctamente.');
        } catch (\Throwable $th) {
            return redirect()
                ->route('quotations.show', $quotation_id)
                ->with('warning-brochure', 'El catálogo fue creado correctamente pero el referido no fue notificado porque su cuenta de correo electrónico es inválida. Por favor corrija la cuenta de correo electrónico y utilice el botón "Reenviar email".');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brochure  $brochure
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brochure $brochure)
    {
        //
    }

    /**
     * Devuelve un listado de los artículo de la sesión del brochure.
     *
     * @param  int  $price
     * @return \Illuminate\Http\Response
     */
    private function combine_plans(int $price, $plans)
    {
        // Valido que el monto solo sea hasta 160000
        if ($price > 160000) {
            return collect([
                'plan_1' => $plans->where('amount', 80000)->first(),
                'plan_2' => $plans->where('amount', 80000)->first(),
            ]);
        }

        $multiplo = $price > 130000 ? 5000 : 1000;

        if ($price % $multiplo) {
            $price = $price + ($multiplo - ($price % $multiplo));
        } else {
            $price = $price;
        }

        $amount_1 = 0;
        $amount_2 = 0;

        // Calculo el primer monto
        if (($price - 80000) < 13000) {
            $amount_1 = 70000;
        } else {
            $amount_1 = 80000;
        }

        // Calculo el resto entre el precio y el primer monto calculado
        $resto = $price - $amount_1;

        // El calculo del segundo monto empieza con el monto del primer plan
        $amount_2 = $plans[0]['amount'];

        // Calculo el segundo monto buscando un plan cuyo monto igual o mayor al 
        // resto entre el precio del articulo y el primer monto calculado
        $i = 1;
        while ($resto > $amount_2) {
            $amount_2 = $plans[$i]['amount'];
            $i++;
        }

        return collect([
            'plan_1' => $plans->where('amount', $amount_1)->first(),
            'plan_2' => $plans->where('amount', $amount_2)->first(),
        ]);
    }

    /**
     * Envía un email al prospecto con el enlace del brochure
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brochure  $brochure
     * @return \Illuminate\Http\Response
     */
    public function notify(Brochure $brochure)
    {
        Mail::to($brochure->quotation->customer->email)->send(new BrochureMail($brochure));

        return redirect()->back()
            ->with('success-brochure', 'El correo electrónico del catálogo personalizado fue reenviado correctamente a tu referido.');
    }
}
