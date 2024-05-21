@extends('layouts.app')
@section('breadcrumbs')
{{ Breadcrumbs::render('dashboard') }}
@endsection


@section('content')

<!-- Underline tabs -->
<div class="card">
    
    @include('alertas.tab-header',['alerta'=>$alerta])
    
    <form action="{{ route('alertas.actualizarHorario') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
            <div class="card-body">
                <input type="hidden" name="alerta_id" value="{{ $alerta->id }}">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Día</th>
                                <th>Estado</th>
                                <th>Hora de Apertura</th>
                                <th>Hora de Cierre</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($horarios as $horario)
                                <tr>
                                    <td>{{ $horario->dia }}</td>
                                    <td>
                                        <div class="form-check">
                                            <input type="hidden" name="horarios[{{ $horario->id }}][id]" value="{{ $horario->id }}">
                                            <input type="checkbox" class="form-check-input" id="estado{{ $horario->id }}" name="horarios[{{ $horario->id }}][estado]" {{ old('horarios.'.$horario->id.'.estado', $horario->estado) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="estado{{ $horario->id }}">Activo</label>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="time" class="form-control" id="hora_apertura{{ $horario->id }}" name="horarios[{{ $horario->id }}][hora_apertura]" value="{{ $horario->hora_apertura ?? '' }}">
                                    </td>
                                    <td>
                                        <input type="time" class="form-control" id="hora_cierre{{ $horario->id }}" name="horarios[{{ $horario->id }}][hora_cierre]" value="{{ $horario->hora_cierre ?? '' }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-muted">
                <button class="btn btn-primary" type="submit">Guardar</button>
                <a href="{{ route('alertas.index') }}" class="btn btn-danger">Cancelar</a>
            </div>
        
    </form>

</div>
<!-- /underline tabs -->







        
@endsection

