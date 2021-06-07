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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Mantenimientos</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
                        <li class="breadcrumb-item active">Cambiar contraseña</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page-title -->
    <div class="row">
        <div class="col-xl-4 col-lg-6 col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('users.edit_password', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Usuario</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{ $user->getFullName() }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña *</label>
                            <input type="password" name="password" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" value="{{ old('password') }}" maxlength="30" placeholder="Ingrese una contraseña segura" data-validation="required strength" data-validation-strength="2" data-sanitize="trim">
                            {!! $errors->first('password', '<span class="form-text form-error">:message</span>') !!}
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirmar contraseña *</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" value="{{ old('password_confirmation') }}" maxlength="30" placeholder="Confirme la contraseña" data-validation="required confirmation" data-validation-confirm="password">
                            {!! $errors->first('password_confirmation', '<span class="form-text form-error">:message</span>') !!}
                        </div>
                        <div class="mt-1">
	                        <button class="btn btn-secondary waves-effect waves-light mr-1" type="submit">Aceptar</button>
	                        <a href="{{ route('users.index') }}" class="btn btn-light waves-effect waves-light">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

