@extends('layouts.app')
@section('breadcrumbs')
{{ Breadcrumbs::render('dashboard') }}
@endsection


@section('content')

<!-- Underline tabs -->
<div class="card">
    
    @include('alertas.tab-header',['alerta'=>$alerta])
    <form action="{{ route('alertas.actualizarUsuarios') }}" method="post">
        @csrf
        <input type="hidden" name="alerta_id" value="{{ $alerta->id }}">
        <div class="card-body">
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>
        <div class="card-footer text-muted">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>

</div>
<!-- /underline tabs -->
       
 
<div id="modal_ver_mapa" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-full modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mapa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="map"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
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
{{ $dataTable->scripts() }}


<script>
    
    var map;
    var marker;

    function verMapa(arg) {
        $('#modal_ver_mapa').modal('show');
        var coordenadas = [$(arg).data('lat'), $(arg).data('long')];
        actualizarMapa(coordenadas);
    }

    function actualizarMapa(coordenadas) {
        if (map) {
            map.setView(coordenadas, 8);
            if (marker) {
                marker.setLatLng(coordenadas);
            } else {
                marker = L.marker(coordenadas, {
                    title: 'Ubicación de gateway',
                    draggable: true
                }).addTo(map);
            }
        }
    }

    $(document).ready(function () {
        map = L.map('map').setView([0, 0], 2); // Inicializa el mapa en coordenadas (0,0) y zoom 2

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        // Inicializa un marcador sin coordenadas
        marker = L.marker([0, 0], {
            title: 'Ubicación de gateway',
            draggable: true
        }).addTo(map);
    });

</script>


</script>

@endpush
