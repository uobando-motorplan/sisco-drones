@extends('layouts.app')

@section('title', 'Consultar solicitud de pago')

@section('content')
    <!-- start page-title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">@yield('title')</h4>

                <div class="page-title-right d-none d-sm-block">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Solicitudes de pago</a></li>
                        <li class="breadcrumb-item active"><a href="javascript: void(0);"></a>Consultar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-0">Solicitud de pago #{{ $payment_request->id }}</h3>
                    <div class="row my-3">
                        <div class="col-md-6">
                            <i class="text-muted">Creado en:</i> {{ $payment_request->created_at }}<br>
                            <i class="text-muted">Actualizado en:</i> {{ $payment_request->created_at }}<br>
                        </div>
                        <div class="col-md-6 text-right">
                            <i class="text-muted">Estado:</i> {!! $payment_request->getStatus() !!}<br>
                            @if ($payment_request->paid_at)
                                <i class="text-muted">Fecha de pago:</i> {{ $payment_request->paid_at ? $payment_request->paid_at->format('Y-m-d H:i') : '' }}<br>
                                <i class="text-muted">Factura #:</i> <br>
                            @endif
                        </div>
                    </div>
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
                                @foreach ($details as $detail)
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($detail->invoice_date)->format('Y-m-d') }}</td>
                                        <td>{{ $detail->surnames }} {{ $detail->names }}</td>
                                        <td>{{ $detail->product_name }}</td>
                                        <td class="text-right">${{ number_format($detail->amount) }}</td>
                                        <td class="text-right">${{ number_format($detail->commision_value, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="">
                                    <th colspan="4" class="text-right">TOTAL</th>
                                    <th class="text-right bg-light">${{ number_format($details->sum('commision_value'), 2) }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection