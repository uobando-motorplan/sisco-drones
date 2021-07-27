@extends('layouts.app')

@section('title', 'Editar referido')

@section('content')
    <!-- start page-title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">@yield('title')</h4>

                <div class="page-title-right d-none d-sm-block">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Referidos</a></li>
                        <li class="breadcrumb-item active">Editar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page-title -->
    <div class="row">
        <div class="col-xl-7">
            @if ($errors->any())
                <div class="alert alert-warning" role="alert">
                    <button type="button" class="close pl-2" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <i class="mdi mdi-alert-outline pr-2"></i> 
                    {!! implode(' ', $errors->all()) !!}
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('quotations.update', $quotation->id) }}">
                        @csrf
                        @method('PUT')
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#customer-tab" role="tab">
                                    <span class="d-block d-sm-none">Referido</span>
                                    <span class="d-none d-sm-block">Referido</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#quotation-tab" role="tab">
                                    <span class="d-block d-sm-none">Producto</span>
                                    <span class="d-none d-sm-block">Producto</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#observations-tab" role="tab">
                                    <span class="d-block d-sm-none">Observaciones</span>
                                    <span class="d-none d-sm-block">Observaciones</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#questions-tab" role="tab">
                                    <span class="d-block d-sm-none">Adicional</span>
                                    <span class="d-none d-sm-block">Adicional</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content pt-3">
                            <div class="tab-pane active" id="customer-tab" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="names">Nombres *</label>
                                            <input type="text" name="names" id="names" class="form-control{{ $errors->has('active') ? ' is-invalid' : '' }}" maxlength="50" placeholder="Máximo 50 caracteres" value="{{ $quotation->customer->names }}" data-validation="required" data-sanitize="trim capitalize">
                                            {!! $errors->first('names', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="surnames">Apellidos *</label>
                                            <input type="text" name="surnames" id="surnames" class="form-control{{ $errors->has('active') ? ' is-invalid' : '' }}" maxlength="50" placeholder="Máximo 50 caracteres" value="{{ $quotation->customer->surnames }}" data-validation="required" data-sanitize="trim capitalize">
                                            {!! $errors->first('surnames', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="identification_type">Tipo identificación *</label>
                                            <select name="identification_type" id="identification_type" class="form-control{{ $errors->has('identification_type') ? ' is-invalid' : '' }}" data-validation="required">
                                                <option value="">- Seleccione un item -</option>
                                                <option value="{{ App\Customer::CEDULA }}" data-url="{{ route('validations.validar_cedula') }}" data-length="10"{{ $quotation->customer->identification_type == App\Customer::CEDULA ? ' selected' : '' }}>Cédula</option>
                                                <option value="{{ App\Customer::RUC }}" data-url="{{ route('validations.validar_ruc') }}" data-length="10"{{ $quotation->customer->identification_type == App\Customer::RUC ? ' selected' : '' }}>RUC</option>
                                                <option value="{{ App\Customer::PASAPORTE }}{{ $quotation->customer->identification_type == App\Customer::PASAPORTE ? ' selected' : '' }}">Pasaporte</option>
                                            </select>
                                            {!! $errors->first('identification_type', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        @php
                                            switch ($quotation->customer->identification_type) {
                                                case App\Customer::CEDULA:
                                                    $maxlength = 10;
                                                    $placeholder = '10 dígitos';
                                                    $data_validation = 'required number server';
                                                    $data_validation_url = route('validations.validar_cedula');
                                                    break;
                                                case App\Customer::RUC:
                                                    $maxlength = 13;
                                                    $placeholder = '13 dígitos';
                                                    $data_validation = 'required number server';
                                                    $data_validation_url = route('validations.validar_ruc');
                                                    break;
                                                case App\Customer::PASAPORTE:
                                                    $maxlength = 20;
                                                    $placeholder = 'Máximo 20 caracteres';
                                                    $data_validation = 'required number server';
                                                    $data_validation_url = '';
                                                    break;
                                            }
                                        @endphp
                                        <div class="form-group">
                                            <label for="identification">Número de identificación *</label>
                                            <input type="text" name="identification" id="identification" class="form-control{{ $errors->has('identification') ? ' is-invalid' : '' }}" value="{{ $quotation->customer->identification }}" maxlength="{{ $maxlength }}" placeholder="{{ $placeholder }}" data-validation="{{ $data_validation }}" data-validation-url="{{ $data_validation_url }}" data-sanitize="trim">
                                            {!! $errors->first('identification', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="province_id">Provincia *</label>
                                            <select name="province_id" id="province_id" class="form-control{{ $errors->has('province_id') ? ' is-invalid' : '' }}" data-validation="required" data-url="{{ route('cities.index') }}">
                                                <option value="">- Seleccione un item -</option>
                                                @foreach ($provinces as $id => $name)
                                                    <option value="{{ $id }}" {{ $quotation->customer->city->province_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            {!! $errors->first('province_id', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city_id">Ciudad *</label>
                                            <select name="city_id" id="city_id" class="form-control{{ $errors->has('city_id') ? ' is-invalid' : '' }}" data-validation="required">
                                                <option value="">- Seleccione un item -</option>
                                                @foreach ($cities as $id => $name)
                                                    <option value="{{ $id }}" {{ $quotation->customer->city_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            {!! $errors->first('city_id', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cell_number">Teléfono móvil</label> <small class="text-muted">(Ejemplo: 0985462885)</small>
                                            <input type="text" name="cell_number" id="cell_number" class="form-control mobile_phone{{ $errors->has('cell_number') ? ' is-invalid' : '' }}" value="{{ $quotation->customer->cell_number }}" maxlength="10" placeholder="10 dígitos" data-validation="number" data-validation-optional="true" data-sanitize="trim">
                                            {!! $errors->first('cell_number', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone_number">Teléfono fijo</label> <small class="text-muted">(Ejemplo: 042879215)</small>
                                            <input type="text" name="phone_number" id="phone_number" class="form-control landline_phone{{ $errors->has('phone_number') ? ' is-invalid' : '' }}" value="{{ $quotation->customer->phone_number }}" maxlength="9" placeholder="9 dígitos" data-validation="number" data-validation-optional="true" data-sanitize="trim">
                                            {!! $errors->first('phone_number', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contact_method_id">Método de contacto *</label>
                                            <select name="contact_method_id" id="contact_method_id" class="form-control{{ $errors->has('contact_method_id') ? ' is-invalid' : '' }}" data-validation="required">
                                                <option value="">- Seleccione un item -</option>
                                                @foreach ($contact_methods as $id => $name)
                                                    <option value="{{ $id }}" {{ $quotation->customer->contact_method_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            {!! $errors->first('contact_method_id', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Correo electrónico *</label>
                                            <input type="text" name="email" id="email" class="form-control{{ $errors->has('active') ? ' is-invalid' : '' }}" maxlength="150" placeholder="Máximo 150 caracteres" value="{{ $quotation->customer->email }}" data-validation="required email" data-sanitize="trim lower">
                                            {!! $errors->first('email', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="quotation-tab" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_id">Producto *</label>
                                            <select name="product_id" id="product_id" class="form-control{{ $errors->has('product_id') ? ' is-invalid' : '' }}" data-validation="required" data-plans-url="{{ route('plans.index') }}" data-preferences-url="{{ route('preferences.index') }}">
                                                <option value="">- Seleccione un item -</option>
                                                @foreach ($products as $id => $name)
                                                    <option value="{{ $id }}" {{ $quotation->plan->product_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            {!! $errors->first('product_id', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="plan_id">Plan *</label>
                                            <select name="plan_id" id="plan_id" class="form-control{{ $errors->has('plan_id') ? ' is-invalid' : '' }}" data-validation="required">
                                                <option value="">- Seleccione un item -</option>
                                                @foreach ($plans as $id => $amount)
                                                    <option value="{{ $id }}" {{ $quotation->plan_id == $id ? 'selected' : '' }}>${{ $amount }}</option>
                                                @endforeach
                                            </select>
                                            {!! $errors->first('plan_id', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md">
                                        <div class="form-group">
                                            <label for="preference_id">Preferencia *</label>
                                            <select name="preference_id" id="preference_id" class="form-control{{ $errors->has('preference_id') ? ' is-invalid' : '' }}" data-validation="required">
                                                <option value="">- Seleccione un item -</option>
                                                @foreach ($preferences as $id => $name)
                                                    <option value="{{ $id }}" {{ $quotation->preference_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            {!! $errors->first('preference_id', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md{{ $quotation->plan->product_id == App\Product::MOTORPLAN ? '' : ' d-none' }}">
                                        <div class="form-group">
                                            <label for="condition">Condición *</label>
                                            <select name="condition" id="condition" class="form-control{{ $errors->has('condition') ? ' is-invalid' : '' }}" data-validation="">
                                                <option value="">- Seleccione un item -</option>
                                                <option value="{{ App\Quotation::NUEVO }}" {{ $quotation->condition == App\Quotation::NUEVO ? 'selected' : '' }}>Nuevo</option>
                                                <option value="{{ App\Quotation::USADO }}" {{ $quotation->condition == App\Quotation::USADO ? 'selected' : '' }}>Usado</option>
                                                <option value="{{ App\Quotation::SIN_PREFERENCIA }}" {{ $quotation->condition == App\Quotation::SIN_PREFERENCIA ? 'selected' : '' }}>No hay preferencia</option>
                                            </select>
                                            {!! $errors->first('condition', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="form-group">
                                            <label for="product_use">¿Para qué usará el bien?</label>
                                            <select name="product_use" id="product_use" class="form-control{{ $errors->has('product_use') ? ' is-invalid' : '' }}" data-validation="">
                                                <option value="">- Seleccione un item -</option>
                                                <option value="{{ App\Quotation::PERSONAL }}" {{ $quotation->product_use == App\Quotation::PERSONAL ? 'selected' : '' }}>Uso personal</option>
                                                <option value="{{ App\Quotation::TRABAJO }}" {{ $quotation->product_use == App\Quotation::TRABAJO ? 'selected' : '' }}>Trabajo</option>
                                            </select>
                                            {!! $errors->first('product_use', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md{{ $quotation->plan->product_id == App\Product::CASAPLAN ? '' : ' d-none' }}">
                                        <div class="form-group">
                                            <label for="reserved">¿Dejó reservado el bien?</label>
                                            <select name="reserved" id="reserved" class="form-control{{ $errors->has('reserved') ? ' is-invalid' : '' }}" data-validation="">
                                                <option value="">- Seleccione un item -</option>
                                                <option value="S" {{ $quotation->reserved == 'S' ? 'selected' : '' }}>Sí</option>
                                                <option value="N" {{ $quotation->reserved == 'N' ? 'selected' : '' }}>No</option>
                                            </select>
                                            {!! $errors->first('reserved', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="observations-tab" role="tabpanel">
                                <div class="form-group">
                                    <label for="comment">Comentarios del prospecto sobre el producto</label>
                                    <textarea name="comment" id="comment" rows="3" class="form-control valid" placeholder="Máximo 255 caracteres" data-validation="length" data-validation-length="max255" data-sanitize="trim">{{ $quotation->comment }}</textarea>
                                    {!! $errors->first('comment', '<span class="form-text form-error">:message</span>') !!}
                                </div>
                                <div class="form-group">
                                    <label for="drone_comment">Observaciones para el vendedor</label>
                                    <textarea name="drone_comment" id="drone_comment" rows="3" class="form-control valid" placeholder="Máximo 255 caracteres" data-validation="length" data-validation-length="max255" data-sanitize="trim">{{ $quotation->drone_comment }}</textarea>
                                    {!! $errors->first('drone_comment', '<span class="form-text form-error">:message</span>') !!}
                                </div>
                            </div>

                            <div class="tab-pane" id="questions-tab" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="occupation_id">Ocupación *</label>
                                            <select name="occupation_id" id="occupation_id" class="form-control{{ $errors->has('occupation_id') ? ' is-invalid' : '' }}" data-validation="required">
                                                <option value="">- Seleccione un item -</option>
                                                @foreach ($occupations as $id => $name)
                                                    <option value="{{ $id }}" {{ $quotation->customer->occupation_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            {!! $errors->first('occupation_id', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="occupation_period_id">Periodo *</label>
                                            <select name="occupation_period_id" id="occupation_period_id" class="form-control{{ $errors->has('occupation_period_id') ? ' is-invalid' : '' }}" data-validation="required">
                                                <option value="">- Seleccione un item -</option>
                                                @foreach ($occupation_periods as $id => $name)
                                                    <option value="{{ $id }}" {{ $quotation->customer->occupation_period_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            {!! $errors->first('occupation_period_id', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="has_social_security">¿Está afiliado al IESS? *</label>
                                            <select name="has_social_security" id="has_social_security" class="form-control{{ $errors->has('has_social_security') ? ' is-invalid' : '' }}" data-validation="required">
                                                <option value="">- Seleccione un item -</option>
                                                <option value="S" {{ $quotation->customer->has_social_security == 'S' ? 'selected' : '' }}>Sí</option>
                                                <option value="N" {{ $quotation->customer->has_social_security == 'N' ? 'selected' : '' }}>No</option>
                                            </select>
                                            {!! $errors->first('has_social_security', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="can_pay_down_payment">¿Tiene dinero para una entrada? *</label>
                                            <select name="can_pay_down_payment" id="can_pay_down_payment" class="form-control{{ $errors->has('can_pay_down_payment') ? ' is-invalid' : '' }}" data-validation="required">
                                                <option value="">- Seleccione un item -</option>
                                                <option value="S" {{ $quotation->customer->can_pay_down_payment == 'S' ? 'selected' : '' }}>Sí</option>
                                                <option value="N" {{ $quotation->customer->can_pay_down_payment == 'N' ? 'selected' : '' }}>No</option>
                                            </select>
                                            {!! $errors->first('can_pay_down_payment', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="monthly_payment_capacity">¿Cuánto puede pagar mensualmente? *</label>
                                            <input type="number" name="monthly_payment_capacity" id="monthly_payment_capacity" class="form-control{{ $errors->has('monthly_payment_capacity') ? ' is-invalid' : '' }}" value="{{ $quotation->customer->monthly_payment_capacity }}" maxlength="10" placeholder="Cantidad en USD" data-validation="required number" data-sanitize="trim">
                                            {!! $errors->first('monthly_payment_capacity', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="has_applied_to_credit">¿Aplicó a un tipo de crédito? *</label>
                                            <select name="has_applied_to_credit" id="has_applied_to_credit" class="form-control{{ $errors->has('has_applied_to_credit') ? ' is-invalid' : '' }}" data-validation="required">
                                                <option value="">- Seleccione un item -</option>
                                                <option value="S" {{ $quotation->customer->has_applied_to_credit == 'S' ? 'selected' : '' }}>Sí</option>
                                                <option value="N" {{ $quotation->customer->has_applied_to_credit == 'N' ? 'selected' : '' }}>No</option>
                                            </select>
                                            {!! $errors->first('has_applied_to_credit', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-12{{ $quotation->customer->has_applied_to_credit == 'S' ? '' : ' d-none' }}">
                                        <div class="form-group">
                                            <label for="why_didnt_buy">¿Por qué no compró por ahí? *</label>
                                            <input type="text" name="why_didnt_buy" id="why_didnt_buy" class="form-control{{ $errors->has('active') ? ' is-invalid' : '' }}" maxlength="100" placeholder="Máximo 100 caracteres" value="{{ $quotation->customer->why_didnt_buy }}" data-validation="" data-sanitize="trim">
                                            {!! $errors->first('why_didnt_buy', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-1">
                                    <input type="hidden" name="deleted_at">
                                    <button class="btn btn-secondary waves-effect waves-light mr-1" type="submit">Aceptar</button>
                                    <a href="{{ route('quotations.index') }}" class="btn btn-light waves-effect waves-light">Cancelar</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/quotation.js') }}"></script>
@endpush
