<?php

namespace App\Http\Controllers;

use App\Bank;
use App\Parameter;
use App\Quotation;
use App\PaymentRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\PaymentRequestRequest;

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
        $banks = Bank::orderBy('name')->pluck('name', 'id');
        $iva = Parameter::find(Parameter::IVA);

        $url = env('SAAD_API_URL').'/api/drone_commissions/';

        try {
            // Hago el llamado al api rest
            $response = Http::withHeaders([
                'X-Token' => env('SAAD_API_TOKEN')
            ])->get($url, [
                'drone_id' => auth()->user()->id
            ]);

            if ($response->ok()) {
                // Decodifico la respuesta del api rest
                $data = json_decode($response->body(), true);
                // Creo una colección
                $commissions = collect($data['drone_commissions']);

                $error = null;
                return view('payment_requests.create', compact('commissions', 'banks', 'iva', 'error'));
            } else {
                $error = 'Ocurrió un error al consultar la información de cierre. Por favor intente más tarde.';
                return view('payment_requests.create', compact('error'));
            }
        } catch (\Throwable $th) {
            $error = 'Ocurrió un error al consultar la información de cierre. Por favor intente más tarde.';
            return view('payment_requests.create', compact('error'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PaymentRequestRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentRequestRequest $request)
    {
        return $request->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PaymentRequest  $payment_request
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentRequest $payment_request)
    {
        // $this->authorize('view', $payment_request);

        // $details = PaymentRequestDetail::wherePaymentRequestId($payment_request->id)
        //     ->join('quotations', 'payment_request_details.quotation_id', '=', 'quotations.id')
        //     ->join('customers', 'quotations.customer_id', '=', 'customers.id')
        //     ->join('admission_application_payments', function($join) {// Para extraer los datos del pago
        //         $join->on('quotations.admission_application', '=', 'admission_application_payments.admission_application');
        //         $join->on('admission_application_payments.customer_identification', '=', 'customers.identification');
        //     })
        //     ->join('plans', 'quotations.plan_id', '=', 'plans.id')
        //     ->join('products', 'plans.product_id', '=', 'products.id')
        //     ->select('quotations.id', 'plan_id', 'customer_id', 'quotations.admission_application', 'invoice_date', 'plan_code', 
        //         'commision_value', 'names', 'surnames', 'identification', 'products.name AS product_name', 'amount')
        //     ->withTrashed()
        //     ->get();

        // return view('payment_requests.show', compact('payment_request', 'details'));
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

    /**
     * Display the specified resource.
     * Devuelve el de detalle de un cierre en formato HTML
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function details(Request $request)
    {
        $url = env('SAAD_API_URL').'/api/drone_commission_details/'.$request->commission_id;

        try {
            // Hago el llamado al api rest
            $response = Http::withHeaders([
                'X-Token' => env('SAAD_API_TOKEN')
            ])->get($url);

            if ($response->ok()) {
                // Decodifico la respuesta del api rest
                $data = json_decode($response->body(), true);
                // Creo una colección
                $details = collect($data['drone_commission_details']);

                // Busco los datos de las cotizaciones para obtener el nombre del cliente y producto
                $quotations = Quotation::select('id', 'customer_id', 'drone_id', 'plan_id', 'admission_application')
                    ->with(['customer:id,names,surnames,identification', 'plan.product'])
                    ->whereIn('admission_application', $details->pluck('NumeroSolicitud'))
                    ->whereDroneId(auth()->user()->id)
                    ->get();

                /*----------  Rango del dron  ----------*/

                    $url = env('SAAD_API_URL').'/api/drone_commission_distributions/'.$request->commission_id;
                    
                    // Hago el llamado al api rest
                    $response_2 = Http::withHeaders([
                        'X-Token' => env('SAAD_API_TOKEN')
                    ])->get($url);

                    if ($response_2->ok()) {
                        $data = json_decode($response_2->body(), true);
                        $drone_range = $data['drone_commission_distribution']['RangoDescripcion'];
                    } else {
                        $drone_range = null;
                    }

                $html = '
                <div class="table-responsive font-size-12">
                    <table class="table table-sm table-bordered mb-0">
                        <thead>
                            <tr class="bg-secondary text-white">
                                <th>N° Solicitud</th>
                                <th>Cliente</th>
                                <th>Cédula/RUC</th>
                                <th>F. inscripción</th>
                                <th class="text-right">Plan</th>
                                <th>Producto</th>
                                <th class="text-right">Bonificación '.$drone_range.'</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $total = 0;
                        if ($quotations->count()) {
                            foreach ($details as $detail) {
                                $quotation = $quotations->where('admission_application', $detail['NumeroSolicitud'])->first();
                                $total += $detail['ValorComision'];
                                $html .= '
                                <tr>
                                    <td>'.$detail['NumeroSolicitud'].'</td>
                                    <td>'.Str::upper($quotation->customer->getFullName()).'</td>
                                    <td>'.$detail['ClienteCedula'].'</td>
                                    <td>'.Carbon::parse($detail['FechaInscripcion'])->format('Y-m-d').'</td>
                                    <td class="text-right">$'.number_format($detail['ValorPlan'], 2).'</td>
                                    <td>'.Str::upper($quotation->plan->product->name).'</td>
                                    <td class="text-right">$'.number_format($detail['ValorComision'], 2).'</td>
                                </tr>';
                            }
                        }
                        $html .= '
                            <tr class="">
                                <th colspan="6" class="text-right">TOTAL</th>
                                <th class="text-right bg-">$'.number_format($total, 2).'</th>
                            </tr>
                        </tbody>
                    </table>
                </div>';

                return $html;
            } else {
                return 'Ocurrió un error1 al consultar la información de cierre. Por favor intente más tarde.';
            }
        } catch (\Throwable $th) {
            return 'Ocurrió un error al consultar la información de cierre. Por favor intente más tarde.';
        }
    }
}
