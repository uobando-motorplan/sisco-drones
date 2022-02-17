@extends('layouts.app')

@section('title', 'Crear solicitud de pago')

@section('content')

<div class="table-responsive">
    <table class="table table-sm table-bordered mb-0">
        <thead>
            <tr class="bg-secondary text-white">
                <th>N° Solicitud</th>
                <th>Cliente</th>
                <th>Identificación</th>
                <th>F. inscripción</th>
                <th class="text-right">Plan</th>
                <th>Producto</th>
                <th class="text-right">Comisión</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subtotal = 0;
            @endphp
            @foreach ($details as $detail)
                <tr>
                    <td>{{ $detail['NumeroSolicitud'] }}</td>
                    <td></td>
                    <td>{{ $detail['ClienteCedula'] }}</td>
                    <td>{{ Carbon\Carbon::parse($detail['FechaInscripcion'])->format('Y-m-d') }}</td>
                    <td class="text-right">${{ number_format($detail['ValorPlan'], 2) }}</td>
                    <td>{{ $detail['ProductoId'] }}</td>
                    <td class="text-right">${{ number_format($detail['ValorComision'], 2) }}</td>
                </tr>
            @endforeach
            <tr class="">
                <th colspan="6" class="text-right">TOTAL</th>
                <th class="text-right bg-">${{ number_format(0, 2) }}</th>
            </tr>
        </tbody>
    </table>
</div>
@endsection