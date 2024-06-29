
<div class="d-inline-flex">
    
    <a href="{{ route('alertas.configuracion',['id'=>$al->id,'op'=>'inicio']) }}" class="text-body mx-2">
        <i class="ph ph-arrow-right"></i>
    </a>
    <div class="dropdown">
        <a href="#" class="text-body dropdown-toggle" data-bs-toggle="dropdown">
            <i class="ph-gear"></i>
        </a>

        <div class="dropdown-menu">
            
            <a href="{{ route('alertas.destroy',$al->id) }}" data-msg="{{ $al->nombre }}" onclick="event.preventDefault(); eliminar(this)" class="dropdown-item">

        
            
                <i class="ph ph-trash me-2"></i>
                Eliminar
            </a>
            
        </div>
    </div>
</div>