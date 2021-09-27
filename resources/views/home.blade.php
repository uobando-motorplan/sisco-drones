@extends('layouts.app')

@section('title', 'Dashboard')

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

    @if (! $error)
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Referidos del mes</p>
                                <h4 class="mb-0">{{ $total_quotations }}</h4>
                            </div>
                            <div class="text-cpmp">
                                <i class="ri-user-line font-size-24"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body border-top py-3">
                        <div class="text-truncate">
                            <span class="badge badge-soft-{{ $from_previus_quotations['color'] }} font-size-11">{!! $from_previus_quotations['icon'] !!}{{ $from_previus_quotations['total'] }}%</span>
                            <small class="text-muted ml-2">Del período anterior</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Clientes del mes</p>
                                <h4 class="mb-0">{{ $total_sales }}</h4>
                            </div>
                            <div class="text-cpmp">
                                <i class="ri-money-dollar-circle-line font-size-24"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body border-top py-3">
                        <div class="text-truncate">
                            <span class="badge badge-soft-{{ $from_previus_sales['color'] }} font-size-11">{!! $from_previus_sales['icon'] !!}{{ $from_previus_sales['total'] }}%</span>
                            <small class="text-muted ml-2">Del período anterior</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Eficacia de cierre del mes</p>
                                <h4 class="mb-0">{{ $total_quotations > 0 ? number_format($total_sales/$total_quotations * 100, 1) : 0 }}%</h4>
                            </div>
                            <div class="text-cpmp">
                                <i class="ri-line-chart-line font-size-24"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body border-top py-3">
                        <div class="text-truncate">
                            <small class="text-muted ml-2">Clientes del mes / Referidos del mes</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body overflow-hidden">
                                <p class="text-truncate font-size-14 mb-2">Comisiones acumuladas</p>
                                <h4 class="mb-0">$0</h4>
                            </div>
                            <div class="text-cpmp">
                                <i class="ri-money-dollar-circle-line font-size-24"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body border-top py-3">
                        <div class="text-truncate">
                            <small class="text-muted ml-2">Calculo cada fin de mes</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="card">
                    <div class="card-body" style="height: 427px;">
                        <p class="text-truncate font-size-14 mb-3">Referidos del mes</p>
                        <div>
                            <div id="line-column-chart" class="apex-charts" dir="ltr"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card">
                    <div class="card-body" style="height: 427px;">
                        <p class="text-truncate font-size-14 mb-3">Referidos del mes por producto</p>
                        <div>
                            <div id="donut-chart" class="apex-charts"></div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center mt-4">
                                    <p class="mb-2 text-truncate"><i class="mdi mdi-circle text-warning font-size-10 mr-1"></i> CasaPlan</p>
                                    <h5>{{ $total_quotations > 0 ? round($total_casaplan_quotations*100/$total_quotations) : 0 }}%</h5>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center mt-4">
                                    <p class="mb-2 text-truncate"><i class="mdi mdi-circle text-success font-size-10 mr-1"></i> MotorPlan</p>
                                    <h5>{{ $total_quotations > 0 ? round($total_motorplan_quotations*100/$total_quotations) : 0 }}%</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-12">
                <div class="card">
                    <div class="card-body">
                        <p class="text-truncate font-size-14 mb-3">Seguimientos para hoy</p>
                        <div data-simplebar style="height: 350px; max-height: 350px;">
                            <ul class="list-unstyled activity-wid">
                                @foreach ($observations as $observation)
                                    <li class="activity-list">
                                        <div class="activity-icon avatar-xs">
                                            <span class="avatar-title bg-soft-info text-info rounded-circle">
                                                <i class="ri-edit-2-fill"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h5 class="font-size-12 mb-0">{{ $observation->followup_date->format('H:i') }} <small class="text-muted"></small></h5>
                                            <a href="{{ route('quotations.show', $observation->quotation_id) }}" class="text-muted">{{ $observation->quotation->customer->getFullName() }}</a> 
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    @else
        <div class="row">
            <div class="col-md-6">
                @if ($error)
                    <div class="alert alert-warning" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <i class="mdi mdi-alert-outline pr-2"></i> {{ $error }}
                    </div>
                @endif
            </div>
        </div>
        <!-- end row -->
    @endif
@endsection

@if (! $error)
    @push('js')
        <!-- apexcharts -->
        <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
        <script type="text/javascript">
            var options = {
                chart: {
                    defaultLocale: 'es',
                    locales: [{
                        name: 'es',
                        options: {
                            months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septembre', 'Octubre', 'Noviembre', 'Deciembre'],
                            shortMonths: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                            days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                            shortDays: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
                            toolbar: {
                                download: 'Descargar SVG',
                                selection: 'Selección',
                                selectionZoom: 'Selección Zoom',
                                zoomIn: 'Acercarse',
                                zoomOut: 'Alejarse',
                                pan: 'Deplasare',
                                reset: 'Reiniciar Zoom',
                            }
                        }
                    }],
                    height: 355,
                    width: '100%',
                    type: 'area',
                    animations: {
                        initialAnimation: {
                            enabled: false
                        }
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                markers: {
                    size: 0,
                },
                colors: ['#5664d2'],
                series: [
                {
                    name: 'Referidos',
                    data: [
                    @foreach ($period_days as $date)
                        ['{{ Carbon\Carbon::parse($date)->format('Y-m-d') }}', {{ $quotations_per_day->where('date', $date)->sum('value') ? $quotations_per_day->where('date', $date)->sum('value') : 0 }}], 
                    @endforeach
                    ]
                }
                ],
                xaxis: {
                    type: 'datetime'
                }
            };
            var chart = new ApexCharts(document.querySelector("#line-column-chart"), options);
            chart.render();

            var options = {
                series: [ {{ $total_motorplan_quotations }}, {{ $total_casaplan_quotations }}],
                chart: {
                    height: 275,
                    type: "donut"
                },
                labels: ["MotorPlan", "CasaPlan"],
                plotOptions: {
                    pie: {
                        donut: {
                            size: "75%"
                        }
                    }
                },
                dataLabels: {
                    enabled: !1
                },
                legend: {
                    show: !1
                },
                colors: ["#1cbb8c", "#eeb902"]
            };
            chart = new ApexCharts(document.querySelector("#donut-chart"), options);
            chart.render();
        </script>
    @endpush
@endif