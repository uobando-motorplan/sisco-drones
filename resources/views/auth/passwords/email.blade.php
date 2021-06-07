@extends('layouts.auth')

@section('title', 'Restablecer contraseña')
@section('subtitle', 'Ingresa tu correo electrónico y te enviaremos las instrucciones para restablecer tu contraseña.')

@section('content')
    <div class="p-2 mt-4">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <form method="POST" action="{{ route('password.email') }}" class="form-horizontal" >
            @csrf
            <div class="form-group auth-form-group-custom mb-4">
                <i class="ri-user-2-line auti-custom-input-icon"></i>
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Ingresa tu correo electrónico" autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
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
