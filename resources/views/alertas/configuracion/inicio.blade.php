@extends('layouts.app')
@section('breadcrumbs')
{{ Breadcrumbs::render('dashboard') }}
@endsection


@section('content')

<!-- Underline tabs -->
<div class="card">
    
    @include('alertas.tab-header',['alerta'=>$alerta])
    <form action="{{ route('alertas.actualizarEstado') }}" method="POST">
        @csrf
        <input type="hidden" name="alerta_id" value="{{ $alerta->id }}">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="border p-3 rounded">
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" class="form-check-input" {{ $alerta->estado?'checked':'' }} name="estado" id="sc_ls_c">
                            <label class="form-check-label" for="sc_ls_c" >La alerta está {{ $alerta->estado?'activado':'desactivado' }}</label>
                            <div class="form-text">Si la alerta esta desactivo se  ignorarán las tramas de enlace ascendente recibidas y las solicitudes de unión.</div>
                        </div>

                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" class="form-check-input" {{ $alerta->puede_enviar_email?'checked':'' }} name="puede_enviar_email" id="puede_enviar_email">
                            <label class="form-check-label" for="puede_enviar_email" >Enviar email a usuarios asignados</label>
                            <div class="form-text">Se enviara un email de los usuarios asignados en está alerta.</div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-muted">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>

</div>
<!-- /underline tabs -->







        
@endsection

