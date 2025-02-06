function updatePercentage(lecturasLatest, dispositivo) {
    try {
        let conveerData = lecturasLatest?.data ?? null;
        const tank = document.getElementById("tank");

        tank.innerHTML = "";
        if (conveerData) {
            let conveerDataObject = conveerData.object;
            let distancia = Number(conveerDataObject?.distance ?? 0);
            let configuraciones = dispositivo
                ? dispositivo.configuraciones
                : [];
            const nivelMaximo = dispositivo?.distance ?? 0; // Capacidad máxima del contenedor
            const nivelActual = distancia; // Nivel actual del contenedor
            if (distancia && configuraciones !== null) {
                const porcentajeLlenado = calcularPorcentajeLlenado(
                    nivelActual,
                    nivelMaximo
                );
                const rangoLlenado = determinarRangoLlenado(
                    porcentajeLlenado,
                    configuraciones
                );
                $("#estadoLector").text(rangoLlenado?.descripcion ?? "");
                pintarNivelDeAgua(
                    porcentajeLlenado,
                    rangoLlenado?.color ?? "#000"
                );
            }
        }
    } catch (error) {
        console.log(error);
    }
}

function pintarNivelDeAgua(porcentajeLlenado, color) {
    const tank = document.getElementById("tank");
    const waterLevel = document.createElement("div");
    waterLevel.className = "water-level";
    waterLevel.style.height = `${porcentajeLlenado}%`;
    waterLevel.style.backgroundColor = color;

    // Onda dentro del agua
    const wave = document.createElement("div");
    wave.className = "wave";

    // Añadir el porcentaje como texto en la parte superior del agua
    waterLevel.textContent = `${porcentajeLlenado.toFixed(2)}%`;

    // Agregar la onda y el nivel de agua al tanque
    waterLevel.appendChild(wave);
    tank.appendChild(waterLevel);
}

// Ejemplo de uso
if(porcentajeLlenado){

    pintarNivelDeAgua(porcentajeLlenado, color);
}

const tank = document.getElementById("tank");
const waterLevel = document.createElement("div");
waterLevel.className = "water-level";
waterLevel.style.height = `${porcentajeLlenado}%`;
waterLevel.style.backgroundColor = color;
waterLevel.textContent = `${porcentajeLlenado.toFixed(2)}%`;

tank.appendChild(waterLevel);
//
function calcularPorcentajeLlenado(nivelActual, nivelMaximo) {
    const nivelInvertido = nivelMaximo - nivelActual;
    const porcentajeLlenado = (nivelInvertido / nivelMaximo) * 100;
    return porcentajeLlenado;
}

function determinarRangoLlenado(porcentajeLlenado, niveles) {
    let rango = null;

    for (let i = 0; i < niveles.length; i++) {
        if (porcentajeLlenado <= niveles[i].valor) {
            rango = niveles[i];
            break;
        }
    }

    return rango;
}

//trabaja con la ultima lectura y conviete el porcentaje en color del grafico

// Función para verificar si un dispositivo reportó lecturas en las últimas 24 horas
function reportoEnUltimas24Horas(fechaUltimaLectura) {
    const fechaActual = new Date();
    const fechaComparar = sumarHoras(fechaUltimaLectura, 24);
    return fechaActual < fechaComparar;
}

// Función para sumar horas a una fecha dada
function sumarHoras(fecha, horas) {
    const nuevaFecha = new Date(fecha);
    nuevaFecha.setHours(nuevaFecha.getHours() + horas);
    return nuevaFecha;
}

//Funcion para calcular la diferencia de tiempo
function calcularDiferenciaTiempo(fechaUltimaLectura) {
    const fechaActual = new Date();
    const fechaLectura = new Date(fechaUltimaLectura);
    const diferenciaMs = fechaActual - fechaLectura;

    const minutos = Math.floor(diferenciaMs / 60000);
    const horas = Math.floor(minutos / 60);
    const dias = Math.floor(horas / 24);

    if (dias > 0) {
        return `hace ${dias} ${dias === 1 ? "día" : "días"}`;
    } else if (horas > 0) {
        return `hace ${horas} ${horas === 1 ? "hora" : "horas"}`;
    } else if (minutos > 0) {
        return `hace ${minutos} ${minutos === 1 ? "minuto" : "minutos"}`;
    } else {
        return "hace unos momentos";
    }
}

function estadoDispositivo(lecturasLatest) {
    if (lecturasLatest) {
        const fechaUltimaLectura = lecturasLatest?.created_at; // Fecha de la última lectura en formato ISO 8601
        const resultado = reportoEnUltimas24Horas(fechaUltimaLectura);
        if (resultado) {
            return `<span class="bg-success bg-opacity-50 text-success lh-1 rounded-pill p-1" style="w">
                    <i class="ph ph-bell"></i>
                </span>`;
        } else {
            return `<span class="bg-danger bg-opacity-50 text-danger lh-1 rounded-pill p-1">
                    <i class="ph ph-bell"></i>
                </span>`;
        }
    } else {
        return `<span class="bg-dark bg-opacity-50 text-dark lh-1 rounded-pill p-1">
                    <i class="ph ph-bell"></i>
                </span>`;
    }
}

function estadoBadgetDispositivo(lecturasLatest, fontSize = null) {
    if (lecturasLatest) {
        const fechaUltimaLectura = lecturasLatest?.created_at; // Fecha de la última lectura en formato ISO 8601
        const resultado = reportoEnUltimas24Horas(fechaUltimaLectura);
        if (resultado) {
            return `<span class="badge bg-success bg-opacity-10 text-success" style="font-size:${
                fontSize ? fontSize : "12"
            }px;">Conectado</span>`;
        } else {
            return `<span class="badge bg-danger bg-opacity-10 text-danger" style="font-size:${
                fontSize ? fontSize : "12"
            }px;">Desconectado</span>`;
        }
    } else {
        return `<span class="badge bg-dark bg-opacity-10 text-dark" style="font-size:${
            fontSize ? fontSize : "12"
        }px;">Sin registros</span>`;
    }
}
