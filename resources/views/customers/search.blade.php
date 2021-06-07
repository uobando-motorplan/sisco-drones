@extends('layouts.app')

@section('title', 'Crear referido')

@section('content')
    <!-- start page-title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">@yield('title')</h4>

                <div class="page-title-right d-none d-sm-block">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}">Referidos</a></li>
                        <li class="breadcrumb-item active">Crear</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-xl-4 col-lg-6">
            @if (session()->has('info'))
                <div class="alert alert-info" role="alert">{!! session('info') !!}</div>
            @endif
            @if (session()->has('warning'))
                <div class="alert alert-warning" role="alert">{{ session('warning') }}</div>
            @endif
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('customers.find') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="identification_type">Tipo identificación *</label>
                                    <select name="identification_type" id="identification_type" class="form-control{{ $errors->has('identification_type') ? ' is-invalid' : '' }}" data-validation="required" data-sanitize="trim upper">
                                        <option value="">- Seleccione un item -</option>
                                        <option value="{{ App\Customer::CEDULA }}" data-url="{{ route('validations.validar_cedula') }}" data-length="10"{{ old('identification_type') == App\Customer::CEDULA ? ' selected' : '' }}>Cédula</option>
                                        <option value="{{ App\Customer::RUC }}" data-url="{{ route('validations.validar_ruc') }}" data-length="10"{{ old('identification_type') == App\Customer::RUC ? ' selected' : '' }}>RUC</option>
                                        <option value="{{ App\Customer::PASAPORTE }}{{ old('identification_type') == App\Customer::PASAPORTE ? ' selected' : '' }}">Pasaporte</option>
                                    </select>
                                    {!! $errors->first('identification_type', '<span class="form-text form-error">:message</span>') !!}
                                </div>
                                <div class="form-group">
                                    <label for="identification">Número de identificación *</label>
                                    <input type="text" name="identification" id="identification" class="form-control{{ $errors->has('identification') ? ' is-invalid' : '' }}" value="{{ old('identification') }}" maxlength="50" placeholder="Máximo 20 caractres" data-validation="required" data-validation-url="" data-sanitize="trim upper">
                                    {!! $errors->first('identification', '<span class="form-text form-error">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-secondary waves-effect waves-light">Continuar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
    $( document ).ready(function() {
        $('#identification_type').trigger('change');
    });
    // Agrego validación para cédula o ruc
    $('#identification_type').on('change', function() {
        if ($(this).val()) {
            if ($(this).val() == 'C') {
                agregar_validacion($('#identification'), 'required number');
                $('#identification').attr('maxlength', 10);
                $('#identification').attr('placeholder', '10 dígitos');
            }
            if ($(this).val() == 'R') {
                agregar_validacion($('#identification'), 'required number');
                $('#identification').attr('maxlength', 13);
                $('#identification').attr('placeholder', '13 dígitos');
            }
            if ($(this).val() == 'P') {
                agregar_validacion($('#identification'), 'required');
                $('#identification').attr('maxlength', 20);
                $('#identification').attr('placeholder', 'Máximo 20 caractres');
            }
        } else {
            quitar_validacion($('#identification'));
            $('#identification').attr('maxlength', 20);
            $('#identification').attr('placeholder', 'Máximo 20 caractres');
        }
    });
    </script>
@endpush
