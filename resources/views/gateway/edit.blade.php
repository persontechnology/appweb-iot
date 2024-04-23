@extends('layouts.app')
@section('breadcrumbs')
{{-- {{ Breadcrumbs::render('categoria-gateway.create') }} --}}
@endsection

@section('content')

<form action="{{ route('gateways.update',$gateway_id_text) }}" method="POST" enctype="multipart/form-data">
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
                            <input type="text" name="nombre" value="{{ old('nombre',$gateway->name) }}" class="form-control @error('nombre') is-invalid @enderror" autofocus placeholder="" required>
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
                            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" placeholder="" required>{{ old('descripcion',$gateway->description) }}</textarea>
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
                            
                            <input type="text" disabled name="gateway_id" value="{{ old('gateway_id',$gateway_id_text) }}" class="form-control @error('gateway_id') is-invalid @enderror" placeholder="" required>
                            <label>Gateway ID (EUI64)</label>
                            @error('gateway_id')
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
                            <input type="number" name="intervalo_estadisticas" value="{{ old('intervalo_estadisticas',$gateway->stats_interval_secs) }}" class="form-control @error('intervalo_estadisticas') is-invalid @enderror" placeholder="" required>
                            <label>Intervalo de estadísticas (segundos)</label>
                            @error('intervalo_estadisticas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- <div class="col-lg-12">
                    <div class="mb-3">
                        @if ($tenants->count()>0)
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-presentation-chart"></i>
                            </div>
                            
                            <select class="form-select @error('tenant_id') is-invalid @enderror" name="tenant_id" required>
                                @foreach ($tenants as $te)
                                <option value="{{ $te->id }}" {{ old('tenant_id',$gateway->tenant_id)==$te->id?'selected':'' }}>{{ $te->name }}</option>
                                @endforeach
                            </select>

                            <label>Inquilinos</label>

                            @error('tenant_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @else
                            @include('layouts.alert',['type'=>'danger','msg'=>'No existe Inquilinos, por favor crear una.'])
                        @endif
                        
                    </div>
                </div> --}}

                <div class="col-lg-12">
                    <h2>Ubicación del gateway</h2>
                    <div id="map"></div>
                    <input type="hidden" name="latitude" id="latitude" value="{{ $gateway->latitude }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ $gateway->longitude }}">
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
@push('scriptsHeader')
<style>
    #map { height: 480px; }
</style>
@endpush
@push('scriptsFooter')
<script>
    $(document).ready(function () {
 
         var coordenadas=[$('#latitude').val(), $('#longitude').val()];

         var map = L.map('map').setView(coordenadas, 8);
 
         L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
 
         var market=L.marker(coordenadas,{
             title:'Ubicación de gateway',
             draggable:true
         }).addTo(map);
 
         market.on('dragend', function(event) {
             var marker = event.target;
             var position = marker.getLatLng();
             $('#latitude').val(position.lat);
             $('#longitude').val(position.lng);
             
         });
     });
 </script>
@endpush

