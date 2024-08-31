@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('dashboard') }}
@endsection

@section('breadcrumb_elements')
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
                    <button type="button"
                        class="btn btn-light border-transparent btn-icon rounded-pill btn-sm sidebar-control sidebar-secondary-toggle d-none d-lg-inline-flex">
                        <i class="ph-arrows-left-right"></i>
                    </button>

                    <button type="button"
                        class="btn btn-light border-transparent btn-icon rounded-pill btn-sm sidebar-mobile-secondary-toggle d-lg-none">
                        <i class="ph-x"></i>
                    </button>
                </div>
            </div>
            <!-- /header -->

            <!-- Online users -->
            <div class="sidebar-section">
                <div class="sidebar-section-header border-bottom">
                    <div class="form-control-feedback form-control-feedback-end">
                        <input id="searchInput" type="search" class="form-control" placeholder="Buscar dispositivo..."
                            onkeyup="buscarDispositivos()">

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
    <div id="map-container">
        <div id="map"></div>

        <div id="overlay-section" class="p-1">
            <!-- Aquí se insertarán dinámicamente las tarjetas  style="display: none;"-->
        </div>
    </div>
@endsection




@push('scriptsHeader')
    <link rel="stylesheet" href="{{ asset('custom/css/homeDashboard.css') }}">
    <script src="https://code.highcharts.com/stock/highstock.js"></script>
    <script src="https://code.highcharts.com/stock/modules/price-indicator.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="{{ asset('custom/js/general.js') }}"></script>
    <script src="{{ asset('custom/js/homeDashboard.js') }}"></script>
@endpush


@push('scriptsFooter')
    <script>
        // Inicializa el mapa y añade una capa base
        const map = L.map('map').setView([0, 0], 2);
        const tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Obtiene los datos de los dispositivos desde el servidor
        const dispositivos = null;

        // Array para almacenar las coordenadas de los dispositivos
        const bounds = [];

        // Objeto para almacenar los marcadores con dev_eui_hex como clave
        const markers = {};

        function cargarDispositivos() {
            $.ajax({
                type: 'GET',
                url: "{{ route('buscar.dispositivos') }}",
                success: function(response) {
                    actualizarMapaYLista(response);
                    actualizarListaDispositivos(response);
                },
                error: function(xhr, status, error) {
                    console.error(`Error al cargar dispositivos: ${error}`);
                }
            });
        }

        // Función para actualizar el mapa y la lista de dispositivos
        function actualizarMapaYLista(dispositivos) {
            // Limpiar marcadores y bounds anteriores
            Object.values(markers).forEach(marker => map.removeLayer(marker));
            bounds.length = 0;

            dispositivos.forEach(dispositivo => {
                const {
                    dev_eui_hex,
                    name,
                    latitude,
                    longitude,
                    battery_level,
                    last_seen_at,
                    description,
                    ver_lectura_url
                } = dispositivo;

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
        }

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
            const {
                dev_eui_hex,
                name,
                battery_level,
                last_seen_at,
                description,
                ver_lectura_url,
            } = dispositivo;
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
            if (query == null || query == "" || query.length > 2) {
                $.ajax({
                    type: 'GET',
                    url: "{{ route('buscar.dispositivos') }}",
                    data: {
                        query: query
                    },
                    success: function(response) {
                        actualizarListaDispositivos(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(`Error al buscar dispositivos: ${error}`);
                    }
                });
            }

        }

        // Actualiza la lista de dispositivos en la barra lateral
        let dispositivoSeleccionado = null;

        function actualizarListaDispositivos(dispositivos) {
            const listaDispositivos = $('#sidebar-dispositivos');
            listaDispositivos.empty();

            if (dispositivos.length === 0) {
                const mensaje = '<div class="text-center py-3">No se encontró resultados o dispositivos.</div>';
                listaDispositivos.append(mensaje);
            } else {
                const table = `
            <table class="table table-bordered table-hover table-sm mb-0">
                <tbody>
                </tbody>
            </table>
        `;
                listaDispositivos.append(table);

                const tbody = listaDispositivos.find('tbody');
                dispositivos.forEach(dispositivo => {
                    const {
                        dev_eui_hex,
                        name,
                        use_tracking,
                        deviceprofile,
                        lecturas_latest,
                        lecturas,
                        puntos_localizacion_latest,
                        description,
                        application
                    } = dispositivo;

                    // Determinar el estado del dispositivo basado en la última lectura o localización
                    let estadoLectura = estadoDispositivo(puntos_localizacion_latest ?? lecturas_latest);
                    let conversionFecha = '';
                    if (puntos_localizacion_latest) {
                        ultimaFecha = puntos_localizacion_latest?.created_at;
                        conversionFecha = puntos_localizacion_latest ? calcularDiferenciaTiempo(
                            puntos_localizacion_latest
                            .created_at) : ''
                    } else {
                        ultimaFecha = lecturas_latest?.created_at;
                        conversionFecha = lecturas_latest ? calcularDiferenciaTiempo(lecturas_latest.created_at) :
                            ''
                    }
                    let estadoBadgetLectura = estadoBadgetDispositivo(puntos_localizacion_latest ?? lecturas_latest,
                        9);
                    // Construir la fila de la tabla
                    const row = `
                <tr class="p-0 m-0 dispositivo-row" data-dev-eui-hex="${dev_eui_hex}">
                    <td class="p-1 m-0 align-middle">
                        <a class="fs-sm fw-bold text-decoration-none text-primary dispositivo-link" href="#">
                            ${name}
                        </a>
                        <div class="fs-xs opacity-50">${dev_eui_hex}</div>
                    </td>
                    <td class="p-1 m-0 text-center align-middle">
                        <div class="fs-sm">
                            ${estadoLectura}
                            
                        </div>
                    </td>
                    <td class="p-1 m-0 text-center align-middle">
                       <div class="d-flex align-items-center justify-content-between">
                        <div class="fw-semibold" style="font-size:9px;">${estadoBadgetLectura}</div>
                        </div>  
                        <div class="d-flex align-items-center justify-content-between text-center">
                            <div class="fw-semibold" style="font-size:9px;">
                                ${conversionFecha}                            
                            </div>
                        </div>    
                    </td>
                </tr>
            `;
                    tbody.append(row);

                    // Asigna evento de clic al <tr> para guardar y resaltar el dispositivo seleccionado
                    const dispositivoRow = tbody.find(`tr[data-dev-eui-hex="${dev_eui_hex}"]`);
                    dispositivoRow.on('click', function(event) {
                        event.preventDefault();

                        // Guardar el dispositivo seleccionado
                        dispositivoSeleccionado = dispositivo;

                        // Remover resaltado de todas las filas
                        document.querySelectorAll('.dispositivo-row').forEach(row => {
                            row.classList.remove('table-active');
                        });

                        // Resaltar la fila seleccionada
                        dispositivoRow.addClass('table-active');

                        // Realizar las demás acciones (mostrar información, centrar marcador, etc.)
                        buscarYcentrarMarketPorDispositivo(dispositivo);
                        document.getElementById('overlay-section').style.display = 'block';
                        mostrarInformacionDispositivo(dispositivo);
                    });
                });

                // Inicializa tooltips
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        }



        function mostrarInformacionDispositivo(dispositivo) {
            const {
                dev_eui_hex,
                name,
                use_tracking,
                deviceprofile,
                lecturas_latest,
                lecturas,
                puntos_localizacion_latest,
                description,
                application
            } = dispositivo;
            const overlaySection = $('#overlay-section');
            let estadoLectura = estadoDispositivo(puntos_localizacion_latest ?? lecturas_latest);
            let estadoBadgetLectura = estadoBadgetDispositivo(puntos_localizacion_latest ?? lecturas_latest);
            let aplicacion = application ?? null;
            let sensor = sensorData(puntos_localizacion_latest ?? lecturas_latest) ?? '';

            let ultimaFecha = '';
            let conversionFecha = '';
            if (puntos_localizacion_latest) {
                ultimaFecha = puntos_localizacion_latest?.created_at;
                conversionFecha = puntos_localizacion_latest ? calcularDiferenciaTiempo(puntos_localizacion_latest
                    .created_at) : ''
            } else {
                ultimaFecha = lecturas_latest?.created_at;
                conversionFecha = lecturas_latest ? calcularDiferenciaTiempo(lecturas_latest.created_at) : ''
            }
            overlaySection.html(`
        <div class="card-group" style="min-height: 140px;">
            <div class="card p-1">
                <div class="card-body m-0 p-0">
                <div class="card-title text-small-card m-0 p-0 d-flex align-items-center justify-content-between">
                    <div class="me-auto text-small-card me-lg-1">
                        <div class="text-muted">${dev_eui_hex}</div>
                        <div class="fw-semibold text-primary">${name}</div>
                    </div>
                    <div>
                        <span>${deviceprofile?.description??''}</span>
                    </div>
                     <div>
                     ${estadoLectura}
                    </div>
                </div>
                <div class="fw-semibold ext-muted ">${description}</div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="text-muted text-small-card">Estado del dispositivo</div>
                        <div class="fw-semibold">${estadoBadgetLectura}</div>
                    </div>  
                     <div class="d-flex align-items-center justify-content-between">
                        <div class="text-muted text-small-card">Último regitro</div>
                        <div class="fw-semibold">
                            ${conversionFecha}
                            <div class="text-muted text-small-card">
                            ${ultimaFecha??''}    
                            </div>
                        </div>
                    </div>            
                </div>
            </div>

            <div class="card p-1">
                <div class="card-body p-0 m-0">
                <h5 class="card-title text-small-card p-1 m-0">Sensores</h5>
                <div class="card-text p-0 m-0">
                    ${sensor}                    
                </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body m-0 p-0">             
                    <div>
                        <div id="grafico" style="height: 200px; min-width: 20px" class="none"></div>

                    </div>
                
                </div>
            </div>
            </div>
    `);

            overlaySection.show();
            updatePercentage(lecturas_latest, aplicacion ?? []);
            $('#grafico').show();
            let conveerData = lecturas_latest?.data ?? null;

            if (conveerData) {
                let conveerDataObject = conveerData.object;
                let distancia = conveerDataObject?.distance ?? null;
                if (distancia) {
                    createChart(lecturas, aplicacion);
                }
            } else {
                $('#grafico').hide();
            }
        }



        function pintarDispositivo(dispositivo) {
            const listaDispositivos = $('#sidebar-dispositivos');
            const existingItem = $(`#dispositivo-item-${dispositivo.dev_eui_hex}`);

            if (existingItem.length) {
                // Si el elemento ya existe, actualiza solo los datos necesarios
                existingItem.find('.fw-semibold').text(dispositivo.dev_eui_hex);
                existingItem.find('.opacity-10').text(dispositivo.name);
                existingItem.find('.align-self-center').html(dispositivo.use_tracking ? '<i class="ph ph-truck"></i>' :
                    '<i class="ph ph-bell text-danger"></i>');
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



        function sensorData(lecturasLatest) {
            let conveerData = lecturasLatest?.data ?? null;
            if (conveerData) {
                let conveerDataObject = conveerData.object;
                let distancia = conveerDataObject?.distance ?? null;
                let motionStatus = conveerDataObject?.motion_status ?? null;
                let press = conveerDataObject?.press ?? null;
                if (distancia !== null) {
                    distancia = distancia;
                    let iconoo = null;
                    let estadodistancia = null;
                    let descripcio = null;
                    return `                    
                    <div class="row">
                        <div class="col-6 text-center">
                           <div class="containerDistancia">
                             <div class="tank" id="tank">
                                
                            </div>
                            </div> 
                        </div>
                        <div class="col-6">
                            <div class="d-flex flex-column">
                                <!-- Estado del lector -->
                                <div class="mb-2">
                                    <span class="text-dark h6 fw-bold" style="font-size: 11px;" id="estadoLector">Estado</span>
                                </div>

                                <!-- Distancia -->
                                <div class="mb-2">
                                    <span class="badge bg-primary bg-opacity-20 text-primary rounded-pill">
                                        Distancia: <span id="distanciaValue">${distancia}</span> mm
                                    </span>
                                </div>

                                <!-- Batería -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-primary bg-opacity-20 text-primary rounded-pill">
                                            <span id="batteryValue">${conveerDataObject?.battery}</span>%
                                        </span>
                                        <span class="badge text-success">
                                            <i class="ph-thin ph-battery-charging"></i>
                                        </span>
                                    </div>
                            </div>
                        </div>                        
                   
                    </div>`;
                } else if (motionStatus && motionStatus === "moving") {
                    return `<div class="list-group-item p-1 d-flex">
                            <span>
                            BATERIA 
                            <span class="badge bg-primary bg-opacity-20 text-primary rounded-pill ms-auto">
                             ${conveerDataObject?.battery}%  
                            </span>
                            </span>
                            <span class="badge text-primary ms-auto">
                             <i class="ph-thin ph-battery-charging"></i>
                            </span>
                        </div>
                        <div class="list-group-item p-1 d-flex">
                            TEMPERATURA
                            <span class="text-primary ms-auto">
                            <span class="badge bg-primary bg-opacity-20 text-primary rounded-pill ms-auto">
                             ${conveerDataObject?.temperature}   
                            </span>

                            <i class="fa-solid fa-temperature-low"></i>    
                            </span>
                        </div>`;
                } else if (press) {
                    return `
                    <div class="list-group-item p-1 d-flex">
                            TIPO DE PRESION
                            <span class="text-primary ms-auto">
                            <span class="badge bg-primary bg-opacity-20 text-primary rounded-pill ms-auto">
                             ${press}   
                            </span>

                            <i class="fa-solid fa-temperature-low"></i>    
                            </span>
                        </div>
                    <div class="list-group-item p-1 d-flex">
                            <span>
                            BATERIA 
                            <span class="badge bg-primary bg-opacity-20 text-primary rounded-pill ms-auto">
                             ${conveerDataObject?.battery}%  
                            </span>
                            </span>
                            <span class="badge text-primary ms-auto">
                             <i class="ph-thin ph-battery-charging"></i>
                            </span>
                        </div>
                        `;

                } else {
                    return `
                    <div class="list-group-item p-1 d-flex">
                            <span>
                            BATERIA 
                            <span class="badge bg-primary bg-opacity-20 text-primary rounded-pill ms-auto">
                             ${conveerDataObject?.battery}%  
                            </span>
                            </span>
                            <span class="badge text-primary ms-auto">
                             <i class="ph-thin ph-battery-charging"></i>
                            </span>
                        </div>
                        `;
                }

            }
        }

        // Espera a que el documento esté completamente cargado antes de buscar dispositivos
        $(document).ready(function() {
            //buscarDispositivos();
            cargarDispositivos();
            $('#grafico').hide();
        });

        function createChart(lecturas, aplicacion) {

            const seriesData = lecturas.map(lectura => {
                return [
                    new Date(lectura.data.time).getTime(),
                    Number(calcularPorcentajeLlenado(
                        Number(lectura?.data?.object?.distance ?? 0).toFixed(2) ?? 0,
                        Number(aplicacion?.distance ?? 0)).toFixed(2))??0

                ];
            });
            debugger;
            Highcharts.stockChart('grafico', {
                rangeSelector: {
                    selected: 1
                },
                title: {
                    text: ''
                },
                series: [{
                    name: '(%)',
                    data: seriesData,
                    tooltip: {
                        valueSuffix: ' %'
                    }
                }],
                xAxis: {
                    type: 'datetime',
                    title: {
                        text: ''
                    }
                },
                yAxis: {
                    title: {
                        text: '(%)'
                    }
                }
            });

        }
    </script>
@endpush
