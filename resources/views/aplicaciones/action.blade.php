<div class="dropdown">
    <a href="#" class="text-body dropdown-toggle" data-bs-toggle="dropdown">
        <i class="ph-gear"></i>
    </a>

    <div class="dropdown-menu">
        <a href="{{ route('applicaciones.edit',$app->id) }}" class="dropdown-item">
            <i class="ph ph-pencil-simple me-2"></i>
            Editar
        </a>
        <a href="{{ route('applicaciones.destroy',$app->id) }}" data-msg="{{ $app->name }}" onclick="event.preventDefault(); eliminar(this)" class="dropdown-item">
            <i class="ph ph-trash me-2"></i>
            Eliminar
        </a>
        <a href="{{ route('configuraciones.distancia',$app->id) }}" class="dropdown-item">
            <i class="ph ph-pencil-simple me-2"></i>
            Cofiguración de distancia
        </a>
    </div>
</div>