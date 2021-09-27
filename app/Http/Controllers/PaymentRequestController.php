<?php

namespace App\Http\Controllers;

use App\Notifications\PaymentRequestCancelledNotification;
use App\Notifications\PaymentRequestNotification;
use App\PaymentRequest;
use App\PaymentRequestDetail;
use App\Quotation;
use App\Role;
use App\Score;
use App\Status;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\DataTables;

class PaymentRequestController extends Controller
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
        return view('payment_requests.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Obtengo un arreglo con los id de las cotizaciones que ya están en los detalles de solicitud de pago
        $details = PaymentRequestDetail::whereHas('quotation', function($query) {
                $query->whereDroneId(auth()->user()->id);
            })
            ->pluck('quotation_id');

        // Obtengo las cotizaciones de comisionistas que estén cerradas con venta, que sus solicitudes de 
        // admisión hayan sido pagadas y que no estén en una solicitud de pago de comisiones ya existente
        $quotations = Quotation::join('customers', 'quotations.customer_id', '=', 'customers.id')
            ->join('admission_application_payments', function($join) {// Para extraer los datos del pago
                $join->on('quotations.admission_application', '=', 'admission_application_payments.admission_application');
                $join->on('admission_application_payments.customer_identification', '=', 'customers.identification');
            })
            ->join('plans', 'quotations.plan_id', '=', 'plans.id')
            ->join('products', 'plans.product_id', '=', 'products.id')
            ->where('quotations.drone_id', auth()->user()->id)
            ->whereStatusId(Status::CERRADO)
            ->whereScoreId(Score::VENTA_CERRADA)
            ->wherePaid(true)
            ->whereCommissionGenerated(true)
            ->whereNotIn('quotations.id', $details)
            ->select('quotations.id', 'plan_id', 'customer_id', 'quotations.admission_application', 'invoice_date', 
                'commision_value', 'names', 'surnames', 'products.name AS product_name', 'amount')
            ->get();

        return view('payment_requests.create', compact('quotations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                'quotation_id' => 'required|array|min:1',
                'quotation_id.*' => 'exists:quotations,id'
            ], 
            [
                'quotation_id.required' => 'Debe seleccionar por lo menos un referido.',
                'quotation_id.*.exists' => 'Uno o más referidos no existen.',
            ],
            [
                'quotation_id' => 'referido',
                'quotation_id.*' => 'referido'
            ]
        );

        // Creo la solicitud de pago de comisiones
        $payment_request = auth()->user()->payment_requests()->create();

        $total_commission = 0;

        for ($i=0; $i < count($request->quotation_id); $i++) {
            // Obtengo el valor de la comisión
            $commision_value = Quotation::join('customers', 'quotations.customer_id', '=', 'customers.id')
                ->join('admission_application_payments', function($join) {// Para extraer los datos del pago
                    $join->on('quotations.admission_application', '=', 'admission_application_payments.admission_application');
                    $join->on('admission_application_payments.customer_identification', '=', 'customers.identification');
                })
                ->where('quotations.id', $request->quotation_id[$i])
                ->pluck('commision_value')
                ->first();

            // Creo los detalles de la solicitud de pago
            $payment_request->details()->create([
                'quotation_id' => $request->quotation_id[$i],
                'value' => $commision_value
            ]);
            // Acumulo el valor de comisión
            $total_commission += $commision_value;
        }

        // Actualizo el valor total de la solicitud de pago
        $payment_request->value = $total_commission;
        $payment_request->save();

        // Envío la notificación al primer usuario con rol de contador
        if ($accountant = User::whereRoleId(Role::ACCOUNTANT)->first()) {
            $accountant->notify(new PaymentRequestNotification($payment_request));
        }

        return redirect()
            ->route('payment_requests.index')
            ->with('success', 'La solicitud de pago fue creada correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PaymentRequest  $payment_request
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentRequest $payment_request)
    {
        $this->authorize('view', $payment_request);

        $details = PaymentRequestDetail::wherePaymentRequestId($payment_request->id)
            ->join('quotations', 'payment_request_details.quotation_id', '=', 'quotations.id')
            ->join('customers', 'quotations.customer_id', '=', 'customers.id')
            ->join('admission_application_payments', function($join) {// Para extraer los datos del pago
                $join->on('quotations.admission_application', '=', 'admission_application_payments.admission_application');
                $join->on('admission_application_payments.customer_identification', '=', 'customers.identification');
            })
            ->join('plans', 'quotations.plan_id', '=', 'plans.id')
            ->join('products', 'plans.product_id', '=', 'products.id')
            ->select('quotations.id', 'plan_id', 'customer_id', 'quotations.admission_application', 'invoice_date', 'plan_code', 
                'commision_value', 'names', 'surnames', 'identification', 'products.name AS product_name', 'amount')
            ->withTrashed()
            ->get();

        return view('payment_requests.show', compact('payment_request', 'details'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\PaymentRequest  $payment_request
     * @return \Illuminate\Http\Response
     */
    public function cancel(PaymentRequest $payment_request)
    {
        $this->authorize('cancel', $payment_request);

        // Cambio el estado de la solicitud
        $payment_request->status = PaymentRequest::ANULADA;
        $payment_request->save();

        // Elimino los detalles
        $payment_request->details()->delete();

        // Envío la notificación al primer usuario con rol de contador
        if ($accountant = User::whereRoleId(Role::ACCOUNTANT)->first()) {
            $accountant->notify(new PaymentRequestCancelledNotification($payment_request));
        }

        return redirect()->back()
            ->with('success', 'El solicitud de pago fue anulada correctamente.');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function accumulated()
    {
        // Obtengo un arreglo con los id de las cotizaciones que ya están en los detalles de solicitud de pago
        $details = PaymentRequestDetail::whereHas('quotation', function($query) {
                $query->whereDroneId(auth()->user()->id);
            })
            ->pluck('quotation_id');

        // Obtengo las cotizaciones de comisionistas que estén cerradas con venta, que la solicitud de 
        // admisión todavía esté pagada y que no estén en una solicitud de pago de comisiones
        $quotations = Quotation::join('customers', 'quotations.customer_id', '=', 'customers.id')
            ->join('admission_application_payments', function($join) {// Para extraer los datos del pago
                $join->on('quotations.admission_application', '=', 'admission_application_payments.admission_application');
                $join->on('admission_application_payments.customer_identification', '=', 'customers.identification');
            })
            ->join('plans', 'quotations.plan_id', '=', 'plans.id')
            ->join('products', 'plans.product_id', '=', 'products.id')
            ->where('quotations.drone_id', auth()->user()->id)
            ->whereStatusId(Status::CERRADO)
            ->whereScoreId(Score::VENTA_CERRADA)
            ->wherePaid(true)
            ->whereCommissionGenerated(true)
            ->whereNotIn('quotations.id', $details)
            ->select('quotations.id', 'plan_id', 'customer_id', 'quotations.admission_application', 'invoice_date', 
                'commision_value', 'names', 'surnames', 'products.name AS product_name', 'amount')
            ->get();

        return view('payment_requests.accumulated', compact('quotations'));
    }

    /**
     *
     * Return a listing of the resource for datatables
     *
     * @return \Illuminate\Http\Response
     */
    public function datatables()
    {
        $payment_requests = PaymentRequest::whereDroneId(auth()->user()->id)
            ->withCount('details')
            ->get();

        return DataTables::of($payment_requests)
            ->addColumn('status', function ($payment_request) {
                return $payment_request->getStatus();
            })
            ->editColumn('value', function ($payment_request) {
                return '$'.number_format($payment_request->value);
            })
            ->editColumn('actions', function ($payment_request) {
                return view('payment_requests/actions', compact('payment_request'));
            })
            ->editColumn('created_at', function ($payment_request) {
                return $payment_request->created_at->format('Y-m-d');
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }
}
