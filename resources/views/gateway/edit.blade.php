@extends('layouts.app')

@section('breadcrumbs')
{{ Breadcrumbs::render('categoria-gateway.edit',$cg) }}
@endsection

@section('content')
    
    <form action="{{ route('categoria-gateway.update',$cg) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">Complete datos</div>
            <div class="card-body">
    
                <div class="mb-3">
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-file-text"></i>
                        </div>
                        <input type="text" name="nombre" value="{{ old('nombre',$cg->nombre) }}" class="form-control @error('nombre') is-invalid @enderror" autofocus placeholder="" required>
                        <label>Nombre</label>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
    
                <div class="mb-3">
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-chat-text"></i>
                        </div>
                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" placeholder="" required>{{ old('descripcion',$cg->descripcion) }}</textarea>
                        <label>Descripción</label>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
    
    
            </div>
            <div class="card-footer text-muted">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('categoria-gateway.index') }}" class="btn btn-danger">Cancelar</a>
            </div>
        </div>
    </form>
        
@endsection
