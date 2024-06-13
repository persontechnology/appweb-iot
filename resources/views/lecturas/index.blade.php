@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('dashboard') }}
@endsection



@section('content')
    <div class="card">

        <div class="card-body">
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>

    </div>
    @push('scriptsFooter')
    {{ $dataTable->scripts() }}

    
    @endpush
@endsection
