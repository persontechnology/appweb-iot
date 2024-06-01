@extends('layouts.app')

@section('breadcrumbs')
{{ Breadcrumbs::render('dashboard') }}
@endsection

@section('breadcrumb_elements')
    {{-- <div class="d-lg-flex mb-2 mb-lg-0">
        <a href="#" class="d-flex align-items-center text-body py-2">
            <i class="ph-lifebuoy me-2"></i>
            Support
        </a>

        <div class="dropdown ms-lg-3">
            <a href="#" class="d-flex align-items-center text-body dropdown-toggle py-2" data-bs-toggle="dropdown">
                <i class="ph-gear me-2"></i>
                <span class="flex-1">Settings</span>
            </a>

            <div class="dropdown-menu dropdown-menu-end w-100 w-lg-auto">
                <a href="#" class="dropdown-item">
                    <i class="ph-shield-warning me-2"></i>
                    Account security
                </a>
                <a href="#" class="dropdown-item">
                    <i class="ph-chart-bar me-2"></i>
                    Analytics
                </a>
                <a href="#" class="dropdown-item">
                    <i class="ph-lock-key me-2"></i>
                    Privacy
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="ph-gear me-2"></i>
                    All settings
                </a>
            </div>
        </div>
    </div> --}}
@endsection



@section('content')
<div id="controls">
    <select id="markerIdSelect" onchange="centerMapOnMarker()">
        <option value="">Selecciona un marcador</option>
        @foreach($dispositivos as $dispositivo)
            <option value="{{ $dispositivo->dev_eui_hex }}">{{ $dispositivo->name }}</option>
        @endforeach
    </select>
</div>

<div id="map"></div>


<div class="modal fade" id="markerModal" tabindex="-1" aria-labelledby="markerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markerModalLabel">Información del Dispositivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalContent"></div>
                
                {{-- BATERIA --}}
                <div class="battery-container">
                    <div class="battery-level" id="batteryLevel"></div>
                    <span class="ph ph-battery-three-quarters battery-icon"></span>
                    <div class="battery-value" id="batteryValue"></div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection




@push('scriptsHeader')
<style>
    #map { height: 480px; }
</style>
<style>
    .battery-container {
    width: 50px;
    height: 120px;
    border: 2px solid #000;
    border-radius: 5px;
    position: relative;
    display: inline-block;
    margin: 20px;
    background-color: #f0f0f0;
  }

  .battery-level {
    width: 100%;
    height: 0;
    position: absolute;
    bottom: 0;
    background-color: green;
    border-radius: 0 0 5px 5px;
  }

  .battery-value {
    position: absolute;
    bottom: 5px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 10px;
    color: black;
  }

  .battery-icon {
    font-size: 16px;
    position: absolute;
    top: 5px;
    left: 50%;
    transform: translateX(-50%);
  }
</style>
  
@endpush


@push('scriptsFooter')


<script>
    
    // Obtener las coordenadas desde Laravel
    var dispositivos = @json($dispositivos);

    // Inicializar el mapa
    var map = L.map('map').setView([-0.9447814006873896, -78.62915039062501], 2);
    // Añadir la capa de tiles de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    
    var markerCluster = L.markerClusterGroup();

    // Objeto para almacenar referencias a los marcadores
    var markers = {};

    // Añadir marcadores para cada coordenada y asignarles un ID
    dispositivos.forEach(function(dispositivo) {
        
        var marker = L.marker([dispositivo.latitude??0, dispositivo.longitude??0], {
            title: dispositivo.dev_eui,
            draggable: true
        }).on('click',function(){
            abrirModalDispositivo(dispositivo);
        });

        markerCluster.addLayer(marker);

        // Asignar un ID único al marcador
        markers[dispositivo.dev_eui_hex] = marker;
        

        // marker.bindPopup(`
        //     <b>${dispositivo.name}</b><br>
        //     DevEui: ${dispositivo.dev_eui_hex}<br>
        //     Battery %: ${dispositivo.battery_level}<br>
        //     Lat: ${dispositivo.latitude}<br>
        //     Lng: ${dispositivo.longitude}                            
        // `);

        // Evento para manejar el final del arrastre del marcador
        marker.on('dragend', function(e) {
            var latLng = e.target.getLatLng();
            console.log(`Nueva posición - Lat: ${latLng.lat}, Lng: ${latLng.lng}`);
            // Aquí puedes hacer una petición AJAX para actualizar la posición en la base de datos si es necesario
        });
    });
    map.addLayer(markerCluster);


    // Función para centrar el mapa en un marcador por ID
    function centerMapOnMarker() {
        
        var markerId = document.getElementById('markerIdSelect').value.trim();
        var marker = markers[markerId];
        
        if (marker) {
            map.setView(marker.getLatLng(), 15); // Ajusta el nivel de zoom según sea necesario
            marker.openPopup();
            var dispositivo = obtenerDispositivoPorId(2);
            abrirModalDispositivo(dispositivo)
        } else {
            alert('Marcador no encontrado');
        }
    }

    function obtenerDispositivoPorId(dev_eui) {
        return dispositivos.find(function(dispositivo) {
            return dispositivo.dev_eui === dev_eui;
        });
    }

    // abrir modal del sipositivo
    function abrirModalDispositivo(dispositivo){
        $('#modalContent').html(`
            <b>${dispositivo.name}</b><br>
            DevEui: ${dispositivo.dev_eui_hex}<br>
            Battery %: ${dispositivo.battery_level}<br>
            Lat: ${dispositivo.latitude}<br>
            Lng: ${dispositivo.longitude}<br>
            Visto por última vez: ${dispositivo.last_seen_at}
        `);
        actualizarNivelBateria(dispositivo.battery_level);
        $('#markerModal').modal('show');
        
    }

    function actualizarNivelBateria(level) {
      // Limit the battery level between 0 and 100
        level = Math.max(0, Math.min(100, level));

        // Update the battery level display
        var batteryLevelElement = document.getElementById('batteryLevel');
        batteryLevelElement.style.height = level + '%';

        // Update the battery value display
        var batteryValueElement = document.getElementById('batteryValue');
        batteryValueElement.textContent = level + '%';
    }

</script>






@endpush