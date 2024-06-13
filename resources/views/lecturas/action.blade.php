<div class="dropdown">
    <a href="#" class="text-body dropdown-toggle" data-bs-toggle="dropdown">
        <i class="ph-gear"></i>
    </a>
    <div class="dropdown-menu">
        <a href="{{ route('lecturas.descargarPdf',$lectura->id) }}" class="dropdown-item">
            <i class="ph ph-file-pdf me-2"></i>
            Descargar
        </a>

        <a href="{{ route('lecturas.show',$lectura->id) }}" class="dropdown-item">
            <i class="ph ph-eye me-2"></i>
            Detalle
        </a>

        <a href="{{ route('lecturas.destroy',$lectura->id) }}" data-msg="{{ $lectura->dipositivoXlecturaId($lectura->id)->name }}-{{ $lectura->dipositivoXlecturaId($lectura->id)->dev_eui }}" onclick="event.preventDefault(); eliminar(this)" class="dropdown-item">
            <i class="ph ph-trash me-2"></i>
            Eliminar
        </a>
    </div>
</div>