@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('dashboard') }}
@endsection

@section('breadcrumb_elements')
@endsection


@section('content')
    <div id="react-dashboard" class="m-0 p-0"></div>
@endsection


<style>
    .content-inner {
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
        overflow-y: hidden !important;
        /* Evita scroll externo */
    }
</style>
