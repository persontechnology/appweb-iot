@extends('layouts.app')
@section('breadcrumbs')
    {{-- {{ Breadcrumbs::render('categoria-gateway.create') }} --}}
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @if (isset($devicesProfiles) && count($devicesProfiles) > 0)
                <ul class="nav nav-tabs mt-1" id="myTab" role="tablist">
                    @foreach ($devicesProfiles as $index => $devicesProfile)
                        @php
                            $isActive =
                                request('active_tab') == "tab-{$devicesProfile->id}" ||
                                (request('active_tab') == null && $index == 0);
                        @endphp
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $isActive ? 'active' : '' }}" id="tab-{{ $devicesProfile->id }}"
                                data-bs-toggle="tab" data-bs-target="#content-{{ $devicesProfile->id }}" type="button"
                                role="tab" aria-controls="content-{{ $devicesProfile->id }}"
                                aria-selected="{{ $isActive ? 'true' : 'false' }}">
                                {{ $devicesProfile->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content" id="myTabContent">
                    @foreach ($devicesProfiles as $index => $devicesProfile)
                        @php
                            $isActive =
                                request('active_tab') == "tab-{$devicesProfile->id}" ||
                                (request('active_tab') == null && $index == 0);
                        @endphp
                        <div class="tab-pane fade {{ $isActive ? 'show active' : '' }}"
                            id="content-{{ $devicesProfile->id }}" role="tabpanel" id="content-{{ $devicesProfile->id }}"
                            role="tabpanel" aria-labelledby="tab-{{ $devicesProfile->id }}">
                            @switch($devicesProfile->name)
                                @case('Distancia')
                                    <div class="container mt-1 p-3">
                                        <div class="">AGREGAR DISTANCIA DEL CONTENEDOR (mm)</div>
                                        <form class="row mt-2" id="configForm" action="{{ route('aplication.update-distance') }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" class="form-control" id="application_id" name="application_id"
                                                value="{{ $application->id }}" required>
                                            <input type="hidden" name="active_tab" id="activeTabInput"
                                                value="{{ request('active_tab') }}">

                                            <div class="col-md-6 mt-2">
                                                <input type="text" class="form-control" id="distance" name="distance"
                                                    value="{{ $application->distance }}" required>
                                            </div>

                                            <div class="col-6 mt-2">
                                                <button type="submit" class="btn btn-primary">GUARDAR DISTANCIA</button>
                                            </div>
                                        </form>
                                    </div>
                                    @if (isset($application->distance) && $application->distance > 0)
                                        <div class="card-header">FORMULARIO DE CONFIGURACIONES DE DISTANCIA</div>
                                        <div class="container mt-1">
                                            <form class="row g-3" action="{{ route('store.configuration.rules.distance') }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="configuration_id"
                                                    value="{{ $devicesProfile->configuration->id }}">
                                                <input type="hidden" name="sensor" value="button">
                                                <input type="hidden" name="active_tab" id="activeTabInput"
                                                    value="{{ request('active_tab') }}">

                                                <div class="col-md-3">
                                                    <label for="valor" class="form-label">Valor (%)</label>
                                                    <input type="number" class="form-control" id="min_value" name="min_value"
                                                        placeholder="Valor entero" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="description" class="form-label">Descripción</label>
                                                    <input type="text" class="form-control" id="description" name="description"
                                                        placeholder="Descripción" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <label for="color" class="form-label">Color</label>
                                                            <input type="color" class="form-control form-control-color"
                                                                id="color" name="color" title="Elige un color" required>
                                                        </div>
                                                        <div class="mt-3">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" name="alert"
                                                                    id="sc_ls_c">
                                                                <label class="form-check-label" for="alert">
                                                                    Activar notificación
                                                                </label>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <button type="submit" class="btn btn-primary mb-3">Enviar</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="container mt-1 mb-4">
                                            <div class="row">
                                                <div class="col-8">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Valor (%)</th>
                                                                <th>Descripción</th>
                                                                <th>Color</th>
                                                                <th>Notificar</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (isset($devicesProfile->configuration->rules) && count($devicesProfile->configuration->rules))
                                                                @foreach ($devicesProfile->configuration->rules as $item)
                                                                    <tr>
                                                                        <td>{{ $item->min_value }} %</td>
                                                                        <td>{{ $item->description }}</td>
                                                                        <td style="background-color: {{ $item->color }};">
                                                                        </td>
                                                                        <td>
                                                                            @if ($item->alert)
                                                                                <span> Notificar</span>
                                                                            @else
                                                                                <span>No Notificar</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <form
                                                                                action="{{ route('configuration.rules.destroy', $item->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="btn btn-danger p-0 pl-3 pr-3 btn-sm">
                                                                                    <i class="ph ph-trash"></i></button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-4">
                                                    <div class="containerDistancia">
                                                        <div class="level" id="level-container">
                                                            <!-- Las secciones de líquido se añadirán aquí -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                    @endif

                                </div>
                            @break

                            @case('Button')
                                <div class="card-header">FORMULARIO DE CONFIGURACIONES DE BOTÓN</div>
                                <div class="container mt-1">
                                    <form action="{{ route('store.configuration.rules.button') }}" method="POST"
                                        id="configForm">
                                        @csrf
                                        <input type="hidden" name="configuration_id"
                                            value="{{ $devicesProfile->configuration->id }}">
                                        <input type="hidden" name="sensor" value="button">
                                        <input type="hidden" name="active_tab" id="activeTabInput"
                                            value="{{ request('active_tab') }}">
                                        <div class="row">


                                            <div class="mb-3 col-6">
                                                <label for="event" class="form-label">Tipo de presión</label>
                                                <select class="form-select" id="event" name="event" required>
                                                    <option value="short">Presión corta</option>
                                                    <option value="long">Presión larga</option>
                                                    <option value="double">Doble presión</option>
                                                    <option value="unknown">Desconocido</option>
                                                </select>
                                            </div>

                                            <div class="mb-3 col-6">
                                                <label for="alert" class="form-label">Activar alerta</label>
                                                <select name="alert" id="alert" class="form-control" required>
                                                    <option value="1">Sí</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary float-end">Guardar configuración</button>
                                    </form>
                                </div>
                                <div class="container mt-3">
                                    <h5>Reglas Configuradas</h5>
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th class="text-center m-0 p-0">ID</th>
                                                <th class="text-center m-0 p-0">Tipo de Presión</th>
                                                <th class="text-center m-0 p-0"></th>
                                                <th class="text-center m-0 p-0">Alerta</th>
                                                <th class="text-center m-0 p-0">Acciones</th>
                                            </tr>
                                        </thead>
                                        @if (isset($devicesProfile->configuration->rules) && count($devicesProfile->configuration->rules))
                                            <tbody>
                                                @foreach ($devicesProfile->configuration->rules as $rule)
                                                    <tr>
                                                        <td class="m-0 p-0 text-center">{{ $rule->id }}</td>
                                                        <td class="m-0 p-0 text-center">{{ ucfirst($rule->event) }}</td>
                                                        <td class="m-0 p-0 text-center">
                                                            @switch($rule->event)
                                                                @case('short')
                                                                    Presión corta
                                                                @break

                                                                @case('long')
                                                                    Presión larga
                                                                @break

                                                                @case('double')
                                                                    Doble presión
                                                                @break

                                                                @default
                                                                    Desconocido
                                                            @endswitch
                                                        </td>
                                                        <td class="m-0 p-0 text-center">{{ $rule->alert ? 'Sí' : 'No' }}</td>
                                                        <td class="m-0 p-0 text-center">
                                                            <form action="{{ route('configuration.rules.destroy', $rule->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger p-0 pl-3 pr-3 btn-sm">
                                                                    <i class="ph ph-trash"></i></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        @endif
                                    </table>
                                </div>
                            @break

                            @case('GPS')
                                <div class="card-header">FORMULARIO DE CONFIGURACIONES DE GPS</div>
                                <div class="container mt-1">
                                    <form action="{{ route('store.configuration.rules.gps') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="configuration_id"
                                            value="{{ $devicesProfile->configuration->id }}">
                                        <input type="hidden" name="sensor" value="gps">
                                        <div class="row">


                                            <div class="mb-3 col-6">
                                                <label for="event" class="form-label">Tipo de evento</label>
                                                <select class="form-select" id="event" name="event" required>
                                                    <option value="start">Inicio del movimiento</option>
                                                    <option value="moving">En movimiento</option>
                                                    <option value="stop">Detenido</option>
                                                    <option value="unknown">Desconocido</option>
                                                </select>
                                            </div>

                                            <div class="mb-3 col-6">
                                                <label for="alert" class="form-label">Activar alerta</label>
                                                <select name="alert" id="alert" class="form-control" required>
                                                    <option value="1">Sí</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary float-end">Guardar configuración</button>
                                    </form>
                                    <h5>Reglas Configuradas</h5>
                                    <table class="table table-bordered mt-3">
                                        <thead class="table-dark">
                                            <tr>
                                                <th class="text-center m-0 p-0">ID</th>
                                                <th class="text-center m-0 p-0">Evento</th>
                                                <th class="text-center m-0 p-0"></th>
                                                <th class="text-center m-0 p-0">Alerta</th>
                                                <th class="text-center m-0 p-0">Acciones</th>
                                            </tr>
                                        </thead>
                                        @if (isset($devicesProfile->configuration->rules) && count($devicesProfile->configuration->rules))
                                            <tbody>
                                                @foreach ($devicesProfile->configuration->rules as $rule)
                                                    <tr>
                                                        <td class="text-center m-0 p-0">{{ $rule->id }}</td>
                                                        <td class="text-center m-0 p-0">{{ $rule->event }}</td>
                                                        <td class="text-center m-0 p-0">
                                                            @switch($rule->event)
                                                                @case('start')
                                                                    Inicio del movimiento
                                                                @break

                                                                @case('moving')
                                                                    En movimiento
                                                                @break

                                                                @case('stop')
                                                                    Detenido
                                                                @break

                                                                @default
                                                                    Desconocido
                                                            @endswitch
                                                        </td>
                                                        <td class="text-center m-0 p-0">{{ $rule->alert ? 'Sí' : 'No' }}</td>
                                                        <td class="text-center m-0 p-0">
                                                            <form action="{{ route('configuration.rules.destroy', $rule->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger  p-0 pl-3 pr-3 btn-sm">
                                                                    <i class="ph ph-trash"></i>

                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        @endif
                                    </table>
                                </div>
                            @break

                            @default
                        @endswitch
                </div>
            @endforeach
        </div>
    @else
        <div>No existe para configuraciones</div>
        @endif


    </div>
    </div>
    @push('scriptsFooter')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".nav-link").forEach(tab => {
                    tab.addEventListener("click", function() {
                        document.getElementById("activeTabInput").value = this.id;
                    });
                });
            });
        </script>
    @endpush
    @push('scriptsHeader')
        <style>
            .containerDistancia {
                font-family: Arial, sans-serif;
                text-align: center;
            }

            .level {
                width: 150px;
                height: 300px;
                border: 2px solid #ccc;
                border-radius: 35px;
                position: relative;
                overflow: hidden;
                background-color: #f2f2f2;
                margin-bottom: 20px;
            }

            .liquid-section {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 0.8em;
                box-sizing: border-box;
                border-top: 2px solid white;
            }

            .water-level {
                width: 100%;
                position: absolute;
                left: 0;
                text-align: center;
                color: white;
                font-weight: bold;
            }
        </style>
    @endpush

    @push('scriptsFooter')
        <script>
            $(document).ready(function() {
                const configuracionesDrivers = @json($devicesProfiles);
                let configurationDitance = configuracionesDrivers.find(config => config.name === 'Distancia');
                let configuration = configurationDitance.configuration;
                let configuraciones = configuration.rules;


                // Función para calcular el porcentaje de llenado
                function calcularPorcentajeLlenado(nivelActual, nivelMaximo) {
                    // Invertir el nivel: 0 es vacío, nivelMaximo es lleno
                    const nivelInvertido = nivelMaximo - nivelActual;

                    // Convertir el nivel invertido a un porcentaje
                    const porcentajeLlenado = (nivelInvertido / nivelMaximo) * 100;
                    console.log(porcentajeLlenado);

                    return porcentajeLlenado;
                }

                // Función para determinar el rango de llenado basado en el porcentaje
                function determinarRangoLlenado(porcentajeLlenado, niveles) {

                    let rango = "Desconocido";

                    for (let i = 0; i < niveles.length; i++) {
                        if (porcentajeLlenado <= niveles[i].min_value) {
                            rango = niveles[i].description;
                            break;
                        }
                    }

                    return rango;
                }

                // Ejemplo de uso
                const nivelMaximo = 1000; // Capacidad máxima del contenedor
                const nivelActual = 40; // Nivel actual del contenedor

                const porcentajeLlenado = calcularPorcentajeLlenado(nivelActual, nivelMaximo);
                const rangoLlenado = determinarRangoLlenado(porcentajeLlenado, configuraciones);

                function generarNivelesDeAgua(niveles) {
                    const tank = document.getElementById('level-container');
                    let bottomPosition = 0;

                    niveles.forEach(nivel => {
                        const waterLevel = document.createElement('div');
                        waterLevel.className = 'water-level';
                        waterLevel.style.height = `${nivel?.min_value - bottomPosition}%`;
                        waterLevel.style.position = 'absolute';
                        waterLevel.style.bottom = `${bottomPosition}%`;
                        waterLevel.style.width = '100%';
                        waterLevel.style.backgroundColor = hexToRgba(nivel.color, 0.8);
                        waterLevel.textContent = `${nivel.description} - ${nivel.min_value}%`;
                        bottomPosition = nivel.min_value; // Actualizar la posición para el siguiente nivel
                        tank.appendChild(waterLevel);
                    });
                }

                // Llamar a la función para generar los niveles de agua
                generarNivelesDeAgua(configuraciones);
            });

            function hexToRgba(hex, opacity) {
                let r = parseInt(hex.slice(1, 3), 16);
                let g = parseInt(hex.slice(3, 5), 16);
                let b = parseInt(hex.slice(5, 7), 16);

                return `rgba(${r}, ${g}, ${b}, ${opacity})`;
            }
        </script>
    @endpush

@endsection
