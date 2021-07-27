@extends('layouts.app')

@section('title', 'Cambiar contraseña')

@section('content')
    <!-- start page-title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">@yield('title')</h4>

                <div class="page-title-right d-none d-sm-block">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Cambiar contraseña</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page-title -->
    <div class="row">
        <div class="col-xl-4 col-lg-6 col-md-8">
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
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('users.edit_my_password') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="current_password">Contraseña actual *</label>
                            <input type="password" name="current_password" id="current_password" class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }}" value="{{ old('current_password') }}" maxlength="30" placeholder="Ingrese una contraseña anterior" data-validation="required" data-sanitize="trim">
                            {!! $errors->first('current_password', '<span class="form-text form-error">:message</span>') !!}
                        </div>
                        <div class="form-group">
                            <label for="password">Nueva contraseña *</label>
                            <input type="password" name="password" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" value="{{ old('password') }}" maxlength="30" placeholder="Ingrese su nueva contraseña" data-validation="required strength" data-validation-strength="2" data-sanitize="trim">
                            {!! $errors->first('password', '<span class="form-text form-error">:message</span>') !!}
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirmar nueva contraseña *</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" value="{{ old('password_confirmation') }}" maxlength="30" placeholder="Confirme su nueva contraseña" data-validation="required confirmation" data-validation-confirm="password">
                            {!! $errors->first('password_confirmation', '<span class="form-text form-error">:message</span>') !!}
                        </div>
                        <div class="mt-1">
                            <button class="btn btn-secondary waves-effect waves-light mr-1" type="submit">Aceptar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

