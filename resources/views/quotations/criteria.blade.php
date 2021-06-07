@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
    <!-- start page-title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">@yield('title')</h4>

                <div class="page-title-right d-none d-sm-block">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><a href="javascript: void(0);">Reportes</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-xl-6 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('quotations.report') }}">
                        @csrf
                        
                        <h4 class="card-title mt-0">Generar reporte</h4>
                        <p class="card-text">Marque la casilla de verificación del filtro que desee aplicar y elija un valor.</p>
                        <div class="row">
                            <div class="col-12">
                                <div class="custom-control custom-checkbox my-1">
                                    <input type="checkbox" class="custom-control-input" id="chkProduct">
                                    <label class="custom-control-label" for="chkProduct">Filtrar por producto</label> <small class="text-muted"></small>
                                </div>
                            </div>
                            <div class="col-1"></div>
                            <div class="col-11">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <select name="product_id" id="product_id" class="form-control{{ $errors->has('product_id') ? ' is-invalid' : '' }}" data-validation="">
                                                <option value="">- Seleccione un item - </option>
                                                @foreach ($products as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            {!! $errors->first('product_id', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="custom-control custom-checkbox my-1">
                                    <input type="checkbox" class="custom-control-input" id="chkStatus">
                                    <label class="custom-control-label" for="chkStatus">Filtrar por estado</label> <small class="text-muted">(Toma en cuenta la fecha de modificación)</small>
                                </div>
                            </div>
                            <div class="col-1"></div>
                            <div class="col-11">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <select name="status_id" id="status_id" class="form-control{{ $errors->has('status_id') ? ' is-invalid' : '' }}" data-validation="">
                                                <option value="">- Seleccione un item - </option>
                                                @foreach ($statuses as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            {!! $errors->first('status_id', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="custom-control custom-checkbox my-1">
                                    <input type="checkbox" class="custom-control-input" id="chkScore">
                                    <label class="custom-control-label" for="chkScore">Filtrar por calificación</label> <small class="text-muted">(Toma en cuenta la fecha de modificación)</small>
                                </div>
                            </div>
                            <div class="col-1"></div>
                            <div class="col-11">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <select name="score_id" id="score_id" class="form-control{{ $errors->has('score_id') ? ' is-invalid' : '' }}" data-validation="">
                                                <option value="">- Seleccione un item - </option>
                                                @foreach ($scores as $id => $name)
                                                    <option value="{{ $id }}">{{ $id }} - {{ $name }}</option>
                                                @endforeach
                                            </select>
                                            {!! $errors->first('score_id', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="custom-control custom-checkbox my-1">
                                    <input type="checkbox" class="custom-control-input" id="chkDates" checked="true" disabled="true">
                                    <label class="custom-control-label" for="chkDates">Filtrar por rango de fechas</label>
                                </div>
                            </div>
                            <div class="col-1"></div>
                            <div class="col-11">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') ? old('start_date') : Carbon\Carbon::now()->format('Y-m-d') }}" placeholder="dd/mm/yyyy" data-validation="required">
                                            {!! $errors->first('start_date', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') ? old('end_date') : Carbon\Carbon::now()->format('Y-m-d') }}" placeholder="dd/mm/yyyy" data-validation="required">
                                            {!! $errors->first('end_date', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-secondary waves-effect waves-light">Generar reporte</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/pages/criteria.js') }}"></script>
@endpush
