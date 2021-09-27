<?php

namespace App\Http\Controllers;

use App\Period;
use App\Product;
use App\Quotation;
use App\QuotationObservation;
use App\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Consulto el periodo actual basándome en la fecha de hoy
        $period = Period::select('id', 'start_date', 'end_date')
            ->whereRaw('? between start_date and end_date', [Carbon::now()->format('Y-m-d')])
            ->first();

        if (! $period) {
            $error = 'No existe un periodo para la fecha actual.';
            return view('home', compact('error'));
        } else {
            $error = null;
        }

        // Consulto el periodo anterior
        $previus_period = Period::whereDate('start_date', '<', $period->start_date)
            ->orderBy('start_date', 'desc')
            ->first();

        // Obtengo la cantidad de cotizaciones creadas en el perido actual y en el anterior
        $total_quotations = $this->contar_cotizaciones($period);
        $total_previus_quotations = $this->contar_cotizaciones($previus_period);

        // Calculo el porcentaje de ventas en relación al mes anterior
        $from_previus_quotations = ['total' => '0', 'icon' => '', 'color' => 'secondary'];
        if ($total_previus_quotations) {
            $x = number_format((($total_quotations - $total_previus_quotations)*100) / $total_previus_quotations, 1);
            if ($x > 0) {
                $from_previus_quotations = ['total' => $x, 'icon' => '<i class="mdi mdi-arrow-up"> </i>', 'color' => 'success'];
            }
            if ($x < 0) {
                $from_previus_quotations = ['total' => $x, 'icon' => '<i class="mdi mdi-down-up"> </i>', 'color' => 'danger'];
            }
        }

        // Obtengo la cantidad de cotizaciones vendidas en el perido actual y en el anterior
        $total_sales = $this->contar_cotizaciones_vendidas($period);
        $total_previus_sales = $this->contar_cotizaciones_vendidas($previus_period);

        // Calculo el porcentaje de ventas en relación al mes anterior
        $from_previus_sales = ['total' => '0', 'icon' => '', 'color' => 'secondary'];
        if ($total_previus_sales) {
            $x = number_format((($total_sales - $total_previus_sales)*100) / $total_previus_sales, 1);
            if ($x > 0) {
                $from_previus_sales = ['total' => $x, 'icon' => '<i class="mdi mdi-arrow-up"> </i>', 'color' => 'success'];
            }
            if ($x < 0) {
                $from_previus_sales = ['total' => $x, 'icon' => '<i class="mdi mdi-down-up"> </i>', 'color' => 'danger'];
            }
        }

        // Obtengo la cantidad de cotizaciones por producto en el perido actual
        $total_casaplan_quotations = $this->contar_cotizaciones($period, Product::CASAPLAN);
        $total_motorplan_quotations = $this->contar_cotizaciones($period, Product::MOTORPLAN);

        // Obtengo la lista de seguimientos de las cotizaciones del dron
        $observations = QuotationObservation::whereHas('quotation', function($query) {
                $query->where('drone_id', auth()->user()->id);
            })
            ->whereDate('followup_date', Carbon::now()->addDays(1)->format('Y-m-d'))
            ->whereType('S')
            ->with(['quotation.customer:id,names,surnames', 'quotation.seller:id,name,last_name'])
            ->orderBy('followup_date', 'asc')
            ->get();

        // Días del periodo
        $period_days = $period->getDays(Carbon::now());

        // Cuento las cotizaciones creadas y las agrupo por día de creación
        $quotations_per_day = $this->contar_cotizaciones_por_dias($period->start_date, $period->end_date);

        return view('home', compact('total_quotations', 'total_sales', 'from_previus_quotations', 'from_previus_sales', 
            'total_casaplan_quotations', 'total_motorplan_quotations', 'observations', 'period_days', 'quotations_per_day', 'error'));
    }

    /**
     *
     * Cuento las cotizaciones creadas
     *
     */
    private function contar_cotizaciones($period, $product_id=null)
    {
        $total = Quotation::whereDroneId(auth()->user()->id)
            ->whereDate('quotations.created_at', '>=', $period->start_date)
            ->whereDate('quotations.created_at', '<=', $period->end_date);
            if ($product_id) {// Filtrado por producto
                $total = $total->join('plans', 'quotations.plan_id', '=', 'plans.id')
                    ->where('plans.product_id', $product_id);
            }
            $total = $total->count();

        return $total;
    }

    /**
     *
     * Cuento las cotizaciones vendidas
     *
     */
    private function contar_cotizaciones_vendidas($period, $product_id=null)
    {
        $total = Quotation::whereDroneId(auth()->user()->id)
            ->whereDate('quotations.updated_at', '>=', $period->start_date)
            ->whereDate('quotations.updated_at', '<=', $period->end_date)
            ->where('score_id', '=', Score::VENTA_CERRADA);
            if ($product_id) {// Filtrado por producto
                $total = $total->join('plans', 'quotations.plan_id', '=', 'plans.id')
                    ->where('plans.product_id', $product_id);
            }
            $total = $total->count();

        return $total;
    }

    /**
     *
     * Cuento las cotizaciones agrupadas por día de creación
     *
     */
    private function contar_cotizaciones_por_dias($start_date, $end_date, $product_id=null)
    {
        $total = Quotation::whereDroneId(auth()->user()->id)
            ->whereDate('quotations.created_at', '>=', $start_date)
            ->whereDate('quotations.created_at', '<=', $end_date);
            if ($product_id) {// Filtrado por producto
                $total = $total->join('plans', 'quotations.plan_id', '=', 'plans.id')
                    ->where('plans.product_id', $product_id);
            }
            $total = $total->groupBy('date')
                ->orderBy('date', 'asc')
                ->get(array(
                    DB::raw('DATE(quotations.created_at) AS date'),
                    DB::raw('COUNT(*) AS value')
                ));

        return $total;
    }
}
