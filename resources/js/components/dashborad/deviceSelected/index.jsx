import React from 'react';
import { Card, CardBody, Col, Row } from 'reactstrap';
const DeviceSelected = ({ loading, deviceData, closeDeviceSelected }) => {
    return (
        <Card className="btn-to-top1 btn-to-top-visible1">
            <CardBody>
                <button
                    className="close-btn"
                    onClick={() => {
                        closeDeviceSelected(true);
                    }}
                    style={{
                        position: 'absolute',
                        top: '10px',
                        right: '10px',
                        background: 'transparent',
                        border: 'none',
                        fontSize: '20px',
                        cursor: 'pointer',
                    }}>
                    &times; {/* La "X" */}
                </button>
                {/* Row de Reactstrap */}
                <Row>
                    {/* Columna 1: Información básica */}
                    <Col md="4" className="device-column">
                        <h6>Información Básica</h6>
                        <table className="table table-bordered table-sm">
                            <tbody>
                                <tr>
                                    <td className="table-cell">
                                        <strong>Nombre:</strong>
                                    </td>
                                    <td className="table-cell">
                                        {deviceData.name}
                                    </td>
                                </tr>
                                <tr>
                                    <td className="table-cell">
                                        <strong>Descripción:</strong>
                                    </td>
                                    <td className="table-cell">
                                        {deviceData.description}
                                    </td>
                                </tr>
                                <tr>
                                    <td className="table-cell">
                                        <strong>Tipo de Dispositivo:</strong>
                                    </td>
                                    <td className="table-cell">
                                        {deviceData.deviceprofile?.name}
                                    </td>
                                </tr>
                                <tr>
                                    <td className="table-cell">
                                        <strong>Última actualización:</strong>
                                    </td>
                                    <td className="table-cell">
                                        {new Date(
                                            deviceData.updated_at,
                                        ).toLocaleString()}
                                    </td>
                                </tr>
                                <tr>
                                    <td className="table-cell">
                                        <strong>Estado:</strong>
                                    </td>
                                    <td className="table-cell">
                                        {deviceData.is_disabled
                                            ? 'Desactivado'
                                            : 'Activo'}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </Col>

                    {/* Columna 2: Información de lecturas_latest */}
                    <Col md="4" className="device-column">
                        <h6>Lecturas Recientes</h6>
                        <table className="table table-bordered table-sm">
                            <tbody>
                                {deviceData.lecturas_latest ? (
                                    <>
                                        <tr>
                                            <td className="table-cell">
                                                <strong>Temperatura:</strong>
                                            </td>
                                            <td className="table-cell">
                                                {deviceData.lecturas_latest
                                                    .temperatura ||
                                                    'No disponible'}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td className="table-cell">
                                                <strong>Humedad:</strong>
                                            </td>
                                            <td className="table-cell">
                                                {deviceData.lecturas_latest
                                                    .humedad || 'No disponible'}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td className="table-cell">
                                                <strong>Presión:</strong>
                                            </td>
                                            <td className="table-cell">
                                                {deviceData.lecturas_latest
                                                    .presion || 'No disponible'}
                                            </td>
                                        </tr>
                                    </>
                                ) : (
                                    <tr>
                                        <td colSpan="2" className="table-cell">
                                            No hay datos de lecturas recientes
                                            disponibles.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </Col>

                    {/* Columna 3: Información adicional */}
                    <Col md="4" className="device-column">
                        <h6>Información Adicional</h6>
                        <table className="table table-bordered table-sm">
                            <tbody>
                                <tr>
                                    <td className="table-cell">
                                        <strong>Fuente de energía:</strong>
                                    </td>
                                    <td className="table-cell">
                                        {deviceData.external_power_source
                                            ? 'Externa'
                                            : 'Interna'}
                                    </td>
                                </tr>
                                <tr>
                                    <td className="table-cell">
                                        <strong>Uso de seguimiento:</strong>
                                    </td>
                                    <td className="table-cell">
                                        {deviceData.use_tracking ? 'Sí' : 'No'}
                                    </td>
                                </tr>
                                <tr>
                                    <td className="table-cell">
                                        <strong>Aplicación:</strong>
                                    </td>
                                    <td className="table-cell">
                                        {deviceData.application?.name}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </Col>
                </Row>
            </CardBody>
        </Card>
    );
};
export default DeviceSelected;
