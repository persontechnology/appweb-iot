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
                <h5 class="mb-0">Sidebar</h5>
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


            <!-- Sidebar search -->
            <div class="sidebar-section">
                <div class="sidebar-section-header border-bottom">
                    <span class="fw-semibold">Sidebar search</span>
                    <div class="ms-auto">
                        <a href="#sidebar_secondary_search" class="text-reset" data-bs-toggle="collapse">
                            <i class="ph-caret-down collapsible-indicator"></i>
                        </a>
                    </div>
                </div>

                <div class="collapse show" id="sidebar_secondary_search">
                    <div class="sidebar-section-body">
                        <div class="form-control-feedback form-control-feedback-end">
                            <input type="search" class="form-control" placeholder="Search">
                            <div class="form-control-feedback-icon">
                                <i class="ph-magnifying-glass opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /sidebar search -->


            <!-- Sub navigation -->
            <div class="sidebar-section">
                <div class="sidebar-section-header border-bottom">
                    <span class="fw-semibold">Navigation</span>
                    <div class="ms-auto">
                        <a href="#sidebar_secondary_navigation" class="text-reset" data-bs-toggle="collapse">
                            <i class="ph-caret-down collapsible-indicator"></i>
                        </a>
                    </div>
                </div>

                <div class="collapse show" id="sidebar_secondary_navigation">
                    <ul class="nav nav-sidebar my-2" data-nav-type="accordion">
                        <li class="nav-item-header opacity-50">Header</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="ph-plus-circle me-2"></i>
                                Nav item 1
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="ph-circles-three-plus me-2"></i>
                                Nav item 2
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="ph-user-plus me-2"></i>
                                Nav item 3
                                <span class="badge bg-primary rounded-pill ms-auto">Badge</span>
                            </a>
                        </li>
                        <li class="nav-item-header opacity-50">Header</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="ph-kanban me-2"></i>
                                Nav item 4
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="ph-file-plus me-2"></i>
                                Nav item 5
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link disabled">
                                <i class="ph-file-x me-2"></i>
                                Nav item 6
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /sub navigation -->

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
    // Inicializa el mapa sin centrado específico
    var map = L.map('map');

    // Añade una capa de mapas base
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Obtén los datos de los dispositivos desde el servidor
    var dispositivos = @json($dispositivos);

    // Array para almacenar las coordenadas de los dispositivos
    var bounds = [];

    // Objeto para almacenar los marcadores con dev_eui_hex como clave
    var markers = {};

    // Añade marcadores al mapa
    dispositivos.forEach(function(dispositivo) {
        if (dispositivo.latitude && dispositivo.longitude) {
            var lat = parseFloat(dispositivo.latitude);
            var lng = parseFloat(dispositivo.longitude);

            // Añadir coordenadas a bounds
            bounds.push([lat, lng]);

            var marker = L.marker([lat, lng], {
                title: dispositivo.dev_eui_hex, // Añade el title
                draggable:true
            }).addTo(map);

            // Almacena el marcador en el objeto markers
            markers[dispositivo.dev_eui_hex] = marker;

            // Añade un popup con información adicional
            marker.bindPopup(`
                <b>${dispositivo.name}</b><br>
                DevEui: ${dispositivo.dev_eui_hex}<br>
                Batería: ${dispositivo.battery_level}%<br>
                Última vez visto: ${dispositivo.last_seen_at}<br>
                Descripción: ${dispositivo.description}
            `);

            // Añade un tooltip con el dev_eui_hex
            // marker.bindTooltip(dispositivo.dev_eui_hex, {
            //     permanent: true, // Hace que la etiqueta sea siempre visible
            //     direction: 'top' // Posiciona la etiqueta sobre el marcador
            // });
        }
    });

    // Si hay coordenadas, ajusta el mapa para mostrar todos los marcadores
    if (bounds.length > 0) {
        map.fitBounds(bounds);
    } else {
        // Si no hay coordenadas válidas, centra el mapa en una vista predeterminada
        map.setView([0, 0], 2);
    }

    // Función para buscar y centrar el marcador por dev_eui_hex
    function buscarYcentrarMarketPorDevEuiHex(dev_eui_hex) {
        var marker = markers[dev_eui_hex];
        if (marker) {
            map.setView(marker.getLatLng(), 15); // Ajusta el nivel de zoom según sea necesario
            marker.openPopup();
        } else {
            console.log("Marcador no encontrado para dev_eui_hex: " + dev_eui_hex);
        }
    }

    
</script>






@endpush