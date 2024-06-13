@php
$dispositivo=$lectura->dipositivoXlecturaId($lectura->id);
@endphp

<div class="table-responsive mt-2">
    <table class="table table-bordered">
        <thead>
            <tr class="text-center">
                <th scope="col" colspan="11">DETALLE DEL DISPOSITIVO</th>
            </tr>
            <tr>
                <th scope="col">Dev_eui</th>
                <th scope="col">Nombre</th>
                <th scope="col">Descripción</th>
                <th scope="col">Nivel de batería</th>
                <th scope="col">Latitude</th>
                <th scope="col">Longitude</th>
                <th scope="col">Creado el</th>
                <th scope="col">Visto por última vez</th>
                <th scope="col">Está deshabilitado?</th>
                <th scope="col">Join_eui</th>
                <th scope="col">Es dispositivo de trackig?</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $dispositivo->dev_eui_hex }}</td>
                <td>{{ $dispositivo->name }}</td>
                <td>{{ $dispositivo->description }}</td>
                <td>{{ $dispositivo->battery_level }}</td>
                <td>{{ $dispositivo->latitude }}</td>
                <td>{{ $dispositivo->longitude }}</td>
                <td>{{ $dispositivo->created_at }}</td>
                <td>{{ $dispositivo->last_seen_at }}</td>
                <td>{{ $dispositivo->is_disabled }}</td>
                <td>{{ $dispositivo->join_eui }}</td>
                <td>{{ $dispositivo->use_tracking }}</td>
            </tr>
        </tbody>
    </table>
</div>

<br>

<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr class="text-center">
                <th colspan="7">DETALLE DE LECTURA</th>
            </tr>
            <tr>
                <th scope="col">Creado el</th>
                
                {{-- <th scope="col">Data</th> --}}
                <th scope="col">Lat</th>
                <th scope="col">Long</th>
                <th scope="col">Dev_eui</th>
                <th scope="col">Nombre</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $lectura->created_at }}</td>
                
                {{-- <td>{{ json_encode($lectura->data) }}</td> --}}
                <td>{{ $lectura->lat??'NA' }}</td>
                <td>{{ $lectura->long??'NA' }}</td>
                <td>
                    {{ $dispositivo->dev_eui_hex??'' }}

                </td>
                <td>{{ $lectura->dipositivoXlecturaId($lectura->id)->name }}</td>
            </tr>
        </tbody>
    </table>
</div>

<br>
<div class="table-responsive">
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Atributo</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lectura->data as $key => $value)
                <tr class="{{ $key=='object'?'bg-primary bg-opacity-20':'' }}" >
                    <td>{{ $key }}</td>
                    <td>
                        @if(is_array($value) || is_object($value))
                            <pre>{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                        @else
                            {{ $value }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>