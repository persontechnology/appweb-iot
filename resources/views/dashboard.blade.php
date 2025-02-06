@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('dashboard') }}
@endsection

@section('breadcrumb_elements')
@endsection


@section('content')
@viteReactRefresh
@vite(['resources/js/app.js'])
<div id="react-dashboard" class="m-0 p-0"></div>
@endsection


