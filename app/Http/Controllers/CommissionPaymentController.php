<?php

namespace App\Http\Controllers;

use App\CommissionPayment;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CommissionPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('commission_payments.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CommissionPayment  $commissionPayment
     * @return \Illuminate\Http\Response
     */
    public function show(CommissionPayment $commissionPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CommissionPayment  $commissionPayment
     * @return \Illuminate\Http\Response
     */
    public function edit(CommissionPayment $commissionPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CommissionPayment  $commissionPayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommissionPayment $commissionPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CommissionPayment  $commissionPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommissionPayment $commissionPayment)
    {
        //
    }

    /**
     *
     * Return a listing of the resource for datatables
     *
     * @return \Illuminate\Http\Response
     */
    public function datatables()
    {
        $commission_payments = CommissionPayment::whereDroneId(auth()->user()->id)
            ->withCount('details')
            ->get();

        return DataTables::of($commission_payments)
            ->addColumn('status', function ($commission_payment) {
                return $commission_payment->getStatus();
            })
            ->editColumn('actions', function ($commission_payment) {
                return view('commission_payments/actions', compact('commission_payment'));
            })
            ->editColumn('created_at', function ($commission_payment) {
                return $commission_payment->created_at->format('Y-m-d H:i:s');
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }
}
