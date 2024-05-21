@extends('layouts.app')
@section('breadcrumbs')
{{ Breadcrumbs::render('dashboard') }}
@endsection


@section('content')

<!-- Underline tabs -->
<div class="card">
    
    @include('alertas.tab-header',['alerta'=>$alerta])
    <form action="{{ route('alertas.actualizarUsuarios') }}" method="post">
        @csrf
        <input type="hidden" name="alerta_id" value="{{ $alerta->id }}">
        <div class="card-body">
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>
        <div class="card-footer text-muted">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>

</div>
<!-- /underline tabs -->
       
@endsection

@push('scriptsFooter')
{{ $dataTable->scripts() }}
@endpush
