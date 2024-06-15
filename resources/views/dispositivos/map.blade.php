@extends('layouts.app')
@section('breadcrumbs')
{{-- {{ Breadcrumbs::render('categoria-gateway.create') }} --}}
@endsection

@section('content')

<div class="card">
    <div class="row m-3">
        <div class="col-lg-4">
            <h2>Tabla de errores</h2>
            @if (count($puntos_Localizaciones_error)>0)
            <table class="table table-sm table-boreder">
                <thead>
                    <th>
                        ERROR
                    </th>
                    <th>
                        FECHA
                    </th>
                </thead>
            <tbody>
                @foreach ($puntos_Localizaciones_error as $item)
                <tr>
                    <td>
                        <span style="font-size: 10px">{{$item->error}}</span>
                    </td>
                    <td>
                        <span style="font-size: 10px">{{$item->created_at}}</span>  
                    </td>
                </tr>                        
                @endforeach

            </tbody>
            </table>
            @else
                <span>No existe errores</span>
            @endif
            
            
        </div>
        <div class="col-lg-8">
            <h2>Ubicación del dispositivo</h2>
            <div id="map"></div>
        </div>
    </div>
</div>
@endsection


@push('scriptsHeader')
<style>
    #map { height: 480px; }
</style>
@endpush
@push('scriptsFooter')
<script>
     $(document).ready(function () {
        var puntosLocalizaciones = {!! json_encode($puntos_Localizaciones) !!};
        var map = L.map('map').setView([-1.075849, -78.598620], 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        
        console.log(puntosLocalizaciones);
        var latlngs = [];
        puntosLocalizaciones.forEach(function(punto, index) {
            var latlng = [Number(punto?.latitud ?? ''), Number(punto?.longitud ?? '')];
            latlngs.push(latlng);
        });
        var ultimoPunto = puntosLocalizaciones[puntosLocalizaciones.length - 1];
        var ultimoLatlng = [Number(ultimoPunto?.latitud ?? ''), Number(ultimoPunto?.longitud ?? '')];
        L.marker(ultimoLatlng).addTo(map);
        var polyline = L.polyline(latlngs, {color: 'red'}).addTo(map);
        map.fitBounds(polyline.getBounds());
    });
 </script>
@endpush