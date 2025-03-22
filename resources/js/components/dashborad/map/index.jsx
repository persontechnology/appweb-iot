import React, { useState } from 'react'; // Asegúrate de importar StrictMode desde React
import { MapContainer, Marker, Popup, TileLayer } from 'react-leaflet';
import CustomMarker from './CustomMarker';
import 'leaflet/dist/leaflet.css';

const Map = ({ loading, devices }) => {
    const [collapsed, setCollapsed] = useState(false);
    const [search, setSearch] = useState('');
    const position = [-0.9833, -78.6167];
    // Coordenadas de los puntos a marcar
    let newMarkers = [];
    const offset = 0.0006;
    if (devices) {
        devices.forEach((element) => {
            let latitude, longitude;
            let tipo = element?.deviceprofile?.name;
            let colorNarker = '#828282'; // Por defecto color gris

            // Verificar si tiene lecturas más recientes
            if (element?.lecturas_latest) {
                switch (element?.deviceprofile?.name) {
                    case 'GPS':
                        break;
                    case 'Distancia':
                        break;
                    case 'Button':
                        let lecturaLatest = element?.lecturas_latest?.data;
                        if (
                            lecturaLatest &&
                            lecturaLatest.rxInfo &&
                            lecturaLatest.rxInfo.length > 0
                        ) {
                            if (
                                lecturaLatest.rxInfo[0] &&
                                lecturaLatest.rxInfo[0].location
                            ) {
                                latitude =
                                    lecturaLatest.rxInfo[0].location.latitude;
                                longitude =
                                    lecturaLatest.rxInfo[0].location.longitude;
                            }
                        }
                        break;

                    default:
                        break;
                }
            }

            // Si no tiene lecturas recientes, usar las coordenadas generales
            if (!latitude || longitude === null) {
                latitude = element?.latitude;
                longitude = element?.longitude;
            }

            // Si tiene coordenadas válidas, agregar el marcador
            if (latitude && longitude) {
                // Asegurarse de que las coordenadas estén como números
                const pos = [Number(latitude), Number(longitude)];
                // Verificar si el marcador ya está cerca de otros
                let isClose = false;
                newMarkers.forEach((marker) => {
                    const [existingLat, existingLng] = marker.position;
                    // Si las coordenadas son muy cercanas (dentro de 0.0001), aplicar un desplazamiento
                    if (
                        Math.abs(existingLat - pos[0]) < offset &&
                        Math.abs(existingLng - pos[1]) < offset
                    ) {
                        isClose = true;
                    }
                });

                // Si el marcador está cerca de otros, aplicar un pequeño desplazamiento
                if (isClose) {
                    pos[0] += offset; // Desplazar en latitud
                    pos[1] += offset; // Desplazar en longitud
                }

                // Determinar el color del marcador según el tipo de dispositivo
                if (element?.lecturas_latest) {
                    const fechaActual = new Date();
                    const fechaComparar = sumarHoras(
                        element?.lecturas_latest.created_at,
                        24,
                    );
                    let dsd = fechaActual < fechaComparar;
                    if (!dsd) {
                        colorNarker = '#FF0000';
                    } else {
                        colorNarker = '#009951';
                    }
                }

                // Agregar el marcador a la lista
                newMarkers.push({
                    position: pos,
                    tipo: tipo,
                    colorNarker: colorNarker,
                });
            }
        });
    }

    function sumarHoras(fecha, horas) {
        const nuevaFecha = new Date(fecha);
        nuevaFecha.setHours(nuevaFecha.getHours() + horas);
        return nuevaFecha;
    }

    const getIconByType = (marker) => {
        let svgCustm = CustomMarker.getMarkerIcon(
            marker?.tipo,
            true,
            marker?.colorNarker ?? '#ff0000',
            40,
            40,
        );
        return svgCustm;
    };
    return (
        <div>
            <MapContainer
                center={position}
                zoom={7}
                style={{ height: '100vh' }}>
                <TileLayer
                    url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                    attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                />
                {/* Renderiza los marcadores dinámicamente */}
                {newMarkers.map((marker, index) => (
                    <Marker
                        key={index}
                        position={marker.position}
                        icon={getIconByType(marker)}>
                        <Popup>{marker.tipo} marcador</Popup>
                    </Marker>
                ))}
            </MapContainer>
        </div>
    );
};

export default Map;
