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

@section('secondary-sidebar')
    <div class="sidebar sidebar-secondary sidebar-expand-lg">

        <!-- Expand button -->
        <button type="button" class="btn btn-sidebar-expand sidebar-control sidebar-secondary-toggle h-100">
            <i class="ph-caret-right"></i>
        </button>
        <!-- /expand button -->


        <!-- Sidebar content -->
        <div class="sidebar-content">

            <!-- Header -->
            <div class="sidebar-section sidebar-section-body d-flex align-items-center pb-0">
                <h5 class="mb-0">Dispositivos</h5>
                <div class="ms-auto">
                    <button type="button" class="btn btn-light border-transparent btn-icon rounded-pill btn-sm sidebar-control sidebar-secondary-toggle d-none d-lg-inline-flex">
                        <i class="ph-arrows-left-right"></i>
                    </button>

                    <button type="button" class="btn btn-light border-transparent btn-icon rounded-pill btn-sm sidebar-mobile-secondary-toggle d-lg-none">
                        <i class="ph-x"></i>
                    </button>
                </div>
            </div>
            <!-- /header -->

            <!-- Online users -->
            <div class="sidebar-section">
                <div class="sidebar-section-header border-bottom">
                    <div class="form-control-feedback form-control-feedback-end">
                        <input id="searchInput" type="search" class="form-control" placeholder="Buscar dispositivo..." onkeyup="buscarDispositivos()">
                        <div class="form-control-feedback-icon">
                            <i class="ph-magnifying-glass opacity-50"></i>
                        </div>
                    </div>                    
                </div>

                <div class="collapse show">
                    <div class="sidebar-section-body" id="sidebar-dispositivos">
                        
                    </div>
                </div>
            </div>
            <!-- /online users -->

        </div>
        <!-- /sidebar content -->

    </div>
@endsection

@section('content')


<div id="map"></div>



@endsection




@push('scriptsHeader')
<style>
    #map { height: 480px; }
</style>

  
@endpush


@push('scriptsFooter')

<script>
    // Inicializa el mapa y añade una capa base
    const map = L.map('map').setView([0, 0], 2);
    const tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Obtiene los datos de los dispositivos desde el servidor
    const dispositivos = @json($dispositivos);

    // Array para almacenar las coordenadas de los dispositivos
    const bounds = [];

    // Objeto para almacenar los marcadores con dev_eui_hex como clave
    const markers = {};

    // Añade marcadores al mapa
    dispositivos.forEach(dispositivo => {
        const { dev_eui_hex, name, latitude, longitude, battery_level, last_seen_at, description, ver_lectura_url } = dispositivo;
        
        const lat = parseFloat(latitude ?? 0);
        const lng = parseFloat(longitude ?? 0);
        
        // Añadir coordenadas a bounds
        bounds.push([lat, lng]);

        const marker = L.marker([lat, lng], {
            title: dev_eui_hex,
            draggable: true
        }).addTo(map);

        // Almacena el marcador en el objeto markers
        markers[dev_eui_hex] = marker;

        // Añade un popup con información adicional
        const popupContent = `
            <b>${name}</b><br>
            DevEui: ${dev_eui_hex}<br>
            Batería: ${battery_level}%<br>
            Última vez visto: ${last_seen_at}<br>
            Descripción: ${description}
            ${ver_lectura_url ? `<br>Ver lectura: ${ver_lectura_url}` : ''}
        `;
        marker.bindPopup(popupContent);

        // Añade un tooltip con el dev_eui_hex
        // marker.bindTooltip(dev_eui_hex, {
        //     permanent: true,
        //     direction: 'top'
        // });
    });

    // Si hay coordenadas, ajusta el mapa para mostrar todos los marcadores
    if (bounds.length > 0) {
        map.fitBounds(bounds);
    }

    // Función para buscar y centrar el marcador por dev_eui_hex
    function buscarYcentrarMarketPorDevEuiHex(dev_eui_hex) {
        const marker = markers[dev_eui_hex];
        
        if (marker) {
            map.setView(marker.getLatLng(), 15);
            marker.openPopup();
        } else {
            console.log(`Marcador no encontrado para dev_eui_hex: ${dev_eui_hex}`);
        }
    }
     // Función para buscar y centrar el marcador por dispositivo completo
    function buscarYcentrarMarketPorDispositivo(dispositivo) {
        const { dev_eui_hex, name, battery_level, last_seen_at, description, ver_lectura_url } = dispositivo;
        const marker = markers[dev_eui_hex];

        if (marker) {
            // Actualiza el contenido del bindPopup
            const popupContent = `
                <b>${name}</b><br>
                DevEui: ${dev_eui_hex}<br>
                Batería: ${battery_level}%<br>
                Última vez visto: ${last_seen_at}<br>
                Descripción: ${description}<br>
                ${ver_lectura_url ? `<a href="${ver_lectura_url}">Ver lectura aquí.</a>` : ''}
            `;

            // Actualiza el contenido del popup
            marker.bindPopup(popupContent);

            // Centra el mapa en el marcador y abre el popup
            map.setView(marker.getLatLng(), 15); // Ajusta el nivel de zoom según sea necesario
            marker.openPopup();
        } else {
            console.log(`Marcador no encontrado para dev_eui_hex: ${dev_eui_hex}`);
        }
    }


    // Función para buscar dispositivos y actualizar la lista en la barra lateral
    function buscarDispositivos() {
        const query = document.getElementById('searchInput').value;

        $.ajax({
            type: 'GET',
            url: "{{ route('buscar.dispositivos') }}",
            data: { query: query },
            success: function(response) {
                actualizarListaDispositivos(response);
            },
            error: function(xhr, status, error) {
                console.error(`Error al buscar dispositivos: ${error}`);
            }
        });
    }

    // Actualiza la lista de dispositivos en la barra lateral
    function actualizarListaDispositivos(dispositivos) {
        const listaDispositivos = $('#sidebar-dispositivos');
        listaDispositivos.empty();

        if (dispositivos.length === 0) {
            const mensaje = '<div class="text-center py-3">No se encontró resultados o dispositivos.</div>';
            listaDispositivos.append(mensaje);
        } else {
            dispositivos.forEach(dispositivo => {
                const { dev_eui_hex, name, use_tracking } = dispositivo;
                const item = `
                    <a href="#" id="dispositivo-item-${dev_eui_hex}" style="color: inherit; text-decoration: none;">
                        <div class="d-flex mb-0 border-bottom">
                            <div class="flex-fill">
                                <span class="fw-semibold">${dev_eui_hex}</span>
                                <div class="fs-sm opacity-10">${name}</div>
                            </div>
                            <div class="ms-3 align-self-center">
                                ${use_tracking ? '<i class="ph ph-truck"></i>' : '<i class="ph ph-bell"></i>'}
                            </div>
                        </div>
                    </a>
                `;
                listaDispositivos.append(item);

                // Asigna evento de clic para buscar y centrar el marcador por dispositivo completo
                const dispositivoItem = document.getElementById(`dispositivo-item-${dev_eui_hex}`);
                dispositivoItem.addEventListener('click', function(event) {
                    event.preventDefault();
                    buscarYcentrarMarketPorDispositivo(dispositivo);
                });
            });
        }
    }


   
    function pintarDispositivo(dispositivo){
        const listaDispositivos = $('#sidebar-dispositivos');
        const existingItem = $(`#dispositivo-item-${dispositivo.dev_eui_hex}`);

        if (existingItem.length) {
            // Si el elemento ya existe, actualiza solo los datos necesarios
            existingItem.find('.fw-semibold').text(dispositivo.dev_eui_hex);
            existingItem.find('.opacity-10').text(dispositivo.name);
            existingItem.find('.align-self-center').html(dispositivo.use_tracking ? '<i class="ph ph-truck"></i>' : '<i class="ph ph-bell text-danger"></i>');
        } else {
            // Si el elemento no existe, crea uno nuevo
            const itemHtml = `
                <a href="#" id="dispositivo-item-${dispositivo.dev_eui_hex}" style="color: inherit; text-decoration: none;" onclick="event.preventDefault(); buscarYcentrarMarketPorDevEuiHex('${dispositivo.dev_eui_hex}')">
                    <div class="d-flex mb-0 border-bottom">
                        <div class="flex-fill">
                            <span class="fw-semibold">${dispositivo.dev_eui_hex}</span>
                            <div class="fs-sm opacity-10">${dispositivo.name}</div>
                        </div>
                        <div class="ms-3 align-self-center">
                            ${dispositivo.use_tracking ? '<i class="ph ph-truck"></i>' : '<i class="ph ph-bell text-danger"></i>'}
                        </div>
                    </div>
                </a>
            `;
            listaDispositivos.prepend(itemHtml);
        }
    }

    


    // Espera a que el documento esté completamente cargado antes de buscar dispositivos
    $(document).ready(function() {
        buscarDispositivos();
    });
</script>







@endpush