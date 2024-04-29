<div class="dropdown">
    <a href="#" class="text-body dropdown-toggle" data-bs-toggle="dropdown">
        <i class="ph-gear"></i>
    </a>

    <div class="dropdown-menu">
        <a href="{{ route('alertas.show',$al->id) }}" class="dropdown-item">
            <i class="ph ph-calendar me-2"></i>
            Horario
        </a>
        <a href="{{ route('alertas.destroy',$al->id) }}" data-msg="{{ $al->nombre }}" onclick="event.preventDefault(); eliminar(this)" class="dropdown-item">
            <i class="ph ph-trash me-2"></i>
            Eliminar
        </a>
    </div>
</div>