
<div class="d-inline-flex">
    <div class="form-check">
        <input type="checkbox"  name="usuarios[]" value="{{ $tu->user_id }}" class="form-check-input" id="{{ $tu->user_id }}"  
        
        @if((is_array(old('usuarios')) && in_array($tu->user_id, old('usuarios'))) || $tu->user->tieneAlerta($alertaId)) checked @endif>
  
    </div>

    @if($tu->user->tieneAlerta($alertaId))
        <a href="{{ route('alertas.eliminarUsuario',['alertaId'=>$alertaId,'userId'=>$tu->user_id]) }}" class="text-body" data-msg="{{ $tu->user->email }}" onclick="event.preventDefault(); eliminar(this)" data-bs-popup="tooltip" title="Quitar" data-bs-placement="bottom">
            <i class="ph ph-prohibit"></i>
        </a>    
        
    @endif   

</div>