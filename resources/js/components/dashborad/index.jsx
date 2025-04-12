import { useEffect, useState } from 'react'; // Asegúrate de importar StrictMode desde React
import axios from 'axios';
import Map from './map';
import SecondarySidebar from './SecondarySidebar';
import ModalReadings from './modalReading';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js'; // Importar Pusher
import DeviceSelected from './deviceSelected';
import { prepareDevices } from '../../helpers/prepareDevices';
const Dashboard = () => {
    const [deviceData, setDeviceData] = useState({
        devices: [],
        devicesSelected: [],
        loader: false,
    });
    const [deviceDataSelected, setDeviceDataSelected] = useState({
        device: null,
        view: false,
    });
    const [modalReadings, setModalReadings] = useState({
        open: false,
        device: null,
    });
    async function cargarDispositivos() {
        try {
            // Mostrar el botón de carga (si se usa en React, podríamos manejar un estado)
            setDeviceData((prevState) => ({
                ...prevState,
                loader: true,
            }));

            // Hacer la petición con Axios
            const response = await axios.get('/buscar-dispositivos');

            // Actualizar el estado con los datos obtenidos
            setDeviceData((prevState) => ({
                ...prevState,
                devices: response?.data ?? [],
                devicesSelected:
                    response?.data?.map((device) => device?.dev_eui) ?? [],
                loader: false,
            }));
        } catch (error) {
            console.error(`Error al cargar dispositivos: ${error}`);
            // Manejo de errores: establecer el estado de carga en false en caso de error
            setDeviceData((prevState) => ({
                ...prevState,
                loader: false,
            }));
        }
    }
    const changeDevicesSelected = (target) => {
        if (target) {
            setDeviceData((prevState) => ({
                ...prevState,
                devicesSelected:
                    deviceData?.devices?.map((device) => device?.dev_eui) ?? [],
            }));
        } else {
            setDeviceData((prevState) => ({
                ...prevState,
                devicesSelected: [],
            }));
        }
        setDeviceDataSelected({
            device: null,
            view: false,
        });
    };
    const changeDeviceSelected = (target, device) => {
        if (target) {
            setDeviceData((prevState) => ({
                ...prevState,
                devicesSelected: [
                    ...prevState.devicesSelected,
                    device?.dev_eui,
                ],
            }));
            setDeviceDataSelected({
                device: device,
                view: true,
            });
        } else {
            let filteredDevices = deviceData.devicesSelected.filter(
                (item) => item !== device?.dev_eui,
            );
            setDeviceData((prevState) => ({
                ...prevState,
                devicesSelected: filteredDevices ?? [],
            }));
            setDeviceDataSelected({
                device: null,
                view: false,
            });
        }
    };

    // Función para manejar la suscripción a Laravel Echo
    const subscribeToDeviceChannel = () => {
        const echo = new Echo({
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            forceTLS: true,
        });

        // Suscripción al canal de dispositivos
        echo.channel('canal-lectura-notificar-dispositivo')
            .subscribed(() => {
                console.log(
                    '✔️ Suscripción exitosa al canal "canal-notificar-dispositivo"',
                );
            })
            .listen('.NotificarLecturaDispositivoEvento', (e) => {
                if (!e.dispositivo) {
                    console.log('⚠️ No se ha recibido el dispositivo.');
                    return;
                }

                // Verifica si el dispositivo pertenece al tenant actual
                const dispositivo = e.dispositivo;
                const tenant_id = window.Laravel.tenant_id;
                console.log(dispositivo);

                if (tenant_id === dispositivo.tenant_id) {
                    console.log('✅ Dispositivo pertenece al tenant actual.');

                    // Actualiza el estado con el dispositivo recibido
                    setDeviceData((prevState) => ({
                        ...prevState,
                        devices: [dispositivo, ...prevState.devices], // Añadir el dispositivo al principio de la lista
                    }));
                    cargarDispositivos();
                } else {
                    console.log('⚠️ Dispositivo no pertenece a este tenant.');
                }
            })
            .error((err) => {
                console.log('❌ Error al suscribirse al canal:', err);
            });
    };

    useEffect(() => {
        // Cargar los dispositivos al montar el componente
        cargarDispositivos();

        // Suscribirse al canal de Echo
        subscribeToDeviceChannel();

        // Limpiar la suscripción al desmontar el componente
        return () => {
            if (window.Echo) {
                window.Echo.disconnect();
                console.log('🚫 Desconectado de Echo');
            }
        };
    }, []); // El array vacío asegura que esto se ejecute solo al montar y desmontar el componente

    const handleOptionsSelected = (type, device) => {
        switch (type) {
            case 'Reading':
                setModalReadings({
                    open: true,
                    device: device,
                });
                break;
            case 'SELECTEDDEVICE':
                setDeviceDataSelected({
                    device: device,
                    view: true,
                });
                break;

            default:
                break;
        }
    };
    const loadDeriver = () => {
        cargarDispositivos();
    };
    const filteredDevices = deviceData.devices.filter((item) =>
        deviceData.devicesSelected.includes(item.dev_eui),
    );

    const devicesForMap = prepareDevices(filteredDevices);

    return (
        <div className="App ashboardd2">
            <SecondarySidebar
                loading={deviceData.loader}
                devices={deviceData.devices ?? []}
                devicesSelected={deviceData.devicesSelected ?? []}
                handleOptionsSelected={handleOptionsSelected.bind(this)}
                changeDevicesSelected={changeDevicesSelected.bind(this)}
                changeDeviceSelected={changeDeviceSelected.bind(this)}
                optionSelected={deviceDataSelected}
                cargarDispositivos={loadDeriver.bind(this)}
            />

            <div className="content p-1" style={{ position: 'relative' }}>
                <Map
                    devices={devicesForMap}
                    optionSelected={deviceDataSelected}
                />
                {deviceDataSelected.view && (
                    <DeviceSelected
                        loading={false}
                        deviceData={deviceDataSelected.device}
                        closeDeviceSelected={() => {
                            setDeviceDataSelected({
                                device: null,
                                view: false,
                            });
                        }}
                    />
                )}
            </div>
            {modalReadings.open && (
                <ModalReadings
                    open={modalReadings.open}
                    device={modalReadings.device}
                    closeModal={() => {
                        setModalReadings({
                            open: false,
                            device: null,
                        });
                    }}
                />
            )}
        </div>
    );
};

export default Dashboard;
