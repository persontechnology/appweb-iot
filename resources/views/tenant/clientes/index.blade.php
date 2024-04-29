@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('dashboard') }}
@endsection

@section('breadcrumb_elements')
    <div class="d-lg-flex mb-2 mb-lg-0">
        <a href="#" class="d-flex align-items-center text-body py-2" data-bs-toggle="modal" data-bs-target="#modal_full_user_no_tenant" onclick="event.preventDefault()">
            Asignar <i class="ph ph-user-plus ms-2"></i>
        </a>
    </div>
@endsection

@section('content')
    <div class="card">
        
        <div class="card-body">
            <div class="table-responsive">
            {!! $tenantUserDatatable->html()->table() !!}
            </div>
        </div>

    </div>

    <!-- Full width modal -->
    <form action="{{ route('inquilinos.clientes.asignar') }}" method="POST">
        @csrf
        <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
        <div id="modal_full_user_no_tenant" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-full">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Asignar usuarios en {{ $tenant->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="table-responsive">
                            {!! $tenantNoUserDatatable->html()->table() !!}
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
	<!-- /full width modal -->
@endsection

@push('scriptsFooter')
{!! $tenantUserDatatable->html()->scripts() !!} 
{!! $tenantNoUserDatatable->html()->scripts() !!} 
@endpush
