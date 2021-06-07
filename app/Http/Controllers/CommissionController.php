<?php

namespace App\Http\Controllers;

use App\CommissionDetail;
use App\CommissionPayment;
use Illuminate\Http\Request;

class CommissionController extends Controller
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
    public function accumulated()
    {
        $commission_details = CommissionDetail::whereHas('quotation', function($query) {
                $query->whereDroneId(auth()->user()->id);
            })
            ->whereNull('commission_payment_id')
            ->with(['quotation:id,plan_id,customer_id', 'quotation.plan', 'quotation.plan.product', 'quotation.customer:id,names,surnames'])
            ->latest()
            ->get();

        return view('commissions.accumulated', compact('commission_details'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function payment_requests()
    {
        $commission_payments = CommissionPayment::whereDroneId(auth()->user()->id)
            ->withCount('details')
            ->latest()
            ->paginate(10);

        return view('commissions.payment_requests.index', compact('commission_payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {
        $commission_details = CommissionDetail::whereHas('quotation', function($query) {
                $query->whereDroneId(auth()->user()->id);
            })
            ->whereNull('commission_payment_id')
            ->with(['quotation:id,plan_id,customer_id', 'quotation.plan', 'quotation.plan.product', 'quotation.customer:id,names,surnames'])
            ->latest()
            ->get();

        return view('commissions.payment_requests.create', compact('commission_details'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Valido los detalles de la solicitud de pago
        $this->validate($request,
            [
                'detail_id' => 'required|array|min:1',
                'detail_id.*' => 'exists:commission_details,id'
            ], 
            [
               'detail_id.required' => 'Debe seleccionar por lo menos un referido.',
               'detail_id.*.exists' => 'Uno o más referidos no existen.',
            ],
            [
                'detail_id' => 'referido',
                'detail_id.*' => 'referido'
            ]
        );

        // Creo la solicitud de pago
        $commission_payment = auth()->user()->commission_payments()->create([
            'value' => '100'
        ]);

        // Creo los detalles de la solicitud de pago
        for ($i=0; $i < count($request->detail_id); $i++) { 
            CommissionDetail::whereId($request->detail_id[$i])
                ->update(['commission_payment_id' => $commission_payment->id]);
        }

        return $commission_payment;
        return $request->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CommissionDetail  $commissionDetail
     * @return \Illuminate\Http\Response
     */
    public function show(CommissionDetail $commissionDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CommissionDetail  $commissionDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(CommissionDetail $commissionDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CommissionDetail  $commissionDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommissionDetail $commissionDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CommissionDetail  $commissionDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommissionDetail $commissionDetail)
    {
        //
    }
}
