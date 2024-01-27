<div class="dropdown">
    <a href="#" class="text-body dropdown-toggle" data-bs-toggle="dropdown">
        <i class="ph-gear"></i>
    </a>

    <div class="dropdown-menu">
        <a href="{{ route('categoria-gateway.edit',$cg) }}" class="dropdown-item">
            <i class="ph ph-pencil-simple me-2"></i>
            Editar
        </a>
        <a href="{{ route('categoria-gateway.destroy',$cg->id) }}" data-msg="{{ $cg->nombre }}" onclick="event.preventDefault(); eliminar(this)" class="dropdown-item">
            <i class="ph ph-trash me-2"></i>
            Eliminar
        </a>
    </div>
</div>