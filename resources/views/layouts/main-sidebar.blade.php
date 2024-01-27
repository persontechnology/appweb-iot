<div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg">

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

                <!-- Main -->
                <li class="nav-item-header pt-0">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Principal</div>
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
                    <a href="{{ route('categoria-gateway.index') }}" class="nav-link {{ Route::is('categoria-gateway.*')?'active':'' }}">
                        <i class="ph ph-presentation-chart"></i>
                        <span>
                            Categoriá de gateway
                        </span>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('categoria-nodo.index') }}" class="nav-link {{ Route::is('categoria-nodo.*')?'active':'' }}" >
                        <i class="ph ph-browsers"></i>
                        <span>
                            Categoriá de nodos
                        </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('gateway.index') }}" class="nav-link {{ Route::is('gateway.*')?'active':'' }}">
                        <i class="ph ph-chart-bar"></i>
                        <span>
                            Gateway
                        </span>
                    </a>
                </li>


                


                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="ph ph-graph"></i>
                        <span>
                            Nodos
                        </span>
                    </a>
                </li>
                
                {{-- <li class="nav-item">
                    <a href="../../../../docs/other_changelog.html" class="nav-link">
                        <i class="ph-list-numbers"></i>
                        <span>Changelog</span>
                        <span class="badge bg-primary align-self-center rounded-pill ms-auto">4.0</span>
                    </a>
                </li> --}}

                <!-- Tables -->
                {{-- <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Tables</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                
                <li class="nav-item nav-item-submenu nav-item-expanded nav-item-open">
                    <a href="#" class="nav-link">
                        <i class="ph-square-half"></i>
                        <span>Data tables</span>
                    </a>
                    <ul class="nav-group-sub collapse show">
                        <li class="nav-item"><a href="datatable_basic.html" class="nav-link active">Basic initialization</a></li>
                        <li class="nav-item"><a href="datatable_styling.html" class="nav-link">Basic styling</a></li>
                        
                    </ul>
                </li> --}}
            
                <!-- /tables -->

            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->
    
</div>