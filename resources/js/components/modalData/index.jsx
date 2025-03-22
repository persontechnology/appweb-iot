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

const ModalData = ({ open, search, closeModal }) => {
    const [date, setDate] = useState('');
    const [deviceData, setDeviceData] = useState({
        lecturas: [],
        loader: false,
    });

    const [searchData, setSearchData] = useState(search);
    const [perPage, setPerPage] = useState(10);
    const [orderData, setOrderData] = useState('asc');
    const [currentPage, setCurrentPage] = useState(1);
    const [lastPage, setLastPage] = useState(1);
    let keysToShow = [];

    async function cargarLecturas() {
        try {
            // Mostrar el botón de carga (si se usa en React, podríamos manejar un estado)
            setDeviceData((prevState) => ({
                ...prevState,
                loader: true,
            }));

            // Hacer la petición con Axios
            const response = await axios.get(
                'dispositivos/consultar/lecturas',
                {
                    params: {
                        search: searchData ?? search,
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

    const handleSearch = () => {
        setCurrentPage(1);
        cargarLecturas();
    };
    useEffect(() => {
        cargarLecturas();
    }, [currentPage, perPage, orderData, date]);

    return (
        <Modal size="xl" fullscreen isOpen={open} toggle={closeModal}>
            <ModalHeader toggle={closeModal}>Lecturas registradas</ModalHeader>
            <ModalBody>
                <div className=" mt-1">
                    <Row>
                        <Col md={3}>
                            <input
                                type="text"
                                placeholder="Buscar"
                                className="form-control mr-2"
                                value={searchData}
                                onChange={(e) => setSearchData(e.target.value)} // 🔹 Actualiza el estado
                                onKeyDown={(e) => {
                                    if (e.key === 'Enter') {
                                        handleSearch();
                                    }
                                }}
                                onBlur={() => {
                                    if (searchData === '') {
                                        setCurrentPage(1);
                                        cargarLecturas();
                                    }
                                }}
                            />
                        </Col>
                        <Col md={3}>
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
                                    setDate('');
                                    onClear();
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
                                <th>Datos</th>
                            </tr>
                        </thead>
                        {deviceData && deviceData?.lecturas.length > 0 ? (
                            <tbody>
                                {deviceData?.lecturas.map((lectura) => (
                                    <tr key={`lecturaa_${lectura.id}`}>
                                        <td>{lectura.id}</td>
                                        <td>
                                            {new Date(
                                                lectura.created_at,
                                            ).toLocaleString()}
                                        </td>
                                        <td>
                                            {JSON.stringify(
                                                lectura?.data,
                                                null,
                                                2,
                                            )}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        ) : (
                            <tbody>
                                <tr>
                                    <td className="text-center" colSpan={3}>
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
        </Modal>
    );
};

export default ModalData;
