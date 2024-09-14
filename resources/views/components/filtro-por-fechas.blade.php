<div>
    <select id="filtroFechas" class="form-select form-select-lg mx-2" onchange="obtenerFechaInicioFechaFin(this, '{{ $resultadoId }}');">
        <option value="opt1">AÑO ACTUAL</option>
        <option value="opt2">AÑO ANTERIOR</option>
        <option value="opt3" selected>MES ACTUAL</option> <!-- Mes actual por defecto -->
        <option value="opt4">MES ANTERIOR</option>
        <option value="opt5">SEMANA ACTUAL</option>
        <option value="opt6">SEMANA ANTERIOR</option>
        <option value="opt7">HOY</option>
        <option value="opt8">AYER</option>
        <option value="opt9">RANGO DE FECHAS</option>
    </select>
    <div id="{{ $resultadoId }}"></div>
</div>

<script>
    function obtenerFechaInicioFechaFin(select, resultContainerId) {
        let fechaInicio, fechaFin;
        const opcion = select.value;

        switch (opcion) {
            case 'opt1': // Año actual
                fechaInicio = moment().startOf('year');
                fechaFin = moment().endOf('year');
                break;
            case 'opt2': // Año anterior
                fechaInicio = moment().subtract(1, 'year').startOf('year');
                fechaFin = moment().subtract(1, 'year').endOf('year');
                break;
            case 'opt3': // Mes actual
                fechaInicio = moment().startOf('month');
                fechaFin = moment().endOf('month');
                break;
            case 'opt4': // Mes anterior
                fechaInicio = moment().subtract(1, 'month').startOf('month');
                fechaFin = moment().subtract(1, 'month').endOf('month');
                break;
            case 'opt5': // Semana actual
                fechaInicio = moment().startOf('week');
                fechaFin = moment().endOf('week');
                break;
            case 'opt6': // Semana anterior
                fechaInicio = moment().subtract(1, 'week').startOf('week');
                fechaFin = moment().subtract(1, 'week').endOf('week');
                break;
            case 'opt7': // Hoy
                fechaInicio = moment().startOf('day');
                fechaFin = moment().endOf('day');
                break;
            case 'opt8': // Ayer
                fechaInicio = moment().subtract(1, 'day').startOf('day');
                fechaFin = moment().subtract(1, 'day').endOf('day');
                break;
            case 'opt9': // Rango de fechas personalizado
                // Aquí puedes implementar un selector de rango de fechas si es necesario
                fechaInicio = 'custom-range-start';
                fechaFin = 'custom-range-end';
                break;
            default:
                fechaInicio = fechaFin = null;
        }

        if (fechaInicio && fechaFin) {
            document.getElementById(resultContainerId).innerText = `Fecha de inicio: ${fechaInicio.format('YYYY-MM-DD')}, Fecha de fin: ${fechaFin.format('YYYY-MM-DD')}`;
        } else {
            document.getElementById(resultContainerId).innerText = 'Selecciona una opción válida.';
        }
    }

    // Ejecutar la función al cargar la página con el valor por defecto "Mes actual"
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('filtroFechas');
        obtenerFechaInicioFechaFin(select, '{{ $resultadoId }}');
    });
</script>
