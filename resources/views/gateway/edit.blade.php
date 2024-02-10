@extends('layouts.app')
@section('breadcrumbs')
{{ Breadcrumbs::render('categoria-gateway.create') }}
@endsection

@section('content')

<form action="{{ route('gateway.update',$gateway) }}" method="POST" enctype="multipart/form-data">
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
                            <input type="text" name="nombre" value="{{ old('nombre',$gateway->nombre) }}" class="form-control @error('nombre') is-invalid @enderror" autofocus placeholder="" required>
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
                                <i class="ph ph-keyboard"></i>
                            </div>
                            <input type="text" name="modelo" value="{{ old('modelo',$gateway->modelo) }}" class="form-control @error('modelo') is-invalid @enderror" placeholder="" required>
                            <label>Modelo</label>
                            @error('modelo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-calculator"></i>
                            </div>
                            <input type="text" name="fcc_id" value="{{ old('fcc_id',$gateway->fcc_id) }}" class="form-control @error('fcc_id') is-invalid @enderror" placeholder="" required>
                            <label>Fcc id</label>
                            @error('fcc_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-rss-simple"></i>
                            </div>
                            <input type="text" name="direccion_ip" value="{{ old('direccion_ip',$gateway->direccion_ip) }}" class="form-control @error('direccion_ip') is-invalid @enderror" placeholder="" required>
                            <label>Dirección ip</label>
                            @error('direccion_ip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-user"></i>
                            </div>
                            <input type="text" name="usuario" value="{{ old('usuario',$gateway->usuario) }}" class="form-control @error('usuario') is-invalid @enderror" placeholder="" required>
                            <label>Usuario</label>
                            @error('usuario')
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
                            <input type="text" value="{{ old('contrasena',$gateway->password) }}" name="contrasena" class="form-control @error('contrasena') is-invalid @enderror" placeholder="" required>
                            <label>Contraseña</label>
                            @error('contrasena')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-barcode"></i>
                            </div>
                            <input type="text" name="imei" value="{{ old('imei',$gateway->imei) }}" class="form-control @error('imei') is-invalid @enderror" placeholder="" required>
                            <label>Imei</label>
                            @error('imei')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-scan"></i>
                            </div>
                            <input type="text" name="mac" value="{{ old('mac',$gateway->mac) }}" class="form-control @error('mac') is-invalid @enderror" placeholder="" required>
                            <label>Mac</label>
                            @error('mac')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mb-3">
                        @if ($categoriaGateway->count()>0)
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-presentation-chart"></i>
                            </div>
                            
                            <select class="form-select @error('categoria_gateway') is-invalid @enderror" name="categoria_gateway" required>
                                @foreach ($categoriaGateway as $cg)
                                <option value="{{ $cg->id }}" {{ old('categoria_gateway',$gateway->categoriaGateway->id)==$cg->id?'selected':'' }}>{{ $cg->nombre }}</option>
                                @endforeach
                            </select>

                            <label>Categoría de gateway</label>

                            @error('categoria_gateway')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @else
                            @include('layouts.alert',['type'=>'danger','msg'=>'No existe Categorías de gateway, por favor crear una.'])
                        @endif
                        
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-toggle-left"></i>
                            </div>
                            <select class="form-select @error('estado') is-invalid @enderror" name="estado" required>
                                <option value="ACTIVO" {{ old('estado',$gateway->estado)=='ACTIVO'?'selected':'' }} >ACTIVO</option>
                                <option value="INACTIVO" {{ old('estado',$gateway->estado)=='INACTIVO'?'selected':'' }} >INACTIVO</option>
                            </select>
                            <label>Estado</label>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-3">
                        
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-toggle-left"></i>
                            </div>
                            <select class="form-select @error('conectado') is-invalid @enderror" name="conectado" required>
                                <option value="SI" {{ old('conectado',$gateway->conectado)=='SI'?'selected':'' }} >SI</option>
                                <option value="NO" {{ old('conectado',$gateway->conectado)=='NO'?'selected':'' }} >NO</option>
                            </select>
                            <label>Conectado</label>
                            @error('conectado')
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
                            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" placeholder="" required>{{ old('descripcion',$gateway->descripcion) }}</textarea>
                            <label>Descripción</label>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <input type="file" name="foto" class="form-control" accept="image/*">
					<div class="form-text">Acepta solo imágenes</div>
                </div>

            </div>
        </div>
        <div class="card-footer text-muted">
            <button class="btn btn-primary" type="submit">Guardar</button>
            <a href="{{ route('gateway.index') }}" class="btn btn-danger">Cancelar</a>
        </div>
    </div>
    
</form>
        
@endsection


