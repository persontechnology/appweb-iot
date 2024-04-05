@extends('layouts.guest')

@section('content')
    
    <!-- Session Status -->
    

    <form class="login-form" method="POST" action="{{ route('login') }}">
            @csrf
        <div class="card mb-0">
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                        <img src="{{ asset('assets/images/logo_icon.svg') }}" class="h-48px" alt="">
                    </div>
                    <h5 class="mb-0">Ingrese a su cuenta</h5>
                    <span class="d-block text-muted">Ingrese sus credenciales a continuación</span>
                    <x-auth-session-status class="mb-4 text-success" :status="session('status')" />
                </div>
    
                <div class="mb-3">
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-envelope-simple"></i>
                        </div>
                        <input type="text" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autofocus placeholder="" required>
                        <label>Email</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-password"></i>
                        </div>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="" required>
                        <label>Contraseña</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
    
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
                </div>
    
                <div class="text-center">
                    <a href="{{ route('password.request') }}">¿Has olvidado tu contraseña?</a>
                </div>
            </div>
        </div>
    </form>

@endsection
