
<!-- modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('brochures.store', $product->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Crear y enviar brochure</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0">
                    @if ($errors->any())
                        <div class="alert alert-warning" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            {{ implode(' ', $errors->all()) }}
                        </div>
                    @endif
                    <div class="d-flex flex-row mb-4">
                        <div class="custom-control custom-radio mr-3">
                            <input type="radio" id="new_referred_1" name="new_referred" value="0" class="custom-control-input" {{ old('new_referred') ? (old('new_referred') == '0' ? 'checked' : '') : 'checked' }}>
                            <label class="custom-control-label" for="new_referred_1">A uno de mis referidos</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="new_referred_2" name="new_referred" value="1" class="custom-control-input" {{ old('new_referred') == '1' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="new_referred_2">A un nuevo referido</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <select name="quotation_id" id="quotation_id" class="custom-select select2{{ $errors->has('quotation_id') ? ' is-invalid' : '' }}" data-validation="required">
                                    <option value="">- Seleccione un referido -</option>
                                    @foreach ($quotations->sortBy('customer.surnames') as $quotation)
                                        <option value="{{ $quotation->id }}" {{ old('quotation_id') == $quotation->id ? 'selected' : '' }}>{{ $quotation->customer->getFullName() }} (Plan de ${{ number_format($quotation->plan->amount) }})</option>
                                    @endforeach
                                </select>
                                {!! $errors->first('quotation_id', '<span class="form-text form-error">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row d-none">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="names">Nombres *</label>
                                <input type="text" name="names" id="names" class="form-control{{ $errors->has('names') ? ' is-invalid' : '' }}" maxlength="50" placeholder="Máximo 50 caracteres" value="{{ old('names') }}" data-validation="" data-sanitize="trim capitalize">
                                {!! $errors->first('names', '<span class="form-text form-error">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="surnames">Apellidos *</label>
                                <input type="text" name="surnames" id="surnames" class="form-control{{ $errors->has('surnames') ? ' is-invalid' : '' }}" maxlength="50" placeholder="Máximo 50 caracteres" value="{{ old('surnames') }}" data-validation="" data-sanitize="trim capitalize">
                                {!! $errors->first('surnames', '<span class="form-text form-error">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="identification_type">Tipo identificación *</label>
                                <select name="identification_type" id="identification_type" class="custom-select{{ $errors->has('identification_type') ? ' is-invalid' : '' }}" data-validation="">
                                    <option value="">- Seleccione un item -</option>
                                    <option value="{{ App\Customer::CEDULA }}" data-url="{{ route('validations.validar_cedula') }}"{{ old('identification_type') == App\Customer::CEDULA ? ' selected' : '' }}>Cédula</option>
                                    <option value="{{ App\Customer::RUC }}" data-url="{{ route('validations.validar_ruc') }}"{{ old('identification_type') == App\Customer::RUC ? ' selected' : '' }}>RUC</option>
                                    <option value="{{ App\Customer::PASAPORTE }}{{ old('identification_type') == App\Customer::PASAPORTE ? ' selected' : '' }}">Pasaporte</option>
                                </select>
                                {!! $errors->first('identification_type', '<span class="form-text form-error">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="identification">Número de identificación *</label>
                                <input type="text" name="identification" id="identification" class="form-control{{ $errors->has('identification') ? ' is-invalid' : '' }}" value="{{ old('identification') }}" maxlength="15" placeholder="Máximo 15 caracteres" data-validation="" data-validation-url="" data-sanitize="trim upper">
                                {!! $errors->first('identification', '<span class="form-text form-error">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group">
                                <label for="city_id">Ciudad *</label>
                                <select name="city_id" id="city_id" class="custom-select select2{{ $errors->has('city_id') ? ' is-invalid' : '' }}" data-validation="">
                                    <option value="">- Seleccione un item -</option>
                                    @foreach ($cities as $id => $name)
                                        <option value="{{ $id }}" {{ old('city_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->first('city_id', '<span class="form-text form-error">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cell_number">Teléfono móvil *</label> <small class="text-muted">(Ejemplo: 0985462885)</small>
                                <input type="text" name="cell_number" id="cell_number" class="form-control{{ $errors->has('cell_number') ? ' is-invalid' : '' }}" value="{{ old('cell_number') }}" maxlength="10" placeholder="10 dígitos" data-validation="" data-sanitize="trim">
                                {!! $errors->first('cell_number', '<span class="form-text form-error">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Correo electrónico *</label>
                                <input type="text" name="email" id="email" class="form-control{{ $errors->has('active') ? ' is-invalid' : '' }}" maxlength="150" placeholder="Máximo 150 caracteres" value="{{ old('email') }}" data-validation="" data-sanitize="trim lower">
                                {!! $errors->first('email', '<span class="form-text form-error">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="plan_id">Plan *</label>
                                <select name="plan_id" id="plan_id" class="custom-select{{ $errors->has('plan_id') ? ' is-invalid' : '' }}" data-validation="required">
                                    <option value="">- Seleccione un item -</option>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>${{ $plan->amount }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->first('plan_id', '<span class="form-text form-error">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="preference_id">{{ $product->id == App\Product::MOTORPLAN ? 'Marca' : 'Tipo de bien' }} *</label>
                                <select name="preference_id" id="preference_id" class="custom-select{{ $errors->has('preference_id') ? ' is-invalid' : '' }}" data-validation="">
                                    <option value="">- Seleccione un item -</option>
                                    @foreach ($preferences as $id => $name)
                                        <option value="{{ $id }}" {{ old('preference_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->first('preference_id', '<span class="form-text form-error">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Aceptar</button>
                </div>
            </form>
            <input type="hidden" id="form_errors" value="{{ $errors->any() ? 1 : 0 }}">
        </div>
    </div>
</div>
<!-- end modal -->

@push('css')
    <!--Select2-->
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@push('js')
    <!--Select2-->
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $('.select2').select2({
            width: "100%"
        });
    </script>
@endpush
