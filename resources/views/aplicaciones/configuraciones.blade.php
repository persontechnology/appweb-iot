@extends('layouts.app')
@section('breadcrumbs')
    {{-- {{ Breadcrumbs::render('categoria-gateway.create') }} --}}
@endsection

@section('content')
    <div class="card">
        <div class="container mt-1 p-3">
            <div class="">AGREGAR DISTANCIA DEL CONTENEDOR (mm)</div>
            <form class="row mt-2" action="{{ route('aplication.update-distance') }}" method="POST">
                @csrf
                <input type="hidden" class="form-control" id="application_id" name="application_id"
                    value="{{ $application->id }}" required>

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
                <form class="row g-3" action="{{ route('store.configuraciones.distancia') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" class="form-control" id="application_id" name="application_id"
                        value="{{ $application->id }}" required>

                    <div class="col-md-3">
                        <label for="valor" class="form-label">Valor (%)</label>
                        <input type="number" class="form-control" id="valor" name="valor" placeholder="Valor entero"
                            required>
                    </div>
                    <div class="col-md-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion"
                            placeholder="Descripción" required>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <label for="color" class="form-label">Color</label>
                                <input type="color" class="form-control form-control-color" id="color" name="color"
                                    title="Elige un color" required>
                            </div>
                            <div class="mt-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="notification" id="sc_ls_c">
                                    <label class="form-check-label" for="notification">
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
                                @if (isset($configuraciones) && count($configuraciones))
                                    @foreach ($configuraciones as $item)
                                        <tr>
                                            <td>{{ $item->valor }} %</td>
                                            <td>{{ $item->descripcion }}</td>
                                            <td style="background-color: {{ $item->color }};"></td>
                                            <td>
                                                @if ($item->notification)
                                                    <span> Notificar</span>
                                                @else
                                                    <span>No Notificar</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('delete.configuraciones.distancia', $item->id) }}"
                                                    class="text-danger">
                                                    <i class="ph ph-trash me-2"></i>
                                                </a>
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
                const configuraciones = @json($configuraciones);
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
                        if (porcentajeLlenado <= niveles[i].valor) {
                            rango = niveles[i].descripcion;
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

                console.log(
                    `El contenedor está lleno en un ${porcentajeLlenado.toFixed(2)}%. Esto corresponde al rango: ${rangoLlenado}.`
                    );

                function generarNivelesDeAgua(niveles) {
                    const tank = document.getElementById('level-container');
                    let bottomPosition = 0;

                    niveles.forEach(nivel => {
                        const waterLevel = document.createElement('div');
                        waterLevel.className = 'water-level';
                        waterLevel.style.height = `${nivel.valor - bottomPosition}%`;
                        waterLevel.style.position = 'absolute';
                        waterLevel.style.bottom = `${bottomPosition}%`;
                        waterLevel.style.width = '100%';
                        waterLevel.style.backgroundColor = hexToRgba(nivel.color, 0.8);
                        waterLevel.textContent = `${nivel.descripcion} - ${nivel.valor}%`;

                        bottomPosition = nivel.valor; // Actualizar la posición para el siguiente nivel
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
