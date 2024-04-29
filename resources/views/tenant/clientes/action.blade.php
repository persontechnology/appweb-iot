<div class="dropdown">
    <a href="#" class="text-body dropdown-toggle" data-bs-toggle="dropdown">
        <i class="ph-gear"></i>
    </a>

    <div class="dropdown-menu">
        
        <a href="{{ route('inquilinos.clientes.eliminar',[$tu->tenant_id,$tu->user_id]) }}" data-msg="{{ $tu->user->email }}" onclick="event.preventDefault(); eliminar(this)" class="dropdown-item">
            <i class="ph ph-trash me-2"></i>
            Eliminar
        </a> 
    </div>
</div>