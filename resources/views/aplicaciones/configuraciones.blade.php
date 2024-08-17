@extends('layouts.app')
@section('breadcrumbs')
{{-- {{ Breadcrumbs::render('categoria-gateway.create') }} --}}
@endsection

@section('content')
<div class="card">
    <div class="card-header">FORMULARIO DE CONFIGURACIONES DE DISTANCIA</div>
    <div class="container mt-1">
        <form class="row g-3" action="{{ route('store.configuraciones.distancia') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" class="form-control" id="application_id" name="application_id" value="{{$application->id}}" required>

            <div class="col-md-4">
                <label for="valor" class="form-label">Valor (mm)</label>
                <input type="number" class="form-control" id="valor" name="valor" placeholder="Valor entero" required>
            </div>
            <div class="col-md-4">
                <label for="descripcion" class="form-label">Descripción</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción" required>
            </div>
            <div class="col-md-4">
                <label for="color" class="form-label">Color</label>
                <input type="color" class="form-control form-control-color" id="color" name="color" title="Elige un color" required>
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
                            <th>Valor (mm)</th>
                            <th>Descripción</th>
                            <th>Color</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($configuraciones) && count($configuraciones))
                            @foreach ($configuraciones as $item)
                                <tr>
                                    <td>{{$item->valor}} mm</td>
                                    <td>{{$item->descripcion}}</td>
                                    <td style="background-color: {{$item->color}};"></td>
                                    <td>
                                        <a href="{{ route('delete.configuraciones.distancia', $item->id) }}" class="text-danger">
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
            border-radius: 75px;
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
    </style>
@endpush

@push('scriptsFooter')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const configuraciones = @json($configuraciones);

        const maxValue = Math.max(...configuraciones.map(item => item.valor));
        const minValue = Math.min(...configuraciones.map(item => item.valor));
        const levelContainer = document.getElementById('level-container');

        // Ordenamos las configuraciones de menor a mayor valor
        configuraciones.sort((a, b) => a.valor - b.valor);

        configuraciones.forEach((item, index) => {
            let nextValue;
            if (index < configuraciones.length - 1) {
                nextValue = configuraciones[index + 1].valor;
            } else {
                nextValue = 0; // El último segmento llega hasta 0
            }

            // Calculamos la altura relativa entre dos valores consecutivos
            const heightPercentage = ((nextValue - item.valor) / (maxValue - minValue)) * 100;

            const section = document.createElement('div');
            section.className = 'liquid-section';
            section.style.height = `${heightPercentage}%`;
            section.style.backgroundColor = item.color;
            section.textContent = item.descripcion;

            levelContainer.appendChild(section);
        });
    });
</script>
@endpush
        
@endsection
