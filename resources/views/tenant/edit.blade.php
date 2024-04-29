@extends('layouts.app')
@section('breadcrumbs')
{{-- {{ Breadcrumbs::render('categoria-gateway.create') }} --}}
@endsection

@section('content')

<form action="{{ route('inquilinos.update',$tenant->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-header">Complete datos</div>
        <div class="card-body">
            
            <div class="row">

                <div class="col-lg-6">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-file-text"></i>
                            </div>
                            <input type="text" name="nombre" value="{{ old('nombre',$tenant->name) }}" class="form-control @error('nombre') is-invalid @enderror" autofocus placeholder="" required>
                            <label>Nombre</label>
                            @error('nombre')
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
                            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" placeholder="" required>{{ old('descripcion',$tenant->description) }}</textarea>
                            <label>Descripción</label>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-keyboard"></i>
                            </div>
                            
                            <input type="number" name="max_gateway_permitidos" value="{{ old('max_gateway_permitidos',$tenant->max_gateway_count) }}" class="form-control @error('max_gateway_permitidos') is-invalid @enderror" placeholder="" required>
                            <label>Máximo número de gateways permitidos</label>
                            <div class="form-text">El número máximo de gateway que esta entidad puede agregar (0 = ilimitado).</div>
                            @error('max_gateway_permitidos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-timer"></i>
                            </div>
                            <input type="number" name="maximo_dispositivos_permitidos" value="{{ old('maximo_dispositivos_permitidos',$tenant->max_device_count) }}" class="form-control @error('maximo_dispositivos_permitidos') is-invalid @enderror" placeholder="" required>
                            <label>Máximo número de dispositivos permitidos</label>
                            <div class="form-text">La cantidad máxima de dispositivos que esta entidad puede agregar (0 = ilimitado).</div>
                            @error('maximo_dispositivos_permitidos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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

