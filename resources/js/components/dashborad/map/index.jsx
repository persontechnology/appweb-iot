import React, { useEffect, useState } from 'react';
import { MapContainer, Marker, Popup, TileLayer, useMap } from 'react-leaflet';
import CustomMarker from './CustomMarker';
import 'leaflet/dist/leaflet.css';

const ChangeView = ({ center, zoom }) => {
    const map = useMap();

    useEffect(() => {
        if (map && center) {
            const [latitude, longitude] = center; // Asegurándonos de que center es un array de [latitude, longitude]
            const currentCenter = map.getCenter();
            const currentZoom = map.getZoom();
            const distance = map.distance(currentCenter, center); // en metros
            // Verificar que el mapa y la ubicación sean correctas
            if (latitude && longitude) {
                // Determina si se debe actualizar el zoom
                const shouldZoom = currentZoom <= 13;

                // Si la distancia es considerable, usa flyTo con animación
                if (distance > 1000) {
                    map.flyTo(center, shouldZoom ? zoom : currentZoom, {
                        animate: true,
                        duration: 1.5,
                    });
                } else {
                    // Si está cerca: solo panear si no se requiere cambiar el zoom
                    if (shouldZoom) {
                        map.flyTo(center, zoom, {
                            animate: true,
                            duration: 1.2,
                        });
                    } else {
                        map.panTo(center); // Panear a la nueva posición sin cambiar el zoom
                    }
                }
            }
        }
    }, [center, zoom, map]);

    return null;
};

const Map = ({ loading, devices, optionSelected }) => {
    const [selectedPosition, setSelectedPosition] = useState(null);
    const position = [-0.9833, -78.6167];

    const getIconByType = (marker) => {
        return CustomMarker.getMarkerIcon(
            marker?.tipo,
            true,
            marker?.colorNarker ?? '#ff0000',
            40,
            40,
        );
    };

    useEffect(() => {
        if (optionSelected) {
            // Buscar el dispositivo con el dev_eui correspondiente

            const selectedDevice = devices.find(
                (device) =>
                    device?.device.dev_eui === optionSelected?.device?.dev_eui,
            );
            if (selectedDevice && selectedDevice?.position) {
                const newPos = selectedDevice?.position;

                // Establecer la nueva posición y ajustar el zoom
                setSelectedPosition(newPos);
            }
        }
    }, [optionSelected]);

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

                {selectedPosition && (
                    <ChangeView center={selectedPosition} zoom={15} />
                )}

                {devices.map((marker, index) => (
                    <Marker
                        key={index}
                        position={marker.position}
                        icon={getIconByType(marker)}
                        eventHandlers={{
                            click: () => {
                                setSelectedPosition(marker.position);
                            },
                        }}>
                        <Popup>
                            <div>
                                <strong>{marker.tipo}</strong>
                                <br />
                                Lat: {marker.position[0].toFixed(4)}
                                <br />
                                Lng: {marker.position[1].toFixed(4)}
                            </div>
                        </Popup>
                    </Marker>
                ))}
            </MapContainer>
        </div>
    );
};

export default Map;
