<div class="offcanvas offcanvas-end" tabindex="-1" id="notifications">
    <div class="offcanvas-header py-0">
        <h5 class="offcanvas-title py-1">Lecturas</h5>
        <button type="button" class="btn btn-light btn-sm btn-icon border-transparent rounded-pill" data-bs-dismiss="offcanvas">
            <i class="ph-x"></i>
        </button>
    </div>

    <div class="offcanvas-body p-0">
        @php
            $lecturas=App\Models\Lectura::latest()->paginate(5);
        @endphp
        {{-- <div class="bg-light fw-medium py-2 px-3">Older notifications</div> --}}
        <div class="p-3 scrolling-pagination" id="contenedor-lecturas">
            
            @foreach ($lecturas as $lectura)
                <div class="d-flex align-items-start mb-3">
                    <div class="flex-fill">
                        <strong class="fw-semibold">{{ $lectura->dev_eui }}</strong> {{ $lectura->descripcion }}
                        <div class="my-2">
                            <a href="#" class="btn btn-success btn-sm me-1">
                                <i class="ph-checks ph-sm me-1"></i>
                                Aprobar
                            </a>
                            <a href="#" class="btn btn-light btn-sm">
                                Reprobar
                            </a>
                        </div>
                        <div class="fs-sm text-muted mt-1">{{ $lectura->created_at }}</div>
                    </div>
                </div>
            @endforeach
            {{ $lecturas->links() }}
        </div>
    </div>
</div>