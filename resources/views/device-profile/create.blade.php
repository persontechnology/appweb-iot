@extends('layouts.app')
@section('breadcrumbs')
{{-- {{ Breadcrumbs::render('categoria-gateway.create') }} --}}
@endsection

@section('content')

<form action="{{ route('perfil-dispositivos.store') }}" method="POST" enctype="multipart/form-data">
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

                <div class="col-lg-4">
                    <div class="mb-3">
                       
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-globe"></i>
                            </div>
                            
                            <select class="form-select @error('region') is-invalid @enderror" name="region" required>
                                <option value="AS923-2" {{ old('region')=='AS923-2'?'selected':'' }}>AS923-2</option>
                                <option value="AS923-3" {{ old('region')=='AS923-3'?'selected':'' }}>AS923-3</option>
                                <option value="AS923-4" {{ old('region')=='AS923-4'?'selected':'' }}>AS923-4</option>
                                <option value="AU915" {{ old('region')=='AU915'?'selected':'' }}>AU915</option>
                                <option value="CN470" {{ old('region')=='CN470'?'selected':'' }}>CN470</option>
                                <option value="CN779" {{ old('region')=='CN779'?'selected':'' }}>CN779</option>
                                <option value="CN470" {{ old('region')=='CN470'?'selected':'' }}>CN470</option>
                                <option value="EU433" {{ old('region')=='EU433'?'selected':'' }}>EU433</option>
                                <option value="EU868" {{ old('region')=='EU868'?'selected':'' }}>EU868</option>
                                <option value="IN865" {{ old('region')=='IN865'?'selected':'' }}>IN865</option>
                                <option value="ISM2400" {{ old('region')=='ISM2400'?'selected':'' }}>ISM2400</option>
                                <option value="KR920" {{ old('region')=='KR920'?'selected':'' }}>KR920</option>
                                <option value="RU864" {{ old('region')=='RU864'?'selected':'' }}>RU864</option>
                                <option value="US915" {{ old('region')=='US915'?'selected':'' }}>US915</option>

                            </select>

                            <label>Región</label>

                            @error('region')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>



                <div class="col-lg-4">
                    <div class="mb-3">
                       
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-laptop"></i>
                            </div>
                            
                            <select class="form-select @error('mac_version') is-invalid @enderror" name="mac_version" required>
                               
                                <option value="1.0.0" {{ old('mac_version')=='1.0.0'?'selected':'' }}>LoRaWAN 1.0.0</option>
                                <option value="1.0.1" {{ old('mac_version')=='1.0.1'?'selected':'' }}>LoRaWAN 1.0.1</option>
                                <option value="1.0.2" {{ old('mac_version')=='1.0.2'?'selected':'' }}>LoRaWAN 1.0.2</option>
                                <option value="1.0.3" {{ old('mac_version')=='1.0.3'?'selected':'' }}>LoRaWAN 1.0.3</option>
                                <option value="1.0.4" {{ old('mac_version')=='1.0.4'?'selected':'' }}>LoRaWAN 1.0.4</option>
                                <option value="1.1.0" {{ old('mac_version')=='1.1.0'?'selected':'' }}>LoRaWAN 1.1.0</option>

                            </select>

                            <label>Versión de Mac</label>
                            <div class="form-text">La versión MAC de LoRaWAN compatible con el dispositivo.</div>
                            @error('mac_version')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mb-3">
                       
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-computer-tower"></i>
                            </div>
                            
                            <select class="form-select @error('revision_parametros_regionales') is-invalid @enderror" name="revision_parametros_regionales" required>
                               
                                <option value="A" {{ old('revision_parametros_regionales')=='A'?'selected':'' }}>A</option>
                                <option value="B" {{ old('revision_parametros_regionales')=='B'?'selected':'' }}>B</option>
                                <option value="RP002-1.0.0" {{ old('revision_parametros_regionales')=='RP002-1.0.0'?'selected':'' }}>RP002-1.0.0</option>
                                <option value="RP002-1.0.1" {{ old('revision_parametros_regionales')=='RP002-1.0.1'?'selected':'' }}>RP002-1.0.1</option>
                                <option value="RP002-1.0.2" {{ old('revision_parametros_regionales')=='RP002-1.0.2'?'selected':'' }}>RP002-1.0.2</option>
                                <option value="RP002-1.0.3" {{ old('revision_parametros_regionales')=='RP002-1.0.3'?'selected':'' }}>RP002-1.0.3</option>

                            </select>

                            <label>Revisión de parámetros regionales</label>
                            <div class="form-text">Revisión de la especificación de Parámetros Regionales soportados por el dispositivo.</div>
                            @error('revision_parametros_regionales')
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
                            <input type="number" name="intervalo_enlace" value="{{ old('intervalo_enlace',3600) }}" class="form-control @error('intervalo_enlace') is-invalid @enderror" placeholder="" required>
                            <label>Intervalo de enlace ascendente esperado (segundos)</label>
                            <div class="form-text">El intervalo esperado en segundos en el que el dispositivo envía mensajes de enlace ascendente. Esto se utiliza para determinar si un dispositivo está activo o inactivo.</div>
                            @error('intervalo_enlace')
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
                            <input type="number" name="intervalo_estadisticas" value="{{ old('intervalo_estadisticas',1) }}" class="form-control @error('intervalo_estadisticas') is-invalid @enderror" placeholder="" required>
                            <label>Frecuencia de solicitud de estado del dispositivo (solicitud/día)</label>
                            <div class="form-text">Frecuencia para iniciar una solicitud de estado del dispositivo final (solicitud/día). Establezca en 0 para desactivar.</div>
                            @error('intervalo_estadisticas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                    
                <div class="col-lg-12">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-file-js"></i>
                            </div>
                            <textarea name="payload_codec_script" style="height: 500px;" class="form-control @error('payload_codec_script') is-invalid @enderror" placeholder="" required>{{ old('payload_codec_script') }}</textarea>
                            <label>Funciones de códec</label>
                            @error('payload_codec_script')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer text-muted">
            <button class="btn btn-primary" type="submit">Guardar</button>
            <a href="{{ route('perfil-dispositivos.index') }}" class="btn btn-danger">Cancelar</a>
            
        </div>
    </div>
    
</form>
        
@endsection

