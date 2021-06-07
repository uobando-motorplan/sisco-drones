<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mi Catálogo Personalizado CasaPlan :: {{ $brochure->quotation->customer->names }} {{ $brochure->quotation->customer->surnames }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="CasaPlan-MotorPlan" name="author" />
    <!-- App favicon -->
    <link href="{{ asset('assets/images/favicon/apple-icon-57x57.png') }}" rel="apple-touch-icon" sizes="57x57">
    <link href="{{ asset('assets/images/favicon/apple-icon-60x60.png') }}" rel="apple-touch-icon" sizes="60x60">
    <link href="{{ asset('assets/images/favicon/apple-icon-72x72.png') }}" rel="apple-touch-icon" sizes="72x72">
    <link href="{{ asset('assets/images/favicon/apple-icon-76x76.png') }}" rel="apple-touch-icon" sizes="76x76">
    <link href="{{ asset('assets/images/favicon/apple-icon-114x114.png') }}" rel="apple-touch-icon" sizes="114x114">
    <link href="{{ asset('assets/images/favicon/apple-icon-120x120.png') }}" rel="apple-touch-icon" sizes="120x120">
    <link href="{{ asset('assets/images/favicon/apple-icon-144x144.png') }}" rel="apple-touch-icon" sizes="144x144">
    <link href="{{ asset('assets/images/favicon/apple-icon-152x152.png') }}" rel="apple-touch-icon" sizes="152x152">
    <link href="{{ asset('assets/images/favicon/apple-icon-180x180.png') }}" rel="apple-touch-icon" sizes="180x180">
    <link href="{{ asset('assets/images/favicon/android-icon-192x192.png') }}" rel="icon" type="image/png" sizes="192x192" >
    <link href="{{ asset('assets/images/favicon/favicon-32x32.png') }}" rel="icon" type="image/png" sizes="32x32">
    <link href="{{ asset('assets/images/favicon/favicon-96x96.png') }}" rel="icon" type="image/png" sizes="96x96">
    <link href="{{ asset('assets/images/favicon/favicon-16x16.png') }}" rel="icon" type="image/png" sizes="16x16">
    <link href="{{ asset('assets/images/favicon/manifest.json') }}" rel="manifest">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('assets/images/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Page Css -->
    <link href="{{ asset('assets/css/brochure.css') }}" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="container my-lg-4 my-xl-5">
        <div class="row h-100 pt-4 pb-5">
            <div class="col-md-4 my-auto text-center">
                <img src="{{ asset('assets/images/brochure/logo-casaplan-motorplan.png') }}" alt="logo CasaPlan-MotorPlan">
            </div>
            <div class="col-md-8 my-auto text-center text-md-left">
                <h1 class="display-4 my-3 my-md-0">MI CATÁLOGO <span class="text-cpmp">CASAPLAN</span></h1>
                <h4 class="text-muted my-0">Adquiere, amplía, construye o remodela. Tú eliges.</h4>
                <a href="javascript:void(0);" class="printPage btn btn-primary mt-4"><i class="mdi mdi-printer mr-1"></i> Imprimir</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <img src="{{ asset('assets/images/brochure/casaplan-992x480.jpg') }}" class="img-fluid d-none d-md-block" alt="Vivenda">
                <img src="{{ asset('assets/images/brochure/casaplan-768x480.jpg') }}" class="img-fluid d-none d-sm-block d-md-none" alt="Vivenda">
                <img src="{{ asset('assets/images/brochure/casaplan-512x480.jpg') }}" class="img-fluid d-block d-sm-none" alt="Vivenda">
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center">
                <h4 class="text-muted my-5">Adquirir, ampliar, construir o remodelar tu casa, oficina, casa vacacional, departamento o local comercial <i><strong><span class="text-primary">Sí</span> <span class="text-dark">es posible</span></strong></i></h4>
            </div>
            <div class="col-12 mb-5">
                <img src="{{ asset('assets/images/brochure/dubai-992x480.jpg') }}" class="img-fluid d-none d-md-block" alt="Promoción Dile sí a Dubái">
                <img src="{{ asset('assets/images/brochure/dubai-768x480.jpg') }}" class="img-fluid d-none d-sm-block d-md-none" alt="Promoción Dile sí a Dubái">
                <img src="{{ asset('assets/images/brochure/dubai-512x480.jpg') }}" class="img-fluid d-block d-sm-none" alt="Promoción Dile sí a Dubái">
            </div>
            <div class="col-12 mb-5 text-center">
                <div class="px-5">
                    Este brochure es exclusivamente para fines informativos, no para uso comercial. Los valores y modelos son referenciales y pueden variar sin previo aviso.
                </div>
            </div>
        </div>
        <div class="pagebreak"></div>

        @foreach ($plans as $plan)
            <!-- start row -->
            <div class="row">
                <div class="col-12">
                    <div class="mb-4">
                        <div class="monto py-3 px-4">
                            <div class="row">
                                <div class="col-12 d-flex justify-content-between">
                                    <div class="d-flex justify-content-start">
                                        <div class="mr-2 d-none d-sm-block">
                                            <img src="{{ asset('assets/images/iso-cpmp.png') }}" class="img-fluid">
                                        </div>
                                        <div class="text-left">
                                            <h6>CasaPlan de</h6>
                                            <h2>${{ number_format($plan->amount) }}</h2>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <h6>Cuota fija de</h6>
                                        <h2>${{ number_format($plan->monthly_payment) }}/mes</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="items px-4 pt-4">
                            <div class="row">
                                @php
                                    $residuo = $items->where('plan_id', $plan->id)->count() % 2;
                                    $class = $residuo > 0 ? 'col-12' : 'col-md-6';
                                @endphp
                                @foreach ($items->where('plan_id', $plan->id)->sortByDesc('recommended') as $item)
                                    <div class="{{ $class }} car-block">
                                        @include('items.casaplan.item_include', ['like' => false, 'remove' => false, 'combined_plans' => null])
                                    </div>
                                    @php
                                        $class = 'col-md-6';
                                    @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <div class="pagebreak"></div>
        @endforeach

        @if ($items->where('plan_id', 999999)->count())
            <!-- start row -->
            <div class="row">
                <div class="col-12">
                    <div class="mb-4">
                        <div class="monto py-3 px-4">
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
                                @php
                                    $residuo = $items->where('plan_id', 999999)->count() % 2;
                                    $class = $residuo > 0 ? 'col-12' : 'col-md-6';
                                @endphp
                                @foreach ($combined_plan_items as $elemento)
                                    <div class="{{ $class }} car-block">
                                        @php
                                            $item = $elemento->item;
                                            $combined_plans = $elemento->combined_plans
                                        @endphp
                                        @include('items.casaplan.item_include', ['like' => false, 'remove' => false, 'combined_plans' => $combined_plans])
                                        @php
                                            $class = 'col-md-6';
                                        @endphp
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        @endif

        {{-- <div class="pagebreak"></div> --}}
        <div class="row text-center">
            <div class="col-6 pt-4 pb-5">
                <h5>Prospecto</h5>
                {{ $brochure->quotation->customer->names }} {{ $brochure->quotation->customer->surnames }}<br>
                {{ $brochure->quotation->customer->email }}
            </div>
            <div class="col-6 pt-4 pb-5">
                <h5>Plan seleccionado</h5>
                MotorPlan de ${{ number_format($brochure->quotation->plan->amount) }} <br>
                Cuota fija mensual de ${{ number_format($brochure->quotation->plan->monthly_payment) }}
            </div>
            <div class="col-12 pb-4">
                <small>Precios sujetos a cambios sin previo aviso. Imágenes y especificaciones referenciales, sujetas a disponibilidad.</small>
            </div>
        </div>
    </div>

    @include('items.partials.gallery_modal')

    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/brochures.js') }}"></script>
</body>
</html>