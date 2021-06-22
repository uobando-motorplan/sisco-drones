<?php
function activeMenu($url) {
    return request()->is($url) ? ' mm-active' : '';
}
?>
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="CasaPlan-MotorPlan" name="author" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <!-- DataTables Css -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive DataTable Css -->
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Ion Range Slider Css -->
    <link href="{{ asset('assets/libs/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet" type="text/css"/>
    @stack('css')
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" type="text/css" />
</head>

<body data-sidebar="">

    <!-- start layout-wrapper -->
    <div id="layout-wrapper">

        <!-- start left sidebar -->
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <div class="navbar-brand-box">
                        <a href="{{ url('/') }}" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="{{ asset('assets/images/logo-sm-dark.png') }}" alt="" height="40">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="44">
                            </span>
                        </a>
                    </div>
                    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                        <i class="ri-menu-2-line align-middle"></i>
                    </button>
                </div>
                <div class="d-flex">
                    <div class="dropdown d-none d-lg-inline-block ml-1">
                        <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                            <i class="ri-fullscreen-line"></i>
                        </button>
                    </div>
                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-brochure-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-url="{{ route('items.list') }}">
                            <i class="ri-heart-line"></i>
                            @if (session()->has('items'))
                                @if (count(session()->get('items')))
                                    <span class="badge badge-danger badge-pill noti-icon-badge">{{ count(session()->get('items')) }}</span>
                                @endif
                            @endif
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0" aria-labelledby="page-header-brochure-dropdown">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0">Artículos del brochure</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="items" id="brochure-items"></div>
                            @if (session()->has('product'))
                                <div class="p-2 border-top">
                                    <a class="btn btn-sm font-size-14 btn-block" href="{{ route('brochures.create', session()->get('product')) }}">Crear y enviar brochure</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-url="{{ route('notifications.readAll') }}">
                            <i class="ri-notification-3-line"></i> {!! Auth::user()->unreadNotifications->count() ? '<span class="noti-dot"></span>' : '' !!}
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0" aria-labelledby="page-header-notifications-dropdown">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0">Notificaciones</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="items" data-simplebar>
                                @foreach (Auth::user()->notifications()->limit(5)->get() as $notification)
                                    <a href="{{ $notification->data['link'] ? $notification->data['link'] : 'javascript:void(0);' }}" class="text-reset notification-item">
                                        <div class="media">
                                            <div class="avatar-xs mr-3">
                                                <span class="avatar-title {{ $notification->read_at ? 'bg-secondary' : 'bg-info' }} rounded-circle font-size-16">
                                                    <i class="{{ $notification->data['icon'] }}"></i>
                                                </span>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="mt-0 mb-1">{{ $notification->data['title'] }}</h6>
                                                <div class="font-size-12">
                                                    <p class="mb-1">{{ $notification->data['text'] }}</p>
                                                    <p class="mb-0"><i class="mdi mdi-clock-outline"></i> {{ $notification->created_at->diffForHumans(Carbon\Carbon::now()) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <div class="p-2 border-top">
                                <a class="btn btn-sm font-size-14 btn-block" href="{{ route('notifications.index') }}">Ver todas</a>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown d-inline-block user-dropdown">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if (auth()->user()->photo == 'hombre.png' OR auth()->user()->photo == 'mujer.png')
                                <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/'.auth()->user()->photo) }}" alt="avatar">
                            @else
                                <img class="rounded-circle header-profile-user" src="/storage/users/{{ auth()->user()->photo }}" alt="avatar">
                            @endif
                            <span class="d-none d-xl-inline-block ml-1">{{ auth()->user()->name }}</span> <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="{{ route('users.profile') }}"><i class="ri-user-line mr-1"></i> Perfil</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="ri-shut-down-line mr-1"></i> Cerrar sesión</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </header>

        <!-- start left sidebar -->
        <div class="vertical-menu">
            <div data-simplebar class="h-100">
                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu list-unstyled" id="side-menu">
                        <li class="menu-title">Menu</li>
                        <li>
                            <a href="{{ url('/') }}" class="waves-effect">
                                <i class="ri-dashboard-line"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="{{ activeMenu('referreds*') }}">
                            <a href="javascript:void(0);" class="has-arrow waves-effect{{ activeMenu('referreds*') }}">
                                <i class="ri-user-line"></i>
                                <span>Referidos</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{ route('customers.search') }}" class="{{ activeMenu('referreds/create*') }}">Crear</a></li>
                                <li><a href="{{ route('quotations.index') }}">Listar</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('events.index') }}" class="waves-effect{{ activeMenu('monitoring*') }}">
                                <i class="ri-calendar-2-line"></i>
                                <span>Seguimientos</span>
                            </a>
                        </li>
                        <li class="{{ activeMenu('payment-requests*') }}">
                            <a href="javascript:void(0);" class="has-arrow waves-effect{{ activeMenu('payment-requests*') }}">
                                <i class="ri-money-dollar-box-line"></i>
                                <span>Solicitudes de pago</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{ route('payment_requests.create') }}">Crear</a></li>
                                <li><a href="{{ route('payment_requests.index') }}">Listar</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('folders.index') }}" class="waves-effect{{ activeMenu('resources*') }}">
                                <i class="ri-folders-line"></i>
                                <span>Librería</span>
                            </a>
                        </li>
                        <li class="{{ activeMenu('items*') }}{{ activeMenu('brochures*') }}">
                            <a href="javascript:void(0);" class="has-arrow waves-effect{{ activeMenu('items*') }}{{ activeMenu('brochures*') }}">
                                <i class="ri-book-2-line"></i>
                                <span>Mi catálogo</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{ route('items.index', App\Product::CASAPLAN) }}" class="">CasaPlan</a></li>
                                <li><a href="{{ route('items.index', App\Product::MOTORPLAN) }}" class="">MotorPlan</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- end left sidebar -->

        <!-- start main-content -->
        <div class="main-content">
            <!-- start page-content -->
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            <!-- end page-content -->

            <!-- start footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            {{ config('app.name') }} - {{ env('APP_VERSION') }}
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-right d-none d-sm-block">
                                CasaPlan-MotorPlan © {{ date('Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end footer -->
        </div>
        <!-- end main-content-->

    </div>
    <!-- end layout-wrapper -->

    <!-- Javascript -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <!-- App Js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <!-- Datatable Js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Form Validator Js -->
    <script src="{{ asset('assets/libs/form-validator/jquery.form-validator.min.js') }}"></script>
    <!-- Inputmask Js -->
    <script src="{{ asset('assets/libs/inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- Ion Range Slider Js -->
    <script src="{{ asset('assets/libs/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <!-- scripts js -->
    <script src="{{ asset('assets/js/scripts.js') }}?{{ rand() }}"></script>
    @stack('js')

</body>

</html>
