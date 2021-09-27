<?php

namespace App\Http\Controllers;

use App\City;
use App\Plan;
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
use App\Brochure;
use App\Customer;
use App\Province;
use App\Quotation;
use App\WorkShift;
use App\Occupation;
use App\Preference;
use App\FollowupType;
use App\ClosureReason;
use App\ContactMethod;
use App\EventCategory;
use App\OccupationPeriod;
use App\Rules\ValidarRuc;
use App\CustomerObservation;
use App\Rules\ValidarCedula;
use Illuminate\Http\Request;
use App\QuotationObservation;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use App\Http\Requests\ReportRequest;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\QuotationRequest;
use App\Rules\ValidarDigitosIdentificacion;

class QuotationController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('quotations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function create(Customer $customer)
    {
        if ($customer->locked) {
            return redirect()
                ->route('customers.search')
                ->with('warning', 'La persona con '.strtolower($customer->getIdentificationType()).' '.$customer->identification.', ya está siendo gestionado por un asesor comercial de CasaPlan-MotorPlan.');
        }

        $cities = City::whereProvinceId($customer->city->province_id)
            ->orderBy('name')
            ->pluck('name', 'id');
        $contact_methods = ContactMethod::orderBy('name')->pluck('name', 'id');
        $products = Product::pluck('name', 'id');
        $provinces = Province::orderBy('name')->pluck('name', 'id');
        $occupations = Occupation::pluck('name', 'id');
        $occupation_periods = OccupationPeriod::pluck('name', 'id');

        return view('quotations.create', compact('customer', 'cities', 'contact_methods', 'products', 'provinces', 'occupations', 'occupation_periods'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\QuotationRequest  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function store(QuotationRequest $request, Customer $customer)
    {
        if ($customer->locked) {
            return redirect()
                ->route('customers.search')
                ->with('warning', 'La persona con '.strtolower($customer->getIdentificationType()).' '.$customer->identification.', ya está siendo gestionado por un asesor comercial de CasaPlan-MotorPlan.');
        }

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

        // Busco el vendedor web con menos oportunidades creadas, que pertenezca a la misma provincia del prospecto
        $sellers = User::select('id', 'group_id', 'name', 'last_name', 'email', 'assigned_prospects')
            ->whereRoleId(Role::ONLINE_SELLER)
            ->whereGroupId(Group::GRUPO_CANAL_DIGITAL)
            ->whereActive(true)
            ->with('group')
            ->inRandomOrder()
            ->get();
        $seller = $sellers->sortBy('assigned_prospects')->first();

        // Actualizo el prospecto
        $customer->locked = true;
        $customer->seller_id = $seller->id;
        $customer->update($request->all());

        // Creo una observación para saber a qué vendedor fue asignado al prospecto
        $customer->observations()->create([
            'user_id' => User::SISTEMA,
            'observation' => 'Asignado automáticamente a '.$seller->getFullName().' por Sistema.',
        ]);

        // Busco la fuente de información
        $source = Source::whereChannelId(Channel::DRONES)->first();

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
        $quotation->source_id = $source->id;
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function show(Quotation $quotation)
    {
        $this->authorize('view', $quotation);

        $quotation_observations = QuotationObservation::whereQuotationId($quotation->id)
            ->with(['user', 'status', 'closure_reason', 'score'])
            ->latest()
            ->paginate(10);

        $customer_observations = CustomerObservation::whereCustomerId($quotation->customer->id)
            ->with('user')
            ->latest()
            ->get();

        $brochures = Brochure::whereQuotationId($quotation->id)
            ->with('user')
            ->withCount('details')
            ->latest()
            ->get();

        return view('quotations.show', compact('quotation', 'quotation_observations', 'customer_observations', 'brochures'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function edit(Quotation $quotation)
    {
        $this->authorize('update', $quotation);

        $cities = City::whereProvinceId($quotation->customer->city->province_id)
            ->orderBy('name')
            ->pluck('name', 'id');
        $contact_methods = ContactMethod::orderBy('name')->pluck('name', 'id');
        $products = Product::pluck('name', 'id');
        $provinces = Province::orderBy('name')->pluck('name', 'id');
        $plans = Plan::whereProductId($quotation->plan->product_id)
            ->whereActive(true)
            ->orderBy('amount', 'asc')
            ->pluck('amount', 'id');
        $preferences = Preference::whereProductId($quotation->plan->product_id)
            ->whereActive(true)
            ->orderBy('name', 'asc')
            ->pluck('name', 'id');
        $occupations = Occupation::pluck('name', 'id');
        $occupation_periods = OccupationPeriod::pluck('name', 'id');

        return view('quotations.edit', compact('quotation', 'cities', 'contact_methods', 'products', 
            'provinces', 'plans', 'preferences', 'occupations', 'occupation_periods'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\QuotationRequest  $request
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function update(QuotationRequest $request, Quotation $quotation)
    {
        $this->authorize('update', $quotation);

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

        // Actualizo el prospecto
        $quotation->customer->update($request->all());

        // Actualizo la cotización
        $quotation->update($request->all());

        return redirect()
            ->route('quotations.index')
            ->with('success', 'El referido fue actualizado correctamente.');
    }

    /**
     * Return a listing of the resource in json format.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatables()
    {
        $quotations = Quotation::whereDroneId(auth()->user()->id)
            ->select('id', 'customer_id', 'plan_id', 'status_id', 'score_id', 'seller_id', 'group_id', 
                'closure_reason_id', 'drone_id', 'attended_at', 'created_at', 'updated_at')
            ->with(['customer', 'plan', 'plan.product', 'status', 'score', 'seller', 'group', 'closure_reason'])
            ->get();

        return DataTables::of($quotations)
            ->addColumn('customer', function ($quotation) {
                return $quotation->customer->getFullName();
            })
            ->addColumn('seller', function ($quotation) {
                return $quotation->seller->getFullName();
            })
            ->addColumn('amount', function ($quotation) {
                return '$'.number_format($quotation->plan->amount);
            })
            ->addColumn('status', function ($quotation) {
                return view('quotations/status', compact('quotation'));
            })
            ->editColumn('attended_at', function ($quotation) {
                return $quotation->attended_at ? $quotation->attended_at->format('Y-m-d H:i') : '';
            })
            ->editColumn('updated_at', function ($quotation) {
                return $quotation->updated_at->format('Y-m-d H:i');
            })
            ->addColumn('actions', function ($quotation) {
                return view('quotations/actions', compact('quotation'));
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    /**
     * Show the form for display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function criteria()
    {
        $closure_reasons = ClosureReason::pluck('description', 'id');
        $products = Product::pluck('name', 'id');
        $scores = Score::pluck('description', 'id');
        $statuses = Status::pluck('description', 'id');

        return view('quotations.criteria', compact('closure_reasons', 'products', 'scores', 'statuses'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\ReportRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function report(ReportRequest $request)
    {
        $quotations = Quotation::whereDroneId(auth()->user()->id)
            ->select('id', 'customer_id', 'plan_id', 'status_id', 'seller_id', 'group_id', 
                'closure_reason_id', 'drone_id', 'attended_at', 'created_at', 'updated_at')
            ->with(['customer', 'plan', 'plan.product', 'status', 'seller', 'group', 'closure_reason']);
            if ($request->product_id) {
                $quotations = $quotations->whereHas('plan', function($query) use($request) {
                    $query->where('product_id', $request->product_id);
                });
            }
            if ($request->status_id OR $request->score_id) {
                $quotations = $quotations->whereDate('updated_at', '>=', $request->start_date)
                    ->whereDate('updated_at', '<=', $request->end_date);

                if ($request->status_id) {
                    $quotations = $quotations->whereStatusId($request->status_id);
                }
                if ($request->score_id) {
                    $quotations = $quotations->whereScoreId($request->score_id);
                }
            } else {
                $quotations = $quotations->whereDate('created_at', '>=', $request->start_date)
                    ->whereDate('created_at', '<=', $request->end_date);
            }
            $quotations = $quotations->get();

        // return $quotations;

        return view('quotations.report', compact('quotations'));
    }
}
