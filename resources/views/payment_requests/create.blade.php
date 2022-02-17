@extends('layouts.app')

@section('title', 'Crear solicitud de pago')

@section('content')
    <!-- start page-title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">@yield('title')</h4>

                <div class="page-title-right d-none d-sm-block">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Solicitudes de pago</a></li>
                        <li class="breadcrumb-item active"><a href="javascript: void(0);"></a>Crear</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page-title -->

    @if (! $error)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-warning" role="alert">
                                <button type="button" class="close pl-2" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <i class="mdi mdi-alert-outline pr-2"></i> 
                                @foreach ($errors->all() as $error)
                                    {{ $error }}<br>
                                @endforeach
                            </div>
                        @endif
                        <form method="POST" enctype="multipart/form-data" action="{{ route('payment_requests.store') }}" >
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead>
                                        <tr class="bg-secondary text-white">
                                            <th class="text-center">Detalle</th>
                                            <th>Liquidación ID</th>
                                            <th>Desde</th>
                                            <th>Hasta</th>
                                            <th>Fecha registro</th>
                                            <th class="text-right">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $subtotal = 0;
                                        @endphp
                                        @foreach ($commissions as $commission)
                                            <tr>
                                                <td class="text-center">
                                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-secondary" title="Consultar detalle" data-toggle="modal" data-target="#cierreModal" data-id="{{ $commission['CierreId'] }}" data-url="{{ route('payment_requests.details', ['commission_id' => $commission['CierreId']]) }}"><i class="ri-eye-line"></i></a>
                                                    <input type="hidden" name="cierre_id[]" value="{{ $commission['CierreId'] }}">
                                                </td>
                                                <td>{{ $commission['CierreId'] }}</td>
                                                <td>{{ Carbon\Carbon::parse($commission['FechaDesde'])->format('Y-m-d') }}</td>
                                                <td>{{ Carbon\Carbon::parse($commission['FechaHasta'])->format('Y-m-d') }}</td>
                                                <td>{{ Carbon\Carbon::parse($commission['FechaRegistro'])->format('Y-m-d') }}</td>
                                                <td class="text-right">${{ number_format($commission['DroneCommissionDistribution']['ValorComision'], 2) }}</td>
                                            </tr>
                                            @php
                                                $subtotal += $commission['DroneCommissionDistribution']['ValorComision'];
                                            @endphp
                                        @endforeach
                                        <tr class="">
                                            <th colspan="5" class="text-right">SUBTOTAL</th>
                                            <th class="text-right bg-">${{ number_format($subtotal, 2) }}</th>
                                        </tr>
                                        <tr class="">
                                            <th colspan="5" class="text-right">IVA</th>
                                            <th class="text-right bg-">${{ number_format($subtotal * $iva->value, 2) }}</th>
                                        </tr>
                                        <tr class="">
                                            <th colspan="5" class="text-right">TOTAL</th>
                                            <th class="text-right bg-light">${{ number_format($subtotal + ($subtotal * $iva->value), 2) }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <h5 class="my-4">Información para el pago</h5>
                            <div class="border rounded pt-3 px-3 pb-2 bg-light">
                                <div class="row">
                                    <div class="col-xl-4 col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label for="bank_id">Banco *</label> 
                                                <select name="bank_id" id="bank_id" class="custom-select{{ $errors->has('bank_id') ? ' is-invalid' : '' }}" data-validation="required">
                                                    <option value="">- Seleccione un item -</option>
                                                    @foreach ($banks as $id => $name)
                                                        <option value="{{ $id }}" {{ (old('bank_id') ? (old('bank_id') == $id ? 'selected' : '') : (auth()->user()->drone->bank_id == $id ? 'selected' : '')) }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                                {!! $errors->first('bank_id', '<span class="form-text form-error">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6">
                                        <div class="form-group">
                                            <label for="bank_account_type">Tipo de cuenta *</label>
                                            <select name="bank_account_type" id="bank_account_type" class="custom-select{{ $errors->has('bank_account_type') ? ' is-invalid' : '' }}" data-validation="required">
                                                <option value="">- Seleccione un item -</option>
                                                <option value="{{ App\Bank::AHORROS }}" {{ (old('bank_account_type') ? (old('bank_account_type') == App\Bank::AHORROS ? 'selected' : '') : (auth()->user()->drone->bank_account_type == App\Bank::AHORROS ? 'selected' : '')) }}>Ahorros</option>
                                                <option value="{{ App\Bank::CORRIENTE }}" {{ (old('bank_account_type') ? old('bank_account_type') == App\Bank::CORRIENTE : (auth()->user()->drone->bank_account_type == App\Bank::CORRIENTE ? 'selected' : '')) }}>Corriente</option>
                                            </select>
                                            {!! $errors->first('bank_account_type', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6">
                                        <div class="form-group">
                                            <label for="bank_account_number">Número de cuenta *</label>
                                            <input type="text" name="bank_account_number" id="bank_account_number" class="form-control{{ $errors->has('bank_account_number') ? ' is-invalid' : '' }}" value="{{ (old('bank_account_number') ? old('bank_account_number') : auth()->user()->drone->bank_account_number) }}" maxlength="20" placeholder="Máximo 20 caracteres" data-validation="required" data-sanitize="trim">
                                            {!! $errors->first('bank_account_number', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6">
                                        <div class="form-group">
                                            <label for="beneficiary_identification">Cédula/Ruc *</label>
                                            <input type="text" name="beneficiary_identification" id="beneficiary_identification" class="form-control{{ $errors->has('active') ? ' is-invalid' : '' }}" maxlength="13" placeholder="Máximo 13 caracteres" value="{{ (old('beneficiary_identification') ? old('beneficiary_identification') : auth()->user()->drone->identification_number) }}" data-validation="required number" data-sanitize="trim">
                                            {!! $errors->first('beneficiary_identification', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6">
                                        <div class="form-group">
                                            <label for="beneficiary_name">Nombre *</label>
                                            <input type="text" name="beneficiary_name" id="beneficiary_name" class="form-control input-mask{{ $errors->has('active') ? ' is-invalid' : '' }}" maxlength="100" placeholder="Máximo 100 caracteres" value="{{ (old('beneficiary_name') ? old('beneficiary_name') : auth()->user()->drone->getFullName(true)) }}" data-validation="required" data-sanitize="trim">
                                            {!! $errors->first('beneficiary_name', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6">
                                        <div class="form-group">
                                            <label for="beneficiary_email">Correo electrónico *</label>
                                            <input type="text" name="beneficiary_email" id="beneficiary_email" class="form-control input-mask{{ $errors->has('active') ? ' is-invalid' : '' }}" maxlength="150" placeholder="Máximo 150 caracteres" value="{{ (old('beneficiary_email') ? old('beneficiary_email') : auth()->user()->drone->email) }}" data-validation="required email" data-sanitize="trim">
                                            {!! $errors->first('beneficiary_email', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded p-3 my-3">
                                <div class="row">
                                    <div class="col-12">
                                        <p>Utiliza los siguientes datos para que elaborares correctamente tu factura.</p>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#invoiceModal">
                                    Ver datos para elaborar la factura
                                </button>
                            </div>

                            <div class="border rounded pt-3 px-3 pb-2 bg-light">
                                <div class="row">
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="invoice_number">Número de factura *</label>
                                            <input type="text" name="invoice_number" id="invoice_number" class="form-control input-mask{{ $errors->has('active') ? ' is-invalid' : '' }}" maxlength="17" placeholder="15 dígitos" value="{{ old('invoice_number') }}" data-validation="required" data-sanitize="trim" data-inputmask="'mask': '999-999-999999999'">
                                            {!! $errors->first('invoice_number', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="invoice_value">Valor total *</label>
                                            <input type="text" name="invoice_value" id="invoice_value" class="form-control input-mask{{ $errors->has('active') ? ' is-invalid' : '' }}" maxlength="9" placeholder="Total en dólares" value="{{ number_format($subtotal + ($subtotal * $iva->value), 2) }}" data-validation="required" data-sanitize="trim" readonly>
                                            {!! $errors->first('invoice_value', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="invoice_autorization_number">Número de autorización *</label>
                                            <input type="text" name="invoice_autorization_number" id="invoice_autorization_number" class="form-control input-mask{{ $errors->has('active') ? ' is-invalid' : '' }}" maxlength="10" placeholder="10 dígitos" value="{{ old('invoice_autorization_number') }}" data-validation="required" data-sanitize="trim" data-inputmask="'mask': '9999999999'">
                                            {!! $errors->first('invoice_autorization_number', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="form-group">
                                            <label for="invoice_autorization_date">Fecha de autorización *</label>
                                            <input type="date" name="invoice_autorization_date" id="invoice_autorization_date" class="form-control{{ $errors->has('invoice_autorization_date') ? ' is-invalid' : '' }}" value="{{ old('invoice_autorization_date') }}" placeholder="dd/mm/yyyy" data-validation="required">
                                            {!! $errors->first('invoice_autorization_date', '<span class="form-text form-error">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="file">Foto de la factura * <small>(Formato: JPG, PNG, PDF - Máximo: 4Mb)</small></label>
                                            <input type="file" class="form-control-file" name="file" id="file">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-secondary waves-effect waves-light mt-3" type="submit">Aceptar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- start modal -->
        <div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="invoiceModalLabel">Datos para la factura</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="15%" class="bg-light">Cliente:</th>
                                <td colspan="3">CasaPlan MotorPlan S.A.</td>
                            </tr>
                            <tr>
                                <th width="15%" class="bg-light">Dirección:</th>
                                <td colspan="3">Sauces 8, Mz. 454 - F26, Solar 1 Av. Francisco de Orellana y Rodolfo Baquerizo Nazur.</td>
                            </tr>
                            <tr>
                                <th width="15%" class="bg-light">RUC:</th>
                                <td width="35%">0992151854001</td>
                                <th width="15%" class="bg-light">Telf:</th>
                                <td width="35%">(04) 2 233939</td>
                            </tr>
                        </table>
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr class="bg-light">
                                    <th class="text-center">Cant.</th>
                                    <th>Descripción</th>
                                    <th class="text-right">V. Unit.</th>
                                    <th class="text-right">V. Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>Pago de comisiones Proyecto Drones</td>
                                    <td class="text-right">${{ number_format($subtotal, 2) }}</td>
                                    <td class="text-right">${{ number_format($subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <th class="text-right bg-light">Subtotal</th>
                                    <td class="text-right">${{ number_format($subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <th class="text-right bg-light">IVA</th>
                                    <td class="text-right">${{ number_format($subtotal * $iva->value, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <th class="text-right bg-light">TOTAL</th>
                                    <td class="text-right">${{ number_format($subtotal + ($subtotal * $iva->value), 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end modal -->

        <div class="modal fade" id="cierreModal" tabindex="-1" role="dialog" aria-labelledby="cierreModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cierreModalLabel">Liquidación #</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        hola ss
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-6">
                <div class="alert alert-warning" role="alert">
                    <button type="button" class="close pl-2" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <i class="mdi mdi-alert-outline pr-2"></i> 
                    {{ $error }}
                </div>
            </div>
        </div>
    @endif
@endsection

@push('js')
    <script type="text/javascript">
    // inputmask init
    $(document).ready(function(){$(".input-mask").inputmask()});



    $('#cierreModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var url = button.data('url');
        var modal = $(this);
        modal.find('.modal-title').html('Liquidación #' + id);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('content')
            }
        });
        var request = $.ajax({
            method: 'get',
            xhrFields: {
                withCredentials: true
            },
            // dataType: 'json',
            url: url
        });
        request.done(function(data) {
            modal.find('.modal-body').html(data);
        });
        request.fail(function(xhr, status, error) {
            console.log('Request failed: ' + error);
        });
    })
    </script>
@endpush