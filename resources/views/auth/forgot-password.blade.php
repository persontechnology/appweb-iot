@extends('layouts.guest')

@section('content')
    

    

    <!-- Session Status -->
    

    <form method="POST" class="login-form" action="{{ route('password.email') }}">
        @csrf
      
        <div class="card mb-0">
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                        <img src="{{ asset('assets/images/logo_icon.svg') }}" class="h-48px" alt="">
                    </div>
                    <h5 class="mb-0">
                        ¿Olvidaste tu contraseña?
                    </h5>
                    <span class="d-block text-muted">
                         Ningún problema. Simplemente háganos saber su dirección de correo electrónico y le enviaremos un enlace para restablecer su contraseña que le permitirá elegir una nueva.
                    </span>
                    <x-auth-session-status class="mb-4 text-success" :status="session('status')" />
                </div>

                
    
                <div class="mb-3">
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-envelope-simple"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autofocus placeholder="" required>
                        <label>Email</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
           
    
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary w-100">Enviar enlace para restablecer contraseña de correo electrónico</button>
                </div>

                <div class="mb-3">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">Iniciar sesión</a>
                </div>
    
            </div>
        </div>

    </form>

@endsection