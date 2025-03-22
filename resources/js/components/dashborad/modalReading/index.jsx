import axios from 'axios';
import React, { useState, useEffect } from 'react';
import {
    Button,
    Col,
    Modal,
    ModalBody,
    ModalFooter,
    ModalHeader,
    Row,
    Table,
} from 'reactstrap';
import ModalData from '../../modalData';

const ModalReading = ({ open, device, closeModal }) => {
    const [date, setDate] = useState('');
    const [deviceData, setDeviceData] = useState({
        lecturas: [],
        loader: false,
    });
    const [modalData, setModalData] = useState({
        open: false,
        search: null,
    });

    const [search, setSearch] = useState('');
    const [perPage, setPerPage] = useState(10);
    const [orderData, setOrderData] = useState('asc');
    const [currentPage, setCurrentPage] = useState(1);
    const [lastPage, setLastPage] = useState(1);
    let keysToShow = [];
    if (device?.deviceprofile?.name === 'GPS') {
        keysToShow = [
            {
                label: 'Nombre del Dispositivo',
                value: 'data.deviceInfo.deviceName',
            },
            { label: 'ID del Cliente', value: 'data.deviceInfo.tenantId' },
            {
                label: 'Nombre del Cliente',
                value: 'data.deviceInfo.tenantName',
            },
            {
                label: 'ID de la Aplicación',
                value: 'data.deviceInfo.applicationId',
            },
        ];
    }
    if (device?.deviceprofile?.name === 'Distancia') {
        keysToShow = [
            {
                label: 'Nombre del Dispositivo',
                value: 'data.deviceInfo.deviceName',
            },
            { label: 'Bateria %', value: 'data.object.battery' },
            { label: 'Distacncia mm', value: 'data.object.distance' },
            { label: 'Posición', value: 'data.object.position' },

            {
                label: 'Nombre del Cliente',
                value: 'data.deviceInfo.tenantName',
            },
        ];
    }
    async function cargarLecturas() {
        try {
            // Mostrar el botón de carga (si se usa en React, podríamos manejar un estado)
            setDeviceData((prevState) => ({
                ...prevState,
                loader: true,
            }));

            // Hacer la petición con Axios
            const response = await axios.get(
                device?.dev_eui + '/dispositivo/lecturas',
                {
                    params: {
                        search,
                        page: currentPage,
                        per_page: perPage,
                        orderBy: orderData,
                        date,
                    },
                },
            );
            // Actualizar el estado con los datos obtenidos
            setDeviceData((prevState) => ({
                ...prevState,
                lecturas: response?.data?.data ?? [],
                loader: false,
            }));
            setLastPage(response.data.last_page);
        } catch (error) {
            console.error(`Error al cargar dispositivos: ${error}`);
            // Manejo de errores: establecer el estado de carga en false en caso de error
            setDeviceData((prevState) => ({
                ...prevState,
                loader: false,
            }));
        }
    }

    const getNestedValue = (obj, path) => {
        return path.split('.').reduce((acc, key) => acc?.[key] ?? null, obj);
    };

    useEffect(() => {
        cargarLecturas();
    }, [currentPage, search, perPage, orderData, date]);

    return (
        <Modal size="xl" fullscreen isOpen={open} toggle={closeModal}>
            <ModalHeader toggle={closeModal}>
                Detalle de lecturas de dsipositivo {device?.name} tipo{' '}
                {device?.dev_eui}{' '}
            </ModalHeader>
            <ModalBody>
                <div className=" mt-1">
                    <Row>
                        <Col md={6}>
                            <div className="d-flex justify-content-between">
                                <input
                                    type="date"
                                    className="form-control mr-2"
                                    value={date}
                                    onChange={(e) => {
                                        setDate(e.target.value);
                                        onDateChange(e.target.value);
                                    }}
                                />
                                <Button
                                    className="ml-2 pl-3 pr-3 m-0"
                                    disabled={!date}
                                    variant="secondary"
                                    size="sm"
                                    onClick={() => {
                                        setDate('');
                                        onClear();
                                    }}>
                                    Limpiar
                                </Button>
                            </div>
                        </Col>
                        <Col md={2}>
                            <select
                                className="form-select mb-3"
                                value={perPage}
                                onChange={(e) =>
                                    setPerPage(Number(e.target.value))
                                }>
                                <option value="5">5 por página</option>
                                <option value="10">10 por página</option>
                                <option value="20">20 por página</option>
                            </select>
                        </Col>
                        <Col md={2}>
                            <select
                                className="form-select mb-3"
                                value={orderData}
                                onChange={(e) => setOrderData(e.target.value)}>
                                <option value="asc">Orden ascendente</option>
                                <option value="desc">Orden descendente</option>
                            </select>
                        </Col>
                        <Col md={2}>
                            <Button
                                className="ml-2 pl-3 pr-3 m-0 float-end"
                                variant="primary"
                                size="sm"
                                onClick={() => {
                                    setModalData({ open: true, search });
                                }}>
                                Buscar en todos los registros
                            </Button>
                        </Col>
                    </Row>

                    <Table className="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                {keysToShow.map(({ label, value }) => (
                                    <th key={value}>{label}</th>
                                ))}
                            </tr>
                        </thead>
                        {deviceData && deviceData?.lecturas.length > 0 ? (
                            <tbody>
                                {deviceData?.lecturas.map((lectura) => (
                                    <tr key={lectura.id}>
                                        <td>{lectura.id}</td>
                                        <td>
                                            {new Date(
                                                lectura.created_at,
                                            ).toLocaleString()}
                                        </td>
                                        {keysToShow.map(({ label, value }) => {
                                            const dataValue = getNestedValue(
                                                lectura,
                                                value,
                                            ); // Accede al valor
                                            return dataValue !== null ? ( // Si existe, mostrarlo
                                                <td>{dataValue}</td>
                                            ) : (
                                                <td>{dataValue}</td>
                                            ); // No renderiza si el dato es null
                                        })}
                                    </tr>
                                ))}
                            </tbody>
                        ) : (
                            <tbody>
                                <tr>
                                    <td
                                        className="text-center"
                                        colSpan={Number(keysToShow.length) + 2}>
                                        no existe datos
                                    </td>
                                </tr>
                            </tbody>
                        )}
                    </Table>

                    {/* Botones de paginación */}
                    <div>
                        <button
                            className="btn btn-primary me-2"
                            onClick={() =>
                                setCurrentPage((prev) => Math.max(prev - 1, 1))
                            }
                            disabled={currentPage === 1}>
                            Anterior
                        </button>
                        <span>
                            Página {currentPage} de {lastPage}
                        </span>
                        <button
                            className="btn btn-primary ms-2"
                            onClick={() =>
                                setCurrentPage((prev) =>
                                    Math.min(prev + 1, lastPage),
                                )
                            }
                            disabled={currentPage === lastPage}>
                            Siguiente
                        </button>
                    </div>
                </div>
            </ModalBody>
            <ModalFooter>
                <Button
                    color="secondary"
                    onClick={() => {
                        closeModal();
                    }}>
                    Cancel
                </Button>
            </ModalFooter>
            <ModalData
                open={modalData.open}
                search={device?.dev_eui ?? ''}
                closeModal={() => {
                    setModalData({ open: false, search: search });
                }}
            />
        </Modal>
    );
};

export default ModalReading;
