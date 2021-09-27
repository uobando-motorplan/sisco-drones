<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\Event;
use App\Group;
use App\Media;
use App\Score;
use App\Source;
use App\Status;
use App\Channel;
use App\Product;
use App\Customer;
use App\Province;
use App\Quotation;
use App\WorkShift;
use App\Occupation;
use App\FollowupType;
use App\ContactMethod;
use App\EventCategory;
use App\OccupationPeriod;
use App\Rules\ValidarRuc;
use App\Rules\ValidarCedula;
use Illuminate\Http\Request;
use App\QuotationObservation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\CustomerRequest;
use App\Rules\ValidarDigitosIdentificacion;

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for searching a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        return view('customers.search');
    }

    /**
     * Find the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function find(Request $request)
    {
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

        $customer = Customer::select('id', 'seller_id', 'drone_id', 'identification_type', 'identification', 'names', 'surnames', 'locked')
            ->with('seller')
            ->whereIdentification($request->identification)
            ->first();

        if ($customer) {
            if (! $customer->locked) {
                return redirect()
                    ->route('quotations.create', $customer->id);
            } else {
                if ($customer->drone_id == auth()->user()->id) {
                    return redirect()->back()
                        ->with('info', 'La persona con '.strtolower($customer->getIdentificationType()).' '.$customer->identification.', ya está en su lista de referidos.');
                } else {
                    return redirect()->back()
                        ->with('warning', 'La persona con '.strtolower($customer->getIdentificationType()).' '.$customer->identification.', ya está siendo gestionado por un asesor comercial de CasaPlan-MotorPlan.');
                }
            }
        }

        return redirect()
            ->route('customers.create')
            ->withInput(); 
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contact_methods = ContactMethod::select('id', 'name')->orderBy('name')->pluck('name', 'id');
        $products = Product::select('id', 'name')->pluck('name', 'id');
        $provinces = Province::select('id', 'name')->orderBy('name')->pluck('name', 'id');
        $occupations = Occupation::select('id', 'name')->pluck('name', 'id');
        $occupation_periods = OccupationPeriod::select('id', 'name')->pluck('name', 'id');

        return view('customers.create', compact('contact_methods', 'products', 'provinces', 'occupations', 'occupation_periods'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CustomerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
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

        // Busco el vendedor web con menos oportunidades creadas, que pertenezca a la misma provincia del prospecto
        $sellers = User::select('id', 'group_id', 'name', 'last_name', 'email', 'assigned_prospects')
            ->whereRoleId(Role::ONLINE_SELLER)
            ->whereGroupId(Group::GRUPO_CANAL_DIGITAL)
            ->whereActive(true)
            ->with('group')
            ->inRandomOrder()
            ->get();
        $seller = $sellers->sortBy('assigned_prospects')->first();

        // Busco la fuente de información
        $source = Source::whereChannelId(Channel::DRONES)->first();

        // Registro el prospecto
        $customer = (new Customer)->fill($request->all());
        $customer->drone_id = auth()->user()->id;
        $customer->seller_id = $seller->id;
        $customer->assignator_id = User::SISTEMA;
        $customer->source_id = $source->id;
        $customer->media_id = Media::REFERIDO;
        $customer->status = Customer::COMPLETO;
        $customer->created_from = Customer::DRONES_WEB;
        $customer->locked = true;
        $customer->save();

        // Creo una observación para saber a qué vendedor fue asignado el prospecto
        $customer->observations()->create([
            'user_id' => User::SISTEMA,
            'observation' => 'Asignado automáticamente a '.$seller->getFullName().' por Sistema.',
        ]);

        // Busco el próximo turno de trabajo
        $work_shift = WorkShift::whereUserId($seller->id)
            ->whereDate('date', '>', Carbon::now())
            ->whereEventCategoryId(EventCategory::TURNO)
            ->first();

        // Defino la fecha del seguimiento automático para el vendedor
        if ($work_shift) {
            $followup_date = Carbon::parse($work_shift->date->format('Y-m-d').' 09:00:00');
        } else {
            $followup_date = Carbon::parse(Carbon::now()->addDays(1)->format('Y-m-d').' 09:00:00');
        }

        // Registro la cotización
        $quotation = (new Quotation)->fill($request->all());
        $quotation->customer_id = $customer->id;
        $quotation->drone_id = auth()->user()->id;
        $quotation->source_id = $customer->source_id;
        $quotation->status_id = Status::EN_SEGUIMIENTO;
        $quotation->score_id = Score::CLIENTE_INDECISO;
        $quotation->seller_id = $customer->seller->id;
        $quotation->group_id = $customer->seller->group_id;
        $quotation->supervisor_id = $customer->seller->group->supervisor_id;
        $quotation->created_from = Quotation::DRONES_WEB;
        $quotation->followup_date = $followup_date;
        $quotation->save();

        // Actualizo el contador de prospectos asignados
        $seller->assigned_prospects = $seller->assigned_prospects + 1;
        $seller->timestamps = false;
        $seller->save();

        // Creo el seguimiento automático para el vendedor
        $quotation->observations()->create([
            'user_id' => $customer->seller->id,
            'followup_type_id' => FollowupType::LLAMAR,
            'followup_date' => $followup_date,
            'type' => QuotationObservation::SEGUIMIENTO
        ]);

        // Creo el evento del seguimiento automático para el vendedor
        $quotation->seller->events()->create([
            'event_category_id' => EventCategory::SEGUIMIENTO,
            'related_id' => $quotation->id,
            'title' => $quotation->customer->getFullName(),
            'is_all_day' => false,
            'start_date' => $quotation->followup_date,
            'end_date' => Carbon::parse($quotation->followup_date)->addHour(),
            'url' => route('quotations.show', $quotation->id),
            'type' => Event::AUTOMATICO,
        ]);

        // Envía una notificación al VENDEDOR para indicarle que tiene una nueva oportunidad comercial.
        $url = env('SISCO_URL').'api/notifications/new_quotation';
        $response = Http::get($url, [
            'api_key' => env('DRONES_KEY'),
            'quotation_id' => $quotation->id
        ]);

        return redirect()
            ->route('quotations.index')
            ->with('success', 'El referido fue creado correctamente.');
    }
}
