<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Product;
use Illuminate\Http\Request;

class PlanController extends Controller
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
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Plan::select('id', 'product_id', 'amount')
            ->where('product_id', $request->product_id)
            ->whereActive(true)
            ->orderBy('amount', 'asc')
            ->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function fee_range(Request $request, Product $product)
    {
        $from = $request->from;
        $to = $request->to;

        // // No hay monto de 14000
        // $from = $from == 14000 ? 15000 : $from;
        // // Múltiplos de 5000
        // if ($from > 50000 AND ($from % 5000) != 0) {
        //     $from = $from + (5000 - ($from % 5000));
        // }
        // if ($to > 50000 AND ($to % 5000) != 0) {
        //     $to = $to + (5000 - ($to % 5000));
        // }

        if ($request->to > 80000) {
            $to = 80000;
        }

        $plan_from = Plan::select('monthly_payment')
            ->where('product_id', $product->id)
            ->whereAmount($from)
            ->whereActive(true)
            ->first();

        $plan_to = Plan::select('monthly_payment')
            ->where('product_id', $product->id)
            ->whereAmount($to)
            ->whereActive(true)
            ->first();

        return collect([
            'from' => '$'.number_format($plan_from->monthly_payment),
            'to' => ($request->to <= 80000 ? '$' : 'Más de $').number_format($plan_to->monthly_payment)
        ]);
    }
}
