@extends('layouts.guest')

@section('content')
    
    <!-- Session Status -->
    

    <form method="POST" class="login-form" action="{{ route('password.store') }}">
            @csrf
        <div class="card mb-0">
            <div class="card-body">

                <div class="text-center mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                        <img src="{{ asset('assets/images/logo_icon.svg') }}" class="h-48px" alt="">
                    </div>
                    <h5 class="mb-0">Restablecer contraseña</h5>
                    <span class="d-block text-muted">Ingrese sus credenciales a continuación</span>
                    <x-auth-session-status class="mb-4 text-success" :status="session('status')" />
                </div>
    
                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="mb-3">
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-envelope-simple"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email', $request->email) }}" class="form-control @error('email') is-invalid @enderror" autofocus placeholder="" required>
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
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-password"></i>
                        </div>
                        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="" required>
                        <label>Confirmar contraseña</label>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

    
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary w-100">Restablecer contraseña</button>
                </div>
    
                <div class="text-center">
                    <a href="{{ route('password.request') }}">¿Has olvidado tu contraseña?</a>
                </div>
            </div>
        </div>
    </form>

@endsection
