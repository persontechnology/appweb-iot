<div class="card-header d-sm-flex pt-sm-0 pb-0">

    <p class="align-self-sm-center mb-sm-0">
        <strong>Alerta: </strong> {{ $alerta->nombre }}
    </p>
    <div class="ms-sm-auto">
        <ul class="nav nav-tabs nav-tabs-underline card-header-tabs mb-0">
            <li class="nav-item">
                <a href="{{ route('alertas.configuracion',['id'=>$alerta->id,'op'=>'inicio']) }}" class="nav-link {{ $opcion=='inicio'?'active':''}}">
                    <i class="ph-house me-2"></i>
                    Inicio
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('alertas.configuracion',['id'=>$alerta->id,'op'=>'usuarios']) }}" class="nav-link {{ $opcion=='usuarios'?'active':''}}">
                    <i class="ph ph-users-four me-2"></i>
                    Usuarios
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('alertas.configuracion',['id'=>$alerta->id,'op'=>'horario']) }}" class="nav-link {{ $opcion=='horario'?'active':''}}">
                    <i class="ph ph-calendar me-2"></i>
                    Horario
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('alertas.configuracion',['id'=>$alerta->id,'op'=>'lecturas']) }}" class="nav-link {{ $opcion=='lecturas'?'active':''}}">
                    <i class="ph ph-notebook me-2"></i>
                    Lecturas
                </a>
            </li>

            {{-- <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="ph-gear"></i>
                </a>
                
                <div class="dropdown-menu dropdown-menu-end">
                    <a href="#card-underline-tab3" tabindex="-1" class="dropdown-item">
                        <i class="ph-user me-2"></i>
                        Profile settings
                    </a>
                    <a href="#card-underline-tab4" tabindex="-1" class="dropdown-item">
                        <i class="ph-stack me-2"></i>
                        Layout settings
                    </a>
                </div>
            </li> --}}
        </ul>
    </div>
</div>