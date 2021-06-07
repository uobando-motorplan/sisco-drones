@extends('layouts.app')

@section('title', 'Comisiones acumuladas')

@section('content')
    <!-- start page-title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">@yield('title')</h4>

                <div class="page-title-right d-none d-sm-block">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><a href="javascript: void(0);">@yield('title')</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr class="bg-secondary text-white">
                                    <th>Fecha facturación</th>
                                    <th>Referido</th>
                                    <th>Producto</th>
                                    <th class="text-right">Plan</th>
                                    <th class="text-right">Comisión</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quotations as $quotation)
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($quotation->invoice_date)->format('Y-m-d') }}</td>
                                        <td>{{ $quotation->surnames }} {{ $quotation->names }}</td>
                                        <td>{{ $quotation->product_name }}</td>
                                        <td class="text-right">${{ number_format($quotation->amount) }}</td>
                                        <td class="text-right">${{ number_format($quotation->commision_value, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="">
                                    <th colspan="4" class="text-right">TOTAL</th>
                                    <th class="text-right bg-light">${{ number_format($quotations->sum('commision_value'), 2) }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
