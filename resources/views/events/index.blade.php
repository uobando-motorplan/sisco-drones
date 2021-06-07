@section('title', 'Seguimientos')

@extends('layouts.app')

@section('content')
    <!-- start page-title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">@yield('title')</h4>

                <div class="page-title-right d-none d-sm-block">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><a href="javascript: void(0);">Seguimientos</a></li>
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
                    {!! $calendar->calendar() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <!-- Fullcalendar -->
    <link href="{{ asset('assets/libs/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@push('js')
    <!-- Fullcalendar -->
    <script src="{{ asset('assets/libs/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/libs/fullcalendar/fullcalendar.min.js') }}"></script>
    <script src='{{ asset('assets/libs/fullcalendar/lang/es.js') }}'></script>
    {!! $calendar->script() !!}
    
    <!-- Categoria del evento -->
    <script type="text/javascript">
        $('#event_category').change(function() {
            window.location.href = "{{ route('events.index', 'category=') }}" + $(this).val();
        });
    </script>
@endpush