@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('dashboard') }}
@endsection

@section('breadcrumb_elements')
    <div class="d-lg-flex mb-2 mb-lg-0">
        <a href="{{ route('inquilinos.create') }}" class="d-flex align-items-center text-body py-2">
            <i class="ph ph-plus"></i>Nuevo
        </a>
    </div>
@endsection

@section('content')
    <div class="card">

        <div class="card-body">
            {{ $dataTable->table() }}
        </div>

    </div>
    @push('scriptsFooter')
    {{ $dataTable->scripts() }}
    @endpush
@endsection
