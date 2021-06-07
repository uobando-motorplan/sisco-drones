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
                        <li class="breadcrumb-item"><a href="">MotorPlan</a></li>
                        <li class="breadcrumb-item active">Resultados de búsqueda</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page-title -->

    @include('items.motorplan.searcher_include')

    <!-- start row -->
    <div class="row mt-3 mb-4">
        <div class="col-12">
            <h1 class="display-6 my-0">Resultados de: {{ $category ? $category->name : '' }} {{ $brand ? $brand->name : '' }} ${{ number_format($from) }} - ${{ number_format($to) }}</h1>
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
                                        <h6 class="mb-0 text-white">MotorPlan de</h6>
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
                                    @include('items.motorplan.item_include', ['like' => true, 'remove' => false])
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    @endforeach

    @include('items.partials.gallery_modal')
@endsection
