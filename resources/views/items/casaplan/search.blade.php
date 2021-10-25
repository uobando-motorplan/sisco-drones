@extends('layouts.app')

@section('title', 'Resultados de búsqueda')

@section('content')
    <!-- start page-title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">@yield('title')</h4>

                <div class="page-title-right d-none d-sm-block">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);"></a>Mi catálogo</li>
                        <li class="breadcrumb-item"><a href="">CasaPlan</a></li>
                        <li class="breadcrumb-item active">Resultados de búsqueda</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page-title -->

    @include('items.casaplan.searcher_include')

    <!-- start row -->
    <div class="row mt-3 mb-4">
        <div class="col-12">
            <h1 class="display-6 my-0">Resultados de: {{ $category ? $category->name : '' }} {{ $location ? $location->name : '' }} ${{ number_format($from) }} - {{ $to == 999999 ? 'Más de $80,000' : '$'.number_format($to) }}</h1>
        </div>
    </div>
    <!-- end row -->

    @foreach ($plans as $plan)
        <!-- start row -->
        <div class="row">
            <div class="col-12">
                <div class="card rounded-0">
                    <div class="monto py-3 px-4 ">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-between">
                                <div class="d-flex justify-content-start">
                                    <div class="mr-2 d-none d-sm-block">
                                        <img src="{{ asset('assets/images/iso-cpmp.png') }}" class="img-fluid">
                                    </div>
                                    <div class="text-left">
                                        <h6 class="mb-0 text-white">CasaPlan de</h6>
                                        <h2 class="mb-0 text-white">${{ number_format($plan->amount) }}</h2>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <h6 class="mb-0">Cuota fija de</h6>
                                    <h2 class="mb-0">${{ number_format($plan->monthly_payment) }}/mes</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4 px-4 pb-0">
                        <div class="row">
                            @foreach ($items->where('plan_id', $plan->id) as $item)
                                <div class="col-xl-4 col-md-6 car-block">
                                    @include('items.casaplan.item_include', ['like' => true, 'remove' => false, 'combined_plans' => null])
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    @endforeach

    @if ($items->where('plan_id', 999999)->count())
        <!-- start row -->
        <div class="row">
            <div class="col-12">
                <div class="card rounded-0">
                    <div class="monto py-3 px-4 ">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-between">
                                <div class="d-flex justify-content-start">
                                    <div class="mr-2 d-none d-sm-block">
                                        <img src="{{ asset('assets/images/iso-cpmp.png') }}" class="img-fluid">
                                    </div>
                                    <div class="text-left">
                                        <h6 class="mb-0 text-white">CasaPlan</h6>
                                        <h2 class="mb-0 text-white">Más de ${{ number_format($last_plan->amount) }}</h2>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <h6 class="mb-0">Cuota fija de</h6>
                                    <h2 class="mb-0">Más de ${{ number_format($last_plan->monthly_payment) }}/mes</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4 px-4 pb-0">
                        <div class="row">
                            @foreach ($combined_plan_items as $elemento)
                                <div class="col-xl-4 col-md-6 car-block">
                                    @php
                                        $item = $elemento->item;
                                        $combined_plans = $elemento->combined_plans
                                    @endphp
                                    @include('items.casaplan.item_include', ['like' => true, 'remove' => false, 'combined_plans' => $combined_plans])
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    @endif
@endsection

@push('css')
    <!-- CDN Files -->
    <link href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" rel="stylesheet">
@endpush

@push('js')
    <!-- CDN Files -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
@endpush

