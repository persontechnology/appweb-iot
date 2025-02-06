import React, { useEffect, useState } from "react"; // Asegúrate de importar StrictMode desde React
import SecondarySidebar from "./SecondarySidebar";
import axios from "axios";
import Map from "./map";

const Dashboard = () => {
    const [deviceData, setDeviceData] = useState({
        divers: [],
        loader: false,
    });

    async function cargarDispositivos() {
        try {
            // Mostrar el botón de carga (si se usa en React, podríamos manejar un estado)
            setDeviceData((prevState) => ({
                ...prevState,
                loader: true,
            }));

            // Hacer la petición con Axios
            const response = await axios.get("/buscar-dispositivos");
            console.log(response);

            // Actualizar el estado con los datos obtenidos
            setDeviceData((prevState) => ({
                ...prevState,
                divers: response?.data ?? [],
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
    console.log(deviceData);
    useEffect(() => {
        cargarDispositivos();
    }, []);
    return (
        <div className="App">
            <SecondarySidebar
                loading={deviceData.loader}
                devices={deviceData.divers ?? []}
            />
            <div className="content p-4">
                <Map />
            </div>
        </div>
    );
};

export default Dashboard;
