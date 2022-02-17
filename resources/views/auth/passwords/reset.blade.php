@extends('layouts.auth')

@section('title', 'Restablecer contraseña')
@section('subtitle', 'Ingresa tu correo electrónico y tu nueva contraseña para continuar.')

@section('content')
    <div class="p-2 mt-4">
        <form method="POST" action="{{ route('password.update') }}" class="form-horizontal" >
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group auth-form-group-custom mb-4">
                <i class="ri-user-2-line auti-custom-input-icon"></i>
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Ingresa tu correo electrónico" autocomplete="email" data-validation="required email" data-sanitize="trim lower" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group auth-form-group-custom mb-4">
                <i class="ri-lock-2-line auti-custom-input-icon"></i>
                <label for="password">Nueva contraseña</label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Ingresa tu nueva contraseña" data-validation="required strength" data-validation-strength="2" data-sanitize="trim">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group auth-form-group-custom mb-4">
                <i class="ri-lock-2-line auti-custom-input-icon"></i>
                <label for="password-confirm">Confirmar contraseña</label>
                <input type="password" id="password-confirm" name="password_confirmation" class="form-control" placeholder="Ingresa tu nueva contraseña" data-validation="required confirmation" data-validation-confirm="password">
            </div>
            <div class="mt-4 text-center">
                <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Continuar</button>
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('login') }}" class="text-muted">Iniciar sesión</a>
            </div>
        </form>
    </div>
@endsection
