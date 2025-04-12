// utils/prepareDevices.js
export const prepareDevices = (devices, offset = 0.0006) => {
    const markers = [];

    devices.forEach((element) => {
        let latitude, longitude;
        let tipo = element?.name;
        let colorNarker = '#828282';
        let deviceDetails = element; // Guardamos el dispositivo completo

        // Obtener la ubicación si está disponible
        if (element?.lecturas_latest?.data?.rxInfo?.[0]?.location) {
            latitude = element.lecturas_latest.data.rxInfo[0].location.latitude;
            longitude =
                element.lecturas_latest.data.rxInfo[0].location.longitude;
        }

        // Si no hay ubicación, usa las coordenadas por defecto
        if (latitude == null || longitude == null) {
            latitude = element?.latitude;
            longitude = element?.longitude;
        }

        if (
            latitude != null &&
            longitude != null &&
            !isNaN(latitude) &&
            !isNaN(longitude)
        ) {
            const pos = [Number(latitude), Number(longitude)];
            let isClose = false;

            // Comprobar si el marcador está cerca de otro
            markers.forEach((marker) => {
                const [existingLat, existingLng] = marker.position;
                if (
                    Math.abs(existingLat - pos[0]) < offset &&
                    Math.abs(existingLng - pos[1]) < offset
                ) {
                    isClose = true;
                }
            });

            if (isClose) {
                // Si está cerca de otro marcador, desplázalo aleatoriamente
                const angle = Math.random() * 2 * Math.PI;
                pos[0] += offset * Math.cos(angle);
                pos[1] += offset * Math.sin(angle);
            }

            // Cambiar el color según la antigüedad de la lectura
            if (element?.lecturas_latest) {
                const fechaActual = new Date();
                const fechaComparar = sumarHoras(
                    element.lecturas_latest.created_at,
                    24,
                );
                const dentroDe24h = fechaActual < fechaComparar;
                colorNarker = dentroDe24h ? '#009951' : '#FF0000';
            }

            // Agregar el marcador con los datos completos del dispositivo
            markers.push({
                position: pos,
                tipo: tipo,
                colorNarker: colorNarker,
                device: deviceDetails, // Aquí se agrega todo el dispositivo para el Popup
            });
        }
    });

    return markers;
};

// Función auxiliar para sumar horas a una fecha
function sumarHoras(fecha, horas) {
    const nuevaFecha = new Date(fecha);
    nuevaFecha.setHours(nuevaFecha.getHours() + horas);
    return nuevaFecha;
}
