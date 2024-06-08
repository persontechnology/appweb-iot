{{-- sidebar-main-resized : aplicamos esa clase soslo pa el dashborad para que se minimise --}}


<div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg @hasSection('secondary-sidebar') sidebar-main-resized @endif">

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- Sidebar header -->
        <div class="sidebar-section">
            <div class="sidebar-section-body d-flex justify-content-center">
                <h5 class="sidebar-resize-hide flex-grow-1 my-auto">Navegación</h5>

                <div>
                    <button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
                        <i class="ph-arrows-left-right"></i>
                    </button>

                    <button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-mobile-main-toggle d-lg-none">
                        <i class="ph-x"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- /sidebar header -->


        <!-- Main navigation -->
        <div class="sidebar-section">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                {{-- ROLE: ADMINISTRADOR --}}


                @role('ADMINISTRADOR')
                    
                 <!-- Main -->
                 <li class="nav-item-header pt-0">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">ADMINISTRADOR</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>

                <li class="nav-item">
                    <a href="{{ route('clientes.index') }}" class="nav-link {{ Route::is('clientes.*')?'active':'' }}">
                        <i class="ph ph-users"></i>
                        <span>
                            Clientes
                        </span>
                    </a>
                </li>



                <li class="nav-item">
                    <a href="{{ route('inquilinos.index') }}" class="nav-link {{ Route::is('inquilinos.*')?'active':'' }}">
                        <i class="ph ph-hard-drives"></i>
                        <span>
                            Inquilinos
                        </span>
                    </a>
                </li>

                

                @endrole

                <li class="nav-item-header pt-0">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Menu</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ Route::is('dashboard')?'active':'' }}">
                        <i class="ph-house"></i>
                        <span>
                            Dashboard
                        </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('usuarios.index') }}" class="nav-link {{ Route::is('usuarios.*')?'active':'' }}">
                        <i class="ph ph-users"></i>
                        <span>
                            Usuarios
                        </span>
                    </a>
                </li>



                <li class="nav-item">
                    <a href="{{ route('perfil-dispositivos.index') }}" class="nav-link {{ Route::is('perfil-dispositivos.*')?'active':'' }}">
                        <i class="ph ph-grid-four"></i>
                        <span>
                            Perfil de dispositivo
                        </span>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('gateways.index') }}" class="nav-link {{ Route::is('gateways.*')?'active':'' }}">
                        <i class="ph ph-chart-bar"></i>
                        <span>
                            Gateway
                        </span>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('applicaciones.index') }}" class="nav-link {{ Route::is('applicaciones.*')?'active':'' }}">
                        <i class="ph ph-app-window"></i>
                        <span>
                            Applicaciones
                        </span>
                    </a>
                </li>



                <li class="nav-item">
                    <a href="{{ route('dispositivos.index') }}" class="nav-link {{ Route::is('dispositivos.*')?'active':'' }}">
                        <i class="ph ph-device-mobile"></i>
                        <span>
                            Dispositivos
                        </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('alertas.index') }}" class="nav-link {{ Route::is('alertas.*')?'active':'' }}">
                        <i class="ph ph-alarm"></i>
                        <span>
                            Alertas
                        </span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->
    
</div>