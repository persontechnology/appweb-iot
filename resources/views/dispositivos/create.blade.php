@extends('layouts.app')
@section('breadcrumbs')
{{-- {{ Breadcrumbs::render('categoria-gateway.create') }} --}}
@endsection

@section('content')

<form action="{{ route('dispositivos.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
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
                            <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control @error('nombre') is-invalid @enderror" autofocus placeholder="" required>
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
                            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" placeholder="" required>{{ old('descripcion') }}</textarea>
                            <label>Descripción</label>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

               

                <div class="col-lg-6">
                    <div class="mb-3">
                        @if ($aplicaciones->count()>0)
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-presentation-chart"></i>
                            </div>
                            
                            <select class="form-select @error('application_id') is-invalid @enderror" name="application_id" required>
                                @foreach ($aplicaciones as $app)
                                <option value="{{ $app->id }}" {{ old('application_id')==$app->id?'selected':'' }}>{{ $app->name }}</option>
                                @endforeach
                            </select>

                            <label>Aplicaciones</label>

                            @error('application_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @else
                            @include('layouts.alert',['type'=>'danger','msg'=>'No existe Aplicaciones, por favor crear una.'])
                        @endif
                        
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-3">
                        @if ($perfil_dispositivos->count()>0)
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-device-tablet"></i>
                            </div>
                            
                            <select class="form-select @error('device_profile_id') is-invalid @enderror" name="device_profile_id" required>
                                @foreach ($perfil_dispositivos as $pd)
                                <option value="{{ $pd->id }}" {{ old('device_profile_id')==$pd->id?'selected':'' }}>{{ $pd->name }}</option>
                                @endforeach
                            </select>

                            <label>Perfil de dispositivo</label>

                            @error('device_profile_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @else
                            @include('layouts.alert',['type'=>'danger','msg'=>'No existe perfil de sispositivos, por favor crear una.'])
                        @endif
                        
                    </div>
                </div>


                <div class="col-lg-6">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-keyboard"></i>
                            </div>
                            <input type="text" name="dev_eui" value="{{ old('dev_eui') }}" class="form-control @error('dev_eui') is-invalid @enderror" placeholder="" required>
                            <label>Dispositivo EUI (EUI64)</label>
                            @error('dev_eui')
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
                            <input type="text" name="join_eui" value="{{ old('join_eui') }}" class="form-control @error('join_eui') is-invalid @enderror" placeholder="" required>
                            <label>Join EUI (EUI64)</label>
                            @error('join_eui')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="col-lg-12">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-timer"></i>
                            </div>
                            <input type="text" name="nwk_key" value="{{ old('nwk_key') }}" class="form-control @error('nwk_key') is-invalid @enderror" placeholder="" required>
                            <label>Clave de aplicación</label>
                            <div class="form-text">
                                Para dispositivos LoRaWAN 1.0. En caso de que su dispositivo sea compatible con LoRaWAN 1.1, actualice primero el perfil del dispositivo.
                            </div>
                            @error('nwk_key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="border p-3 rounded">
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" class="form-check-input" name="is_disabled" id="sc_ls_c">
                        <label class="form-check-label" for="sc_ls_c" >El dispositivo está deshabilitado</label>
                        <div class="form-text">Se ignorarán las tramas de enlace ascendente recibidas y las solicitudes de unión.</div>
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

