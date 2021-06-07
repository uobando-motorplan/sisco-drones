@extends('layouts.app')

@section('title', 'Perfil')

@section('content')
    <!-- start page-title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">@yield('title')</h4>

                <div class="page-title-right d-none d-sm-block">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-xl-10">
            @if (session()->has('success'))
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <i class="mdi mdi-check pr-2"></i> {{ session('success') }}
                </div>
            @endif
            @if (session()->has('warning'))
                <div class="alert alert-warning" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <i class="mdi mdi-alert-outline pr-2"></i> {{ session('warning') }}
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 mb-2">
            <h5>Foto de perfil</h5>
            Actualiza tu foto de perfil.
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body profile">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('users.update_photo') }}">
                        @csrf
                        @method('PUT')
                        <div class="d-flex justify-content-start">
                            <div class="pr-4">
                                @if (auth()->user()->photo == 'hombre.png' OR auth()->user()->photo == 'mujer.png')
                                    <img class="avatar-xl img-thumbnail" src="{{ asset('assets/images/'.auth()->user()->photo) }}" alt="avatar"> 
                                @else
                                    <img class="avatar-xl img-thumbnail" src="/storage/users/{{ auth()->user()->photo }}" alt="avatar">
                                @endif
                            </div>
                            <div class="">
                                <div class="form-group">
                                    <label for="image">Foto *</label> <small class="text-muted">(Tipo: jpg, png - 512 x 512 pixeles - Tamaño: 1Mb)</small>
                                    <input type="file" name="image" id="image" class="form-control-file" data-validation="required mime size" data-validation-allowing="jpg, png" data-validation-max-size="1M">
                                </div>
                                <button class="btn btn-sm btn-secondary px-3 waves-effect waves-light" type="submit">Actualizar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4 mb-2">
            <h5>Contraseña</h5>
            Asegúrete de que tu cuenta esté usando una contraseña larga y aleatoria para mantenerse seguro.
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('users.update_password') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="current_password">Contraseña actual *</label>
                            <input type="password" name="current_password" id="current_password" class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }}" value="{{ old('current_password') }}" maxlength="30" placeholder="" data-validation="required" data-sanitize="trim">
                            {!! $errors->first('current_password', '<span class="form-text form-error">:message</span>') !!}
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="password">Nueva contraseña *</label>
                                    <input type="password" name="password" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" value="{{ old('password') }}" maxlength="30" placeholder="" data-validation="required strength" data-validation-strength="2" data-sanitize="trim">
                                    {!! $errors->first('password', '<span class="form-text form-error">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmar contraseña *</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" value="{{ old('password_confirmation') }}" maxlength="30" placeholder="" data-validation="required confirmation" data-validation-confirm="password">
                                    {!! $errors->first('password_confirmation', '<span class="form-text form-error">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-secondary px-3 waves-effect waves-light" type="submit">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4 mb-2">
            <h5>Correo electrónico</h5>
            Actualiza tu dirección de correo electrónico.
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('users.update_email') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="email">Correo electrónico *</label>
                            <input type="text" name="email" id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ auth()->user()->drone->email }}" maxlength="150" placeholder="Máximo 150 caracteres" data-validation="required email" data-sanitize="trim">
                            {!! $errors->first('email', '<span class="form-text form-error">:message</span>') !!}
                        </div>
                        <button class="btn btn-sm btn-secondary px-3 waves-effect waves-light" type="submit">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4 mb-2">
            <h5>Información de contacto</h5>
            Actualiza tus números telefónicos y redes sociales.
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body profile">
                    <form method="POST" action="{{ route('users.update_contact_information') }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="mobile_phone">Teléfono móvil *</label>
                                    <input type="text" name="mobile_phone" id="mobile_phone" class="form-control cell_phone{{ $errors->has('mobile_phone') ? ' is-invalid' : '' }}" value="{{ auth()->user()->drone->mobile_phone }}" maxlength="10" placeholder="Máximo 10 dígitos" data-validation="required" data-sanitize="trim">
                                    {!! $errors->first('mobile_phone', '<span class="form-text form-error">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="landline_phone">Teléfono fijo</label>
                                    <input type="text" name="landline_phone" id="landline_phone" class="form-control landline_phone{{ $errors->has('landline_phone') ? ' is-invalid' : '' }}" value="{{ auth()->user()->drone->landline_phone }}" maxlength="9" placeholder="Máximo 9 dígitos" data-validation="" data-sanitize="trim">
                                    {!! $errors->first('landline_phone', '<span class="form-text form-error">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-secondary px-3 waves-effect waves-light" type="submit">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4 mb-2">
            <h5>Cuenta bancaria</h5>
            Actualice los datos de su cuenta bancaria donde recibirá el pago de sus comisiones.
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('users.update_bank') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <div class="form-group">
                                <label for="bank_id">Banco *</label>
                                <select name="bank_id" id="bank_id" class="form-control{{ $errors->has('bank_id') ? ' is-invalid' : '' }}" data-validation="required">
                                    <option value="">- Seleccione un item -</option>
                                    @foreach ($banks as $id => $name)
                                        <option value="{{ $id }}" {{ auth()->user()->drone->bank_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->first('bank_id', '<span class="form-text form-error">:message</span>') !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="bank_account_type">Tipo de cuenta *</label>
                                    <select name="bank_account_type" id="bank_account_type" class="form-control{{ $errors->has('bank_account_type') ? ' is-invalid' : '' }}" data-validation="required">
                                        <option value="">- Seleccione un item -</option>
                                        <option value="A" {{ auth()->user()->drone->bank_account_type == App\Bank::AHORROS ? 'selected' : '' }}>Ahorros</option>
                                        <option value="C" {{ auth()->user()->drone->bank_account_type == App\Bank::CORRIENTE ? 'selected' : '' }}>Corriente</option>
                                    </select>
                                    {!! $errors->first('bank_account_type', '<span class="form-text form-error">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="bank_account_number">Número de cuenta *</label>
                                    <input type="text" name="bank_account_number" id="bank_account_number" class="form-control{{ $errors->has('bank_account_number') ? ' is-invalid' : '' }}" value="{{ auth()->user()->drone->bank_account_number }}" maxlength="20" placeholder="Máximo 20 caracteres" data-validation="required" data-sanitize="trim">
                                    {!! $errors->first('bank_account_number', '<span class="form-text form-error">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-secondary px-3 waves-effect waves-light" type="submit">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- bs custom file input plugin -->
    <script src="{{ asset('assets/libs/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script type="text/javascript">
        bsCustomFileInput.init();
        $('.landline_phone').inputmask("(02|03|04|05|06|07) 9999999");
        $('.cell_phone').inputmask("0\\999999999");
    </script>
@endpush
