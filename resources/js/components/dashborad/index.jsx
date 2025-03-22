import { useEffect, useState } from 'react'; // Asegúrate de importar StrictMode desde React
import axios from 'axios';
import Map from './map';
import SecondarySidebar from './SecondarySidebar';
import ModalReadings from './modalReading';
import { Card, CardBody } from 'reactstrap';
import DeviceSelected from './deviceSelected';
const Dashboard = () => {
    const [deviceData, setDeviceData] = useState({
        devices: [],
        devicesSelected: [],
        loader: false,
    });
    const [deviceDataSelected, setDeviceDataSelected] = useState({
        diver: null,
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
                    response?.data?.map((diver) => diver?.dev_eui) ?? [],
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
                    deviceData?.devices?.map((diver) => diver?.dev_eui) ?? [],
            }));
        } else {
            setDeviceData((prevState) => ({
                ...prevState,
                devicesSelected: [],
            }));
        }
    };
    const changeDeviceSelected = (target, diver) => {
        if (target) {
            setDeviceData((prevState) => ({
                ...prevState,
                devicesSelected: [...prevState.devicesSelected, diver?.dev_eui],
            }));
        } else {
            let filteredDevices = deviceData.devicesSelected.filter(
                (item) => item !== diver?.dev_eui,
            );
            setDeviceData((prevState) => ({
                ...prevState,
                devicesSelected: filteredDevices ?? [],
            }));
        }
    };

    const handleOptionsSelected = (type, device) => {
        debugger;
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
        debugger;
        cargarDispositivos();
    };
    useEffect(() => {
        cargarDispositivos();
    }, []);

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
                    devices={
                        deviceData.devices.filter((item) =>
                            deviceData.devicesSelected.includes(item.dev_eui),
                        ) ?? []
                    }
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
