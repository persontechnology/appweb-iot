@extends('layouts.app')
@section('breadcrumbs')
{{-- {{ Breadcrumbs::render('categoria-gateway.create') }} --}}
@endsection

@section('content')

<form action="{{ route('usuarios.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header">Complete datos</div>
        <div class="card-body">
            <div class="row">

                <div class="col-lg-12">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-identification-card"></i>
                            </div>
                            <input type="text" name="identificacion" value="{{ old('identificacion') }}" class="form-control @error('identificacion') is-invalid @enderror" autofocus placeholder="" required>
                            <label>Identificación</label>
                            @error('identificacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="col-lg-6">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-identification-card"></i>
                            </div>
                            <input type="text" name="nombres" value="{{ old('nombres') }}" class="form-control @error('nombres') is-invalid @enderror"  placeholder="" required>
                            <label>Nombres</label>
                            @error('nombres')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-identification-card"></i>
                            </div>
                            <input type="text" name="apellidos" value="{{ old('apellidos') }}" class="form-control @error('apellidos') is-invalid @enderror" placeholder="" required>
                            <label>Apellidos</label>
                            @error('apellidos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-envelope-simple"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="" required>
                            <label>email</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-password"></i>
                            </div>
                            <input type="password" name="contrasena" value="{{ old('contrasena') }}" class="form-control @error('contrasena') is-invalid @enderror" placeholder="" required>
                            <label>Contraseña</label>
                            @error('contrasena')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="col-lg-6">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-chat-text"></i>
                            </div>
                            <textarea name="descripcion"  class="form-control @error('descripcion') is-invalid @enderror" placeholder="" required>{{ old('descripcion') }}</textarea>
                            <label>Descripción</label>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="border p-3 rounded">
                        <div class="d-inline-flex align-items-center me-3">
                            <input type="checkbox" name="es_administrador" id="dc_li_c" {{ old('es_administrador') ? 'checked' : '' }}>
                            <label class="ms-2" for="dc_li_c">Es administrador</label>
                        </div>
                
                        <div class="d-inline-flex align-items-center">
                            <input type="checkbox" name="esta_activo" id="dc_li_u" {{ old('esta_activo') ? 'checked' : '' }}>
                            <label class="ms-2" for="dc_li_u">Está activo</label>
                        </div>
                    </div>
                </div>


            

            </div>
        </div>
        <div class="card-footer text-muted">
            <button class="btn btn-primary" type="submit">Guardar</button>
            <a href="{{ route('gateways.index') }}" class="btn btn-danger">Cancelar</a>
            
        </div>
    </div>
    
</form>
        
@endsection

