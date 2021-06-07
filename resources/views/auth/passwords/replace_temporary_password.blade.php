@extends('layouts.auth')

@section('title', 'Cambia tu clave temporal')
@section('subtitle', 'Ingresa una contraseña segura que incluya letras, números y caracteres especiales.')

@section('content')
    <div class="p-2 mt-4">
        <form method="POST" action="{{ route('users.update_temporary_password') }}" class="form-horizontal" >
            @csrf
            @method('PUT')
            <div class="form-group auth-form-group-custom mb-4">
                <i class="ri-lock-2-line auti-custom-input-icon"></i>
                <label for="password">Nueva contraseña</label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Ingresa tu nueva contraseña">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group auth-form-group-custom mb-4">
                <i class="ri-lock-2-line auti-custom-input-icon"></i>
                <label for="password-confirm">Confirmar contraseña</label>
                <input type="password" id="password-confirm" name="password_confirmation" class="form-control" placeholder="Ingresa tu nueva contraseña">
            </div>
            <div class="mt-4 text-center">
                <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Continuar</button>
            </div>
            <div class="mt-4 text-center">
                <a href="{{ url('/') }}" class="text-muted">Iniciar sesión</a>
            </div>
        </form>
    </div>
@endsection
