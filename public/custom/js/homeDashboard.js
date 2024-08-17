//trabaja con la ultima lectura y conviete el porcentaje en color del grafico

function updatePercentage(lecturasLatest, configuraciones) {
    try {
        let conveerData = lecturasLatest?.data ?? null;
        if (conveerData) {
            let conveerDataObject = conveerData.object;
            let distancia = Number(conveerDataObject?.distance ?? 0);

            if (
                distancia &&
                configuraciones !== null &&
                configuraciones.length > 0
            ) {
                const maxValue = Math.max(
                    ...configuraciones.map((item) => item.valor)
                );
                const minValue = Math.min(
                    ...configuraciones.map((item) => item.valor)
                );
                const levelContainer =
                    document.getElementById("level-container");

                levelContainer.innerHTML = "";

                var heightTotal = 0;
                for (let i = 0; i < configuraciones.length; i++) {
                    const currentValue = configuraciones[i].valor;
                    const nextValue =
                        i < configuraciones.length - 1
                            ? configuraciones[i + 1].valor
                            : 0;

                    if (distancia <= nextValue && distancia >= currentValue) {
                        const heightPercentage =
                            ((currentValue - nextValue) /
                                (maxValue - minValue)) *
                            100;
                        const section = document.createElement("div");
                        section.className = "liquid-section";
                        section.style.height = `100%`;
                        section.style.backgroundColor = hexToRgba(
                            configuraciones[i].color,
                            0.5
                        );
                        section.textContent = configuraciones[i].descripcion;
                        section.style.color = hexToRgba(
                            configuraciones[i].color,
                            0.5
                        );
                        const percentageText = document.createElement("span");
                        percentageText.className = "percentage-text";
                        let cantidadPositiva = Math.abs(heightPercentage);
                        percentageText.textContent = `${distancia??'0'}mm`;

                        section.appendChild(percentageText);
                        levelContainer.appendChild(section);
                        $("#estadoLector").text(
                            configuraciones[i]?.descripcion ?? ""
                        );
                        break;
                    } else {
                        const heightPercentage =
                            ((currentValue - nextValue) /
                                (maxValue - minValue)) *
                            100;
                        const section = document.createElement("div");
                        section.className = "liquid-section";
                        heightTotal = heightTotal + heightPercentage;
                        section.style.height = `${heightPercentage}%`;
                        section.textContent = configuraciones[i].descripcion;
                        section.style.backgroundColor = "transparent";
                        section.style.textColor = "transparent";
                        levelContainer.appendChild(section);
                    }
                }
            }
        }
    } catch (error) {
        console.log(error);
    }
}

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
