<div class="dropdown">
    <a href="#" class="text-body dropdown-toggle" data-bs-toggle="dropdown">
        <i class="ph-gear"></i>
    </a>

    <div class="dropdown-menu">
        
        <a href="{{ route('inquilinos.clientes',$tena->id) }}" class="dropdown-item">
            <i class="ph ph-users me-2"></i>
            Clientes
        </a>

        <a href="{{ route('inquilinos.edit',$tena->id) }}" class="dropdown-item">
            <i class="ph ph-pencil-simple me-2"></i>
            Editar
        </a>
        
        <a href="{{ route('inquilinos.destroy',$tena->id) }}" data-msg="{{ $tena->name }}" onclick="event.preventDefault(); eliminar(this)" class="dropdown-item">
            <i class="ph ph-trash me-2"></i>
            Eliminar
        </a>
    </div>
</div>