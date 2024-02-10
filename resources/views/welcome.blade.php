@extends('layouts.guest')
@section('content')
    <div class="card">
        <div class="card-header">{{ config('app.name') }}</div>
        <div class="card-body">
            <h4 class="card-title">Copyrigth, {{ date('Y') }}. Todos los derechos reservados.!</h4>
        </div>
        <div class="card-footer text-muted">PERSON TECHNOLOGY</div>
    </div>
    
@endsection