<?php

namespace App\Http\Controllers;

use App\Item;
use App\Plan;
use App\Product;
use App\Location;
use App\Preference;
use App\ItemCategory;
use App\PromoterType;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('gallery');
        if (! session()->has('items')) {
            session()->put('items', []);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $category = null;
        $brand = null;
        $location = null;
        $promoter_type = null;

        // Obtengo los artículos recomendados
        $items = Item::whereHas('category', function ($quey) use ($product) {
                $quey->where('product_id', $product->id);
            })
            ->whereActive(true)
            ->whereRecommended(true)
            ->with(['plan', 'preference', 'real_estate_project'])
            ->orderBy('price')
            ->limit(12)
            ->get();

        // Obtengo las categorías según el producto
        $categories = ItemCategory::whereProductId($product->id)
            ->whereActive(true)
            ->orderBy('name')
            ->pluck('name', 'id');

        // Obtengo el monto inicial y final para seleccionar el rango de precios
        $from = collect($items->pluck('plan')->pluck('amount'))->first();
        $to = $items->contains('plan_id', 999999) ? 999999 : collect($items->pluck('plan')->pluck('amount'))->last();

        if ($product->id == Product::MOTORPLAN) {
            // Obtengo las marcas
            $preferences = Preference::whereProductId(Product::MOTORPLAN)
                ->whereActive(true)
                ->orderBy('name')
                ->pluck('name', 'id');

            return view('items.motorplan.index', compact('items', 'product', 'categories', 'preferences', 'category', 'brand', 'from', 'to'));
        } else {
            // Obtengo los tipos de promotor
            $promoter_types = PromoterType::pluck('name', 'id');
            // Obtengo las ubicaciones
            $locations = Location::whereActive(true)
                ->orderBy('name')
                ->pluck('name', 'id');
    
            return view('items.casaplan.index', compact('items', 'product', 'categories', 'locations', 'promoter_types', 'location', 'promoter_type', 'category', 'from', 'to'));
        }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, Product $product)
    {
        // Defino el rango de precios
        $range = explode(';', $request->range);

        // Obtengo los planes en el rango de monto seleccionado
        $plans = Plan::select('id', 'amount')
            ->where('product_id', $product->id)
            ->where('amount', '>=', $range[0])
            ->where('amount', '<=', $range[1])
            ->whereActive(true)
            ->orderBy('amount')
            ->get();
        
        // Articio para item de más de 800000 con código de plan 999999 no incluido en la db
        $collect = $plans;
        if ($range[1] == 999999) {
            $collect->push(['id' => 999999]);
        }

        // Busco los artículos
        $items = Item::whereIn('plan_id', $collect->pluck('id'))
            ->whereActive(true);
            // Filtrado por categoría
            if ($request->category) {
                $items = $items->whereItemCategoryId($request->category);
            }
            // Filtrado por marca
            if ($product->id == Product::MOTORPLAN AND $request->brand) {
                $items = $items->wherePreferenceId($request->brand);
            }
            // Filtrado por proyecto ubicación
            if ($product->id == Product::CASAPLAN) {
                // Filtrado por proyecto ubicación
                if ($request->location) {
                    $items = $items->whereHas('real_estate_project', function ($query) use ($request) {
                        $query->where('location_id', $request->location);
                    });
                }
                // Filtrado por proyecto tipo de promotor
                if ($request->promoter) {
                    $items = $items->whereHas('real_estate_project.promoter', function ($query) use ($request) {
                        $query->where('promoter_type_id', $request->promoter);
                    });
                }
            }
            $items = $items->with(['preference', 'real_estate_project'])
                ->orderBy('price')
                ->get();

        // return $items;

        // Obtengo los planes según de los artículos encontrados
        $plans = Plan::select('id', 'product_id', 'amount', 'monthly_payment')
            ->whereActive(true)
            ->whereIn('id', $items->pluck('plan_id'))
            ->orderBy('amount')
            ->get();

        // Obtengo las categorías según el producto
        $categories = ItemCategory::whereProductId($product->id)
            ->whereActive(true)
            ->orderBy('name')
            ->pluck('name', 'id');

        // Obtengo la categoría seleccionada
        $category = ItemCategory::select('id', 'name')
            ->whereId($request->category)
            ->first();

        $from = $range[0];
        $to = $range[1];

        if ($product->id == Product::MOTORPLAN) {
            // Obtengo las marcas
            $preferences = Preference::whereProductId(Product::MOTORPLAN)
                ->whereActive(true)
                ->orderBy('name')
                ->pluck('name', 'id');
            // Obtengo la marca seleccionada
            $brand = Preference::select('id', 'name')
                ->whereId($request->brand)
                ->first();

            return view('items.motorplan.search', compact('items', 'plans', 'product', 'categories', 'preferences', 'brand', 'category', 'from', 'to'));
        } else {
            // Obtengo los tipos de promotor
            $promoter_types = PromoterType::pluck('name', 'id');
            // Obtengo la ubicación seleccionada
            $promoter_type = PromoterType::select('id', 'name')
                ->whereId($request->promoter)
                ->first();
            // Obtengo las ubicaciones
            $locations = Location::whereActive(true)
                ->orderBy('name')
                ->pluck('name', 'id');
            // Obtengo la ubicación seleccionada
            $location = Location::select('id', 'name')
                ->whereId($request->location)
                ->first();

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
            }

            return view('items.casaplan.search', compact('items', 'plans', 'product', 'categories', 'category', 'promoter_types', 
            'promoter_type', 'locations', 'location', 'from', 'to', 'combined_plan_items', 'last_plan'));
        }
    }

    /**
     * Agrega el ID de un artículo en la sesión del brochure.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $array = session()->get('items');
        if (! in_array($request->id, $array)) {
            array_push($array, $request->id);
        }
        session()->put('items', $array);

        return session()->get('items');
    }

    /**
     * Elimina el ID de un artículo de la sesión del brochure.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request)
    {
        $array = session()->get('items');
        $array = array_diff($array, [$request->id]);
        session()->put('items', $array);

        if ($request->back == 1) {
            return redirect()->back();
        }

        return session()->get('items');
    }

    /**
     * Devuelve un listado de los artículo de la sesión del brochure.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        if (session()->has('items')) {
            $items = Item::select('id', 'item_category_id', 'preference_id', 'real_estate_project_id', 'name', 'price')
                ->whereIn('id', session()->get('items'))
                ->with(['category', 'preference', 'real_estate_project'])
                ->orderBy('price')
                ->get();
            
            $collect = collect();

            $motorplan_items = 0;
            $casaplan_items = 0;

            foreach ($items as $item ) {
                $collect->push([
                    'id' => $item->id,
                    'name' => ($item->category->product_id == Product::MOTORPLAN ? $item->preference->name : $item->real_estate_project->name).' '.$item->name,
                    'price' => '$'.number_format($item->price, 2),
                    'icon' => $item->category->product_id == Product::MOTORPLAN ? 'car' : 'home'
                ]);

                if ($item->category->product_id  == Product::MOTORPLAN) {
                    $motorplan_items++;
                } else {
                    $casaplan_items++;
                }
            }

            // Para poder usar la ruta de crear brochure
            session()->put('product', $casaplan_items > $motorplan_items ? Product::CASAPLAN : Product::MOTORPLAN);

            return $collect;
        }
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
}
