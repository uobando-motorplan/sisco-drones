@extends('layouts.app')

@section('title', 'Brochure MotorPlan')

@section('content')
    <!-- start page-title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">@yield('title')</h4>

                <div class="page-title-right d-none d-sm-block">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);"></a>Mi catálogo</li>
                        <li class="breadcrumb-item active">MotorPlan</li>
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
            <h1 class="display-6 my-0">Vehículos recomendados</h1>
        </div>
    </div>
    <!-- end row -->

    <!-- start row -->
    <div class="row">
        @foreach ($items->shuffle() as $item)
            <div class="col-xl-4 col-md-6 car-block">
                <div class="card mb-0">
                    <div class="card-body pt-4 px-4">
                        @include('items.motorplan.item_include', ['like' => true, 'remove' => false])
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <!-- end row -->
@endsection

@push('css')
    <!-- CDN Files -->
    <link href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" rel="stylesheet">
@endpush

@push('js')
    <!-- CDN Files -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
@endpush