@extends('layouts.app')
@section('breadcrumbs')
{{-- {{ Breadcrumbs::render('categoria-gateway.create') }} --}}
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <strong>Detalle:</strong> {{ $lectura->dipositivoXlecturaId($lectura->id)->dev_eui_hex }}

        <a href="{{ route('lecturas.descargarPdf',$lectura->id) }}">
            Descargar <i class="ph ph-file-pdf"></i>
        </a>
    </div>
    <div class="card-body">
       @include('lecturas.detalle',['lectura'=>$lectura])
    </div>
</div>





        
@endsection

