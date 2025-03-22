<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Global stylesheets -->
    <link href="{{ asset('assets/fonts/inter/inter.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/icons/phosphor/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/ltr/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/icons/fontawesome/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script src="{{ asset('assets/demo/demo_configurator.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- /core JS files -->




    <!-- Theme JS files -->
    <script src="{{ asset('assets/js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/forms/selects/select2.min.js') }}"></script>

    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

    {{-- validate --}}
    <script src="{{ asset('assets/js/vendor/validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/validate/messages_es.min.js') }}"></script>

    {{-- notify --}}
    <script src="{{ asset('assets/js/notify/notify.js') }}"></script>

    {{-- jquery confirm --}}
    <link rel="stylesheet" href="{{ asset('assets/js/vendor/jquery-confirm/jquery-confirm.min.css') }}">
    <script src="{{ asset('assets/js/vendor/jquery-confirm/jquery-confirm.min.js') }}"></script>

    {{-- mapa --}}
    <link rel="stylesheet" href="{{ asset('assets/js/vendor/leaflet/leaflet.css') }}">
    <script src="{{ asset('assets/js/vendor/leaflet/leaflet.js') }}"></script>
    <!-- /theme JS files -->
    @stack('scriptsHeader')
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/demo/pages/navbar_components.js') }}"></script>
    <script src="{{ asset('assets/demo/pages/user_pages_profile.js') }}"></script>

    <script src="{{ asset('assets/js/page.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/personalized.css') }}" />


    <!-- SECCTION VITE FOR ECHO SERVER --->

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        window.Laravel = {!! json_encode([
            'tenant_id' => Auth::user() ? Auth::user()->tenant_id : null,
        ]) !!};
    </script>



</head>

<body>

    <div class="navbar navbar-dark navbar-expand-lg navbar-static border-bottom border-bottom-white border-opacity-10">
        <div class="container-fluid">
            <div class="d-flex d-lg-none me-2">
                <button type="button" class="navbar-toggler sidebar-mobile-main-toggle rounded-pill">
                    <i class="ph-list"></i>
                </button>
                <button type="button" class="navbar-toggler sidebar-mobile-secondary-toggle rounded-pill">
                    <i class="ph-arrow-left"></i>
                </button>
            </div>

            <div class="navbar-brand flex-1 flex-lg-0">
                <a href="index.html" class="d-inline-flex align-items-center">
                    <img src="{{ asset('assets/images/logo_icon.svg') }}" alt="">
                    <img src="{{ asset('assets/images/logo_text_light.svg') }}"
                        class="d-none d-sm-inline-block h-16px ms-3" alt="">
                </a>
            </div>

            <div class="d-xl-none">
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse"
                    data-bs-target="#navbar-form-select2-dark">
                    <i class="ph ph-arrow-down"></i>
                </button>
            </div>

            <div class="navbar-collapse collapse" id="navbar-form-select2-dark">
                <div class="mt-3 mt-xl-0">
                    <div class="wmin-xl-200" data-color-theme="dark">
                        <form id="seleccionarInquilinoForm" action="{{ route('profile.seleccionarInquilino') }}"
                            method="POST">
                            @csrf
                            <select class="form-control form-control-select2" name="inquilinoId" id="selectInquilino"
                                data-container-css-class="bg-transparent" onchange="submitForm(this)">
                                @foreach (Auth::user()->tenants as $inquilino_menu)
                                    <option value="{{ $inquilino_menu->id }}"
                                        {{ Auth::user()->tenant_id == $inquilino_menu->id ? 'selected' : '' }}>
                                        {{ $inquilino_menu->name }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>


            <ul class="nav flex-row">

                <li class="nav-item nav-item-dropdown-lg dropdown ms-lg-2">
                    <a href="#" class="navbar-nav-link navbar-nav-link-icon rounded-pill"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        <i class="ph ph-bell"></i>
                        <span
                            class="badge bg-yellow text-black position-absolute top-0 end-0 translate-middle-top zindex-1 rounded-pill mt-1 me-1"
                            id="contadorLecturasNotificacion">
                            {{ App\Models\Lectura::totalLecturasEstadoFalse() }}
                        </span>
                    </a>

                    <div class="dropdown-menu wmin-lg-400 p-0">
                        <div class="d-flex align-items-center p-3">
                            <h6 class="mb-0">Lecturas</h6>
                            {{-- <div class="ms-auto">
								<a href="#" class="text-body">
									<i class="ph-plus-circle"></i>
								</a>
								<a href="#search_messages" class="collapsed text-body ms-2" data-bs-toggle="collapse">
									<i class="ph-magnifying-glass"></i>
								</a>
							</div> --}}
                        </div>

                        <div class="collapse" id="search_messages">
                            <div class="px-3 mb-2">
                                <div class="form-control-feedback form-control-feedback-start">
                                    <input type="text" class="form-control" placeholder="Search messages">
                                    <div class="form-control-feedback-icon">
                                        <i class="ph-magnifying-glass"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-menu-scrollable pb-2" id="contenedor-lecturas">
                            @php
                                $lecturas_h = App\Models\Lectura::take(20)->latest()->get();
                            @endphp
                            @foreach ($lecturas_h as $lectura_h)
                                <a href="{{ route('lecturas.show', $lectura_h->id) }}"
                                    class="dropdown-item align-items-start text-wrap py-2 {{ $lectura_h->estado == false ? 'bg-primary bg-opacity-20' : '' }}">
                                    <div class="flex-1">
                                        <span class="fw-semibold">{{ $lectura_h->dev_eui }}</span>
                                        <span class="text-muted float-end fs-sm">{{ $lectura_h->created_at }}</span>
                                        <div class="text-muted">
                                            {{ $lectura_h->dipositivoXlecturaId($lectura_h->id)->name ?? '' }}
                                        </div>

                                    </div>
                                </a>
                            @endforeach

                        </div>

                        <div class="d-flex border-top py-2 px-3">


                            <a href="{{ route('lecturas.descartarTodo', 'x') }}"
                                data-msg="¿Está seguro de descartar todas las lecturas?."
                                onclick="event.preventDefault(); eliminar(this)" class="text-body">
                                <i class="ph-checks me-1"></i>
                                Descartar todo
                            </a>
                            <a href="{{ route('lecturas.index') }}" class="text-body ms-auto">
                                Ver todos
                                <i class="ph-arrow-circle-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>


            <div class="navbar-collapse justify-content-center flex-lg-1 order-2 order-lg-1 collapse"
                id="navbar_search">

            </div>

            <ul class="nav flex-row justify-content-end order-1 order-lg-2">
                {{-- <li class="nav-item ms-lg-2">
					<a href="#" class="navbar-nav-link navbar-nav-link-icon rounded-pill" data-bs-toggle="offcanvas" data-bs-target="#notifications">
						<i class="ph-bell"></i>
						<span class="badge bg-yellow text-black position-absolute top-0 end-0 translate-middle-top zindex-1 rounded-pill mt-1 me-1">2</span>
					</a>
				</li> --}}

                <li class="nav-item nav-item-dropdown-lg dropdown ms-lg-2">
                    <a href="#" class="navbar-nav-link align-items-center rounded-pill p-1"
                        data-bs-toggle="dropdown">
                        <div class="status-indicator-container">
                            <img src="{{ asset('assets/images/demo/users/usuario.png') }}"
                                class="w-32px h-32px rounded-pill" alt="">
                            <span class="status-indicator bg-success"></span>
                        </div>
                        <span class="d-none d-lg-inline-block mx-lg-2">{{ Auth::user()->email }}</span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <i class="ph-user-circle me-2"></i>
                            My perfil
                        </a>

                        <div class="dropdown-divider"></div>
                        {{-- <a href="#" class="dropdown-item">
							<i class="ph-gear me-2"></i>
							Account settings
						</a> --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" class="dropdown-item"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="ph-sign-out me-2"></i>
                                Cerrar sesión
                            </a>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <!-- /main navbar -->



    <!-- Page content -->
    <div class="page-content">

        <!-- Main sidebar -->
        @include('layouts.main-sidebar')
        <!-- /main sidebar -->

        <!-- Secondary sidebar -->
        @hasSection('secondary-sidebar')
            @yield('secondary-sidebar')
        @else
        @endif
        <!-- /secondary sidebar -->


        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Inner content -->
            <div class="content-inner">

                <!-- Page header -->
                <div class="page-header page-header-light shadow">

                    <div class="page-header-content d-lg-flex border-top">
                        <div class="d-flex">
                            <div class="breadcrumb py-2">
                                @yield('breadcrumbs')
                            </div>

                            @hasSection('breadcrumb_elements')
                                <a href="#breadcrumb_elements"
                                    class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto"
                                    data-bs-toggle="collapse">
                                    <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                                </a>
                            @endif

                        </div>

                        <div class="collapse d-lg-block ms-lg-auto" id="breadcrumb_elements">
                            @yield('breadcrumb_elements')
                        </div>
                    </div>
                </div>
                <!-- /page header -->


                <!-- Content area -->
                <div class="content">

                    @include('layouts.errors-alert')

                    @yield('content')
                </div>
                <!-- /content area -->


                <!-- Footer -->
                @include('layouts.footer')
                <!-- /footer -->

            </div>
            <!-- /inner content -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->


    <!-- Notifications -->
    @include('layouts.notification')
    <!-- /notifications -->


    <!-- Demo config -->
    @include('layouts.demo-config')
    <!-- /demo config -->


    {{-- form eliminar --}}
    <form action="" method="POST" id="formEliminar">
        @csrf
        @method('DELETE')
    </form>
    {{-- end form eliminar --}}
    @stack('scriptsFooter')

    <script>
        $('#selectInquilino').on('change', function() {
            $('#seleccionarInquilinoForm').submit();
        });

        function anadirLecturaNotificacionHeader(dispositivo) {

            // Obtener el valor actual del elemento usando jQuery
            let valorActual = parseInt($('#contadorLecturasNotificacion').text().trim());
            // Incrementar el valor
            valorActual++;
            // Actualizar el texto del elemento con el nuevo valor usando jQuery
            $('#contadorLecturasNotificacion').text(valorActual);

            let contenedorLecturas = $('#contenedor-lecturas')
            let itemLectura = `
			
			<a href="${dispositivo.ver_lectura_url}" class="dropdown-item align-items-start text-wrap py-2 bg-primary bg-opacity-20">
					<div class="flex-1">
						<span class="fw-semibold">${ dispositivo.dev_eui_hex }</span>
						<span class="text-muted float-end fs-sm">${dispositivo.created_at}</span>
						<div class="text-muted">
							${dispositivo.name}
						</div>
					</div>
				</a>
			`;

            contenedorLecturas.prepend(itemLectura);
        }
    </script>


</body>

</html>
