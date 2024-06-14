@extends('layouts.app')
@section('breadcrumbs')
{{ Breadcrumbs::render('dashboard') }}
@endsection


@section('content')

<!-- Underline tabs -->
<div class="card">
    
    @include('alertas.tab-header',['alerta'=>$alerta])
    
    <form action="{{ route('alertas.guardarTipo') }}" method="POST">
        @csrf
        <input type="hidden" name="alerta_id" value="{{ $alerta->id }}">
        <div class="card-body">
            <div class="row">



                <div class="col-lg-12">
                    <div class="mb-3">
                    
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-laptop"></i>
                            </div>

                            <select name="parametro" class="form-select">
                                <optgroup label="Todos">
                                    <option value="battery=====Nivel de batería en porcentaje (0 a 100)">Nivel de batería en porcentaje (0 a 100) </option>
                                </optgroup>

                                <optgroup label="Smart Button">
                                    <option value="press=====Estado de la tecla (unknown, short, long, double)">Estado de la tecla (unknown, short, long, double)</option>
                                </optgroup>

                                <optgroup label="Enviroment Monitoring Sensor">
                                    <option value="distance=====Distancia en unidades específicas de tu dispositivo (3 to 450) milimetros.">Distancia en unidades específicas de tu dispositivo (30 to 4500) milimetros.</option>
                                    <option value="position=====Posición del dispositivo (unknown, normal, tilt)">Posición del dispositivo (unknown, normal, tilt) </option>
                                    
                                </optgroup>

                                <optgroup label="Asset Tracker">
                                    {{-- <option value="latitude=====Latitud en formato decimal (-90 a 90)">Latitud en formato decimal (-90 a 90) </option>
                                    <option value="longitude=====Longitud en formato decimal (-180 a 180)">Longitud en formato decimal (-180 a 180) </option> --}}
                                    <option value="position=====Posición del dispositivo (unknown, normal, tilt)">Posición del dispositivo (unknown, normal, tilt) </option>
                                    <option value="motion_status=====Estado de movimiento (unknown, start, moving, stop)">Estado de movimiento (unknown, start, moving, stop) </option>
                                    <option value="temperature===== Temperatura en grados Celsius (0 a 40)"> Temperatura en grados Celsius (0 a 40) </option>
                                    
                                    {{-- <option value="geofence_status=====Estado de la geovalla (unknown, inside, outside, unset)">Estado de la geovalla (unknown, inside, outside, unset) </option>
                                    <option value="position=====Posición del dispositivo (unknown, normal, tilt)">Posición del dispositivo (unknown, normal, tilt) </option>
                                    <option value="wifi_scan_result=====Resultado del escaneo de Wi-Fi (unknown, finish, timeout)">Resultado del escaneo de Wi-Fi (unknown, finish, timeout)</option>
                                    <option value="tamper_status=====Estado de la manipulación (unknown, install, uninstall)">Estado de la manipulación (unknown, install, uninstall)</option>
                                    <option value="temperature_abnormal=====Indicador de temperatura anormal (true, false)">Indicador de temperatura anormal (true, false) </option> --}}
                                </optgroup>
                            </select>
                            
                            <label>Parametro</label>
                            @error('parametro')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-3">
                    
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-laptop"></i>
                            </div>

                            <select name="condicion" class="form-select">
                                <option value="=">Igual a(=) </option>
                                <option value=">">Mas grande que (>)</option>
                                <option value="<">Menos que (<)</option>
                                <option value="!=">No es igual a (!=)</option>
                                <option value="">Vacío</option>
                                
                            </select>
                            
                            <label>Condiciones</label>
                            @error('condicion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-file-text"></i>
                            </div>
                            <input type="text" name="valor" value="{{ old('valor') }}" class="form-control @error('valor') is-invalid @enderror" placeholder="" required>
                            <label>Valor Tag</label>
                            @error('valor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="mb-3">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-envelope"></i>
                            </div>
                            <input type="text" name="mensaje" value="{{ old('mensaje') }}" class="form-control @error('mensaje') is-invalid @enderror" placeholder="" required>
                            <label>Mensaje</label>
                            @error('mensaje')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

        

                

            </div>
        </div>
        <div class="card-footer text-muted">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
    

</div>
<!-- /underline tabs -->

<div class="card">
    <div class="card-header">Listado</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>...</th>
                        <th scope="col">Parametro</th>
                        <th scope="col">Título</th>
                        <th scope="col">Condición</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Mensaje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($alerta->alertasTipos as $at)
                    <tr class="">
                        <td>
                            <a href="{{ route('alertas.eliminarTipo',$at->id) }}" data-msg="{{ $at->titulo }}" onclick="event.preventDefault(); eliminar(this)" class="dropdown-item">
                                <i class="ph ph-trash me-2"></i>
                            </a>
                        </td>
                        <td>{{ $at->parametro }}</td>
                        <td>{{ $at->titulo }}</td>
                        <td>{{ $at->condicion }}</td>
                        <td>{{ $at->valor }}</td>
                        <td>{{ $at->mensaje }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>

</div>






        
@endsection

