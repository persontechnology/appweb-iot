@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('dashboard') }}
@endsection

@section('breadcrumb_elements')
    <div class="d-lg-flex mb-2 mb-lg-0">
        
    </div>
@endsection

@section('content')
    <div class="card">

        <div class="card-body">
            <div class="container mt-5">
                <form class="row g-3" action="{{ route('configuraciones.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="col-auto">
                        <input type="number" class="form-control" id="valor" name="valor" placeholder="Valor entero" required>
                    </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción" required>
                    </div>
                    <div class="col-auto">
                        <input type="color" class="form-control form-control-color" id="color" name="color" title="Elige un color" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary mb-3">Enviar</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    @push('scriptsFooter')
   
    @endpush
@endsection
