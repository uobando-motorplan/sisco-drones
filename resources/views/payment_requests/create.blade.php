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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p>Selecciona uno o más referidos para crear tu solicitud de pago.</p>

                    @if ($errors->any())
                        <div class="alert alert-warning" role="alert">
                            <button type="button" class="close pl-2" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="mdi mdi-alert-outline pr-2"></i> 
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                    @endif
                    <form method="POST" action="{{ route('payment_requests.store') }}">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr class="bg-secondary text-white">
                                        <th class="text-center">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="selectAll" name="">
                                                <label class="custom-control-label" for="selectAll"></label>
                                            </div>
                                        </th>
                                        <th>Referido</th>
                                        <th>Fecha facturación</th>
                                        <th>Producto</th>
                                        <th class="text-right">Plan</th>
                                        <th class="text-right">Comisión</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($quotations as $quotation)
                                        <tr>
                                            <td class="text-center">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input commision" id="customCheck{{ $i }}" name="quotation_id[]" value="{{ $quotation->id }}" data-commission="{{ $quotation->commision_value }}">
                                                    <label class="custom-control-label" for="customCheck{{ $i }}"></label>
                                                </div>
                                            </td>
                                            <td>{{ $quotation->surnames }} {{ $quotation->names }}</td>
                                            <td>{{ Carbon\Carbon::parse($quotation->invoice_date)->format('Y-m-d') }}</td>
                                            <td>{{ $quotation->product_name }}</td>
                                            <td class="text-right">${{ number_format($quotation->amount) }}</td>
                                            <td class="text-right">${{ number_format($quotation->commision_value, 2) }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                    <tr class="">
                                        <th colspan="5" class="text-right">SUBTOTAL</th>
                                        <th class="text-right bg-">$<span id="subtotal">0</span></th>
                                    </tr>
                                    <tr class="">
                                        <th colspan="5" class="text-right">IVA</th>
                                        <th class="text-right bg-">$<span id="iva">0</span></th>
                                    </tr>
                                    <tr class="">
                                        <th colspan="5" class="text-right">TOTAL</th>
                                        <th class="text-right bg-light">$<span id="total">0</span></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="border rounded pt-3 px-3 pb-2 my-3 bg-light">
                            <div class="row">
                                <div class="col-xl-3 col-md-6">
                                    <div class="form-group">
                                        <label for="method_of_payment">Forma de pago *</label>
                                        <select name="method_of_payment" id="method_of_payment" class="form-control{{ $errors->has('method_of_payment') ? ' is-invalid' : '' }}" data-validation="">
                                            <option value="">- Seleccione un item -</option>
                                            <option value="1" selected="">Transferencia directa</option>
                                            <option value="2">Pago a tercero</option>
                                        </select>
                                        {!! $errors->first('method_of_payment', '<span class="form-text form-error">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 d-none">
                                    <div class="form-group">
                                        <label for="bank">Banco *</label>
                                        <input type="text" name="bank" id="bank" class="form-control input-mask{{ $errors->has('active') ? ' is-invalid' : '' }}" maxlength="50" placeholder="Máximo 50 caracteres" value="" data-validation="" data-sanitize="trim">
                                        {!! $errors->first('bank', '<span class="form-text form-error">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 d-none">
                                    <div class="form-group">
                                        <label for="account_type">Tipo de cuenta *</label>
                                        <select name="account_type" id="account_type" class="form-control{{ $errors->has('account_type') ? ' is-invalid' : '' }}" data-validation="">
                                            <option value="">- Seleccione un item -</option>
                                            <option value="">Corriente</option>
                                            <option value="">Ahorro</option>
                                        </select>
                                        {!! $errors->first('account_type', '<span class="form-text form-error">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 d-none">
                                    <div class="form-group">
                                        <label for="account_number">N° de cuenta *</label>
                                        <input type="text" name="account_number" id="account_number" class="form-control input-mask{{ $errors->has('active') ? ' is-invalid' : '' }}" maxlength="15" placeholder="Máximo 15 caracteres" value="" data-validation="" data-sanitize="trim">
                                        {!! $errors->first('account_number', '<span class="form-text form-error">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-xl-5 col-md-6 d-none">
                                    <div class="form-group">
                                        <label for="name">Nombre del beneficiario *</label>
                                        <input type="text" name="name" id="name" class="form-control input-mask{{ $errors->has('active') ? ' is-invalid' : '' }}" maxlength="50" placeholder="Máximo 50 caracteres" value="" data-validation="" data-sanitize="trim">
                                        {!! $errors->first('name', '<span class="form-text form-error">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6 d-none">
                                    <div class="form-group">
                                        <label for="identification_number">Cédula/Ruc *</label>
                                        <input type="text" name="identification_number" id="identification_number" class="form-control input-mask{{ $errors->has('active') ? ' is-invalid' : '' }}" maxlength="11" placeholder="Máximo 11 caracteres" value="" data-validation="" data-sanitize="trim">
                                        {!! $errors->first('identification_number', '<span class="form-text form-error">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 d-none">
                                    <div class="form-group">
                                        <label for="email">Correo electrónico</label>
                                        <input type="text" name="email" id="email" class="form-control input-mask{{ $errors->has('active') ? ' is-invalid' : '' }}" maxlength="50" placeholder="Máximo 50 caracteres" value="" data-validation="" data-sanitize="trim">
                                        {!! $errors->first('email', '<span class="form-text form-error">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border rounded p-3 my-3">
                            <div class="row">
                                <div class="col-12">
                                    <p>Utiliza los siguientes datos descritos para elaborar correctamente tu factura y luego tómale una foto a la factura o escanéala para que posteriormente la subas.</p>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#exampleModal">
                                Ver datos para la factura
                            </button>
                        </div>

                        <div class="border rounded pt-3 px-3 pb-2 bg-light">
                            <div class="row">
                                <div class="col-xl-3 col-md-6">
                                    <div class="form-group">
                                        <label for="invoice_number">Número de factura *</label>
                                        <input type="text" name="invoice_number" id="invoice_number" class="form-control input-mask{{ $errors->has('active') ? ' is-invalid' : '' }}" maxlength="9" placeholder="Máximo 9 dígitos" value="" data-validation="required" data-sanitize="trim" data-inputmask="'mask': '999999999'">
                                        {!! $errors->first('invoice_number', '<span class="form-text form-error">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="form-group">
                                        <label for="value">Valor *</label>
                                        <input type="text" name="value" id="value" class="form-control input-mask{{ $errors->has('active') ? ' is-invalid' : '' }}" maxlength="9" placeholder="Total en dólares" value="" data-validation="required" data-sanitize="trim">
                                        {!! $errors->first('value', '<span class="form-text form-error">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="form-group">
                                        <label for="expiry_date">Fecha de emisión *</label>
                                        <input type="date" name="expiry_date" id="expiry_date" class="form-control{{ $errors->has('expiry_date') ? ' is-invalid' : '' }}" value="" placeholder="dd/mm/yyyy" data-validation="required">
                                        {!! $errors->first('expiry_date', '<span class="form-text form-error">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="form-group">
                                        <label for="names">Fecha de caducidad *</label>
                                        <input type="date" name="issue_date" id="issue_date" class="form-control{{ $errors->has('issue_date') ? ' is-invalid' : '' }}" value="" placeholder="dd/mm/yyyy" data-validation="required">
                                        {!! $errors->first('issue_date', '<span class="form-text form-error">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlFile1">Subir factura * <small>(Formato: JPG o PDF - Máximo: 4Mb)</small></label>
                                        <input type="file" class="form-control-file" id="exampleFormControlFile1">
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

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Datos para la factura</h5>
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
                                <td>Pago de comisiones proyecto drones</td>
                                <td class="text-right">$<span class="subtotal"></span></td>
                                <td class="text-right">$<span class="subtotal"></span></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td></td>
                                <td></td>
                                <td></td>
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
                                <td class="text-right">$<span class="subtotal"></span></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <th class="text-right bg-light">IVA</th>
                                <td class="text-right">$<span class="iva"></span></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <th class="text-right bg-light">TOTAL</th>
                                <td class="text-right">$<span class="total"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
    // inputmask init
    $(document).ready(function(){$(".input-mask").inputmask()});

    $('#selectAll').click(function(e) {
        var table = $(e.target).closest('table');
        $('td input:checkbox', table).prop('checked', this.checked);
    });
    $('.custom-control-input').change(function() {
        var subtotal = 0;
        $('.table input:checked').each(function() {
            if ($(this).attr('data-commission')) {
                subtotal = subtotal + Number($(this).attr('data-commission'));
            }
        });
        $('#subtotal').html(parseFloat(subtotal).toFixed(2));
        $('#iva').html(parseFloat(subtotal*0.12).toFixed(2));
        $('#total').html(parseFloat(subtotal+subtotal*0.12).toFixed(2));


        $('.subtotal').html(parseFloat(subtotal).toFixed(2));
        $('.iva').html(parseFloat(subtotal*0.12).toFixed(2));
        $('.total').html(parseFloat(subtotal+subtotal*0.12).toFixed(2));
    });
    $('#method_of_payment').change(function() {
        if ($(this).val()==2) {
            $('#bank').parent().parent().removeClass('d-none');
            $('#account_number').parent().parent().removeClass('d-none');
            $('#account_type').parent().parent().removeClass('d-none');
            $('#name').parent().parent().removeClass('d-none');
            $('#identification_number').parent().parent().removeClass('d-none');
            $('#email').parent().parent().removeClass('d-none');
        } else {
            $('#bank').parent().parent().addClass('d-none');
            $('#account_number').parent().parent().addClass('d-none');
            $('#account_type').parent().parent().addClass('d-none');
            $('#name').parent().parent().addClass('d-none');
            $('#identification_number').parent().parent().addClass('d-none');
            $('#email').parent().parent().addClass('d-none');
        }
    });
    </script>
@endpush

@push('css')
    <style type="text/css">
        .custom-control {
            padding-left: 2rem;
        }
    </style>
@endpush
