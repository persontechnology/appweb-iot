@extends('layouts.app')

@section('breadcrumbs')
{{ Breadcrumbs::render('categoria-gateway.index') }}
@endsection

@section('breadcrumb_elements')
    <div class="d-lg-flex mb-2 mb-lg-0">
        <a href="{{ route('categoria-gateway.create') }}" class="d-flex align-items-center text-body py-2">
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


@endsection

@push('scriptsHeader')

@endpush
@push('scriptsFooter')
{{ $dataTable->scripts() }}
@endpush