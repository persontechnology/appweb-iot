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
    <style>
        #map-container {
            position: relative;
            height: calc(100vh - 150px);
            /* Ajusta la altura del mapa restando la altura del overlay-section */
        }

        #map {
            width: 100%;
            height: 100%;
        }

        #overlay-section {
            position: absolute;
            bottom: 5px;
            /* Espacio desde la parte inferior */
            left: 5px;
            /* Espacio desde el lado izquierdo */
            width: 100%;
            /* Ancho del overlay-section */
            height: 150px;
            /* Altura máxima del overlay-section */
            overflow-y: auto;
            /* Habilita el scroll vertical si el contenido excede la altura máxima */
            background-color: #fff;
            z-index: 1000;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Sombra ligera */
        }

        .sidebar {
            --sidebar-width: 25.75rem;
        }

        .card {
            margin-bottom: 10px;
            /* Espacio entre tarjetas */
            border: 1px solid #ccc;
            /* Borde de las tarjetas */
            border-radius: 8px;
            /* Borde redondeado */
            padding: 10px;
            /* Espaciado interno de las tarjetas */
        }
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
        function actualizarListaDispositivos(dispositivos) {
            const listaDispositivos = $('#sidebar-dispositivos');
            listaDispositivos.empty();

            if (dispositivos.length === 0) {
                const mensaje = '<div class="text-center py-3">No se encontró resultados o dispositivos.</div>';
                listaDispositivos.append(mensaje);
            } else {
                const table = `
            <table class="table table-bordered">                
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
                        use_tracking
                    } = dispositivo;
                    const row = `
                <tr class="p-0 m-0">
                    <td class="p-1 pb-0 pt-0 m-0">
                        <a class="fs-sm" href="#" id="dispositivo-item-${dev_eui_hex}">
                            ${name}
                        </a>
                        <div class="fs-sm opacity-50">${dev_eui_hex}</div>
                    </td>
                    <td class="p-1 m-0 pb-0 pt-0">${use_tracking ? '<i class="ph ph-truck"></i>' : '<i class="ph ph-bell"></i>'}</td>
                </tr>
            `;
                    tbody.append(row);
                    mostrarInformacionDispositivo("sas")
                    // Asigna evento de clic para buscar y centrar el marcador por dispositivo completo
                    const dispositivoItem = document.getElementById(`dispositivo-item-${dev_eui_hex}`);
                    dispositivoItem.addEventListener('click', function(event) {
                        event.preventDefault();
                        buscarYcentrarMarketPorDispositivo(dispositivo);
                        document.getElementById('overlay-section').style.display = 'block';
                        mostrarInformacionDispositivo(dispositivo);
                    });
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
                puntos_localizacion_latest,
                description
            } = dispositivo;
            const overlaySection = $('#overlay-section');
            let estadoLectura = estadoDispositivo(puntos_localizacion_latest??lecturas_latest);
            let estadoBadgetLectura = estadoBadgetDispositivo(puntos_localizacion_latest??lecturas_latest);
            let sensor=sensorData(puntos_localizacion_latest??lecturas_latest)??'';
            let ultimaFecha='';
            let conversionFecha='';
            if(puntos_localizacion_latest){
                ultimaFecha=puntos_localizacion_latest?.created_at;
                conversionFecha=puntos_localizacion_latest?calcularDiferenciaTiempo(puntos_localizacion_latest.created_at):''
            }else{
                ultimaFecha=lecturas_latest?.created_at;
                conversionFecha=lecturas_latest?calcularDiferenciaTiempo(lecturas_latest.created_at):''
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
                   
                    <div class="list-group ">
                    ${sensor}
                    </div>
                    
                </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body m-0 p-0">
                    <h5 class="card-title text-small-card p-1 m-0">registros</h5>
                    <div class=" p-0 m-0">
                        <a href="#" class="navbar-nav-link navbar-nav-link-icon rounded-pill" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <i class="ph ph-bell"></i>
                            <span class="badge bg-yellow text-black position-absolute top-0 end-0 translate-middle-top zindex-1 rounded-pill mt-1 me-1" id="contadorLecturasNotificacion">
                                16
                            </span>
					    </a>
                    </div>
                
                </div>
            </div>
            </div>
    `);
            overlaySection.show();
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

        function estadoDispositivo(lecturasLatest) {

            if (lecturasLatest) {
                const fechaUltimaLectura = lecturasLatest?.created_at; // Fecha de la última lectura en formato ISO 8601
                const resultado = reportoEnUltimas24Horas(fechaUltimaLectura);
                if (resultado) {
                    return `<div class="bg-success bg-opacity-50 text-success lh-1 rounded-pill p-1">
                            <i class="ph ph-bell"></i>
                        </div>`
                } else {
                    return `<div class="bg-danger bg-opacity-50 text-danger lh-1 rounded-pill p-1">
                            <i class="ph ph-bell"></i>
                        </div>`
                }

            } else {
                return `<div class="bg-dark bg-opacity-50 text-dark lh-1 rounded-pill p-1">
                            <i class="ph ph-bell"></i>
                        </div>`
            }
        }

        function estadoBadgetDispositivo(lecturasLatest) {
            if (lecturasLatest) {
                const fechaUltimaLectura = lecturasLatest?.created_at; // Fecha de la última lectura en formato ISO 8601
                const resultado = reportoEnUltimas24Horas(fechaUltimaLectura);
                if (resultado) {
                    return `<span class="badge bg-success bg-opacity-10 text-success">Conectado</span>`
                } else {
                    return `<span class="badge bg-danger bg-opacity-10 text-danger">Desconectado</span>`
                }
            } else {
                return `<span class="badge bg-dark bg-opacity-10 text-dark">Sin registros</span>`
            }
        }
        function calcularDiferenciaTiempo(fechaUltimaLectura) {
                const fechaActual = new Date();
                const fechaLectura = new Date(fechaUltimaLectura);
                const diferenciaMs = fechaActual - fechaLectura;

                const minutos = Math.floor(diferenciaMs / 60000);
                const horas = Math.floor(minutos / 60);
                const dias = Math.floor(horas / 24);

                if (dias > 0) {
                    return `hace ${dias} ${dias === 1 ? 'día' : 'días'}`;
                } else if (horas > 0) {
                    return `hace ${horas} ${horas === 1 ? 'hora' : 'horas'}`;
                } else if (minutos > 0) {
                    return `hace ${minutos} ${minutos === 1 ? 'minuto' : 'minutos'}`;
                } else {
                    return 'hace unos momentos';
                }
            }

        
        function sensorData(lecturasLatest) {
            let conveerData=lecturasLatest?.data??null;
            if(conveerData){
                let conveerDataObject=conveerData.object;
                let distancia=conveerDataObject?.distance??null;
                let motionStatus=conveerDataObject?.motion_status??null;
                let press=conveerDataObject?.press??null;
                if(distancia&&distancia>=0){
                    distancia=Number(distancia)/10;
                    let iconoo=null;
                    let estadodistancia=null;
                    let descripcio=null;
                    if(distancia>50){
                        iconoo=` <img src="{{ asset('assets/images/sensor/contenedor-vacio.png') }}" class="h-48px" alt="">` ;
                        estadodistancia=`<span class="badge bg-success bg-opacity-20 text-success rounded-pill ms-auto">VACÍO</span>` ;
                    
                    }else if(distancia<50&& distancia>10){
                        iconoo=` <img src="{{ asset('assets/images/sensor/contenedor-medio.png') }}" class="h-48px" alt="">` ;
                        estadodistancia=`<span class="badge bg-danger bg-opacity-20 text-danger rounded-pill ms-auto">MEDIO</span>` ;
                    
                    }else{
                        iconoo=` <img src="{{ asset('assets/images/sensor/contenedor-lleno.png') }}" class="h-48px" alt="">`;
                        estadodistancia=`<span class="badge bg-warning bg-opacity-20 text-warning rounded-pill ms-auto">LLENO</span>` ;
                    }
                    return `                    
                    <div class="list-group-item p-1 d-flex">
                        <span class="mt-1">
                        DISTANCIA
                        </br>
                        ${estadodistancia}
                        </span>
                        <span class="badge text-success ms-auto">
                        <span class="badge bg-primary bg-opacity-20 text-primary rounded-pill ms-auto">
                            ${distancia}
                        </span>                        
                        ${iconoo}
                    </div>
                    <div class="list-group-item p-1 d-flex">
                        <span>
                            BATERIA 
                             <span class="badge bg-primary bg-opacity-20 text-primary rounded-pill ms-auto">
                             ${conveerDataObject?.battery}%
                            </span>
                             </span>
                            <span class="badge text-success ms-auto">
                                 <i class="ph-thin ph-battery-charging"></i>
                            </span>
                        </div>`;
                }else if(motionStatus && motionStatus==="moving"){
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
                }else if(press){
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

                }else{
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
            // Función para sumar horas a una fecha dada
        function sumarHoras(fecha, horas) {
            const nuevaFecha = new Date(fecha);
            nuevaFecha.setHours(nuevaFecha.getHours() + horas);
            return nuevaFecha;
        }

        // Función para verificar si un dispositivo reportó lecturas en las últimas 24 horas
        function reportoEnUltimas24Horas(fechaUltimaLectura) {
            const fechaActual = new Date();
            const fechaComparar = sumarHoras(fechaUltimaLectura, 24);
            return fechaActual < fechaComparar;
        }
        // Espera a que el documento esté completamente cargado antes de buscar dispositivos
        $(document).ready(function() {
            //buscarDispositivos();
            cargarDispositivos();
        });
    </script>
@endpush
