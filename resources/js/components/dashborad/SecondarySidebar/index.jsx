import React, { useMemo, useState } from 'react';
import { Col, Input, Row, Table } from 'reactstrap';
import ItemTable from './ItemTable';
import { on } from '../../../../../node_modules/leaflet/src/dom/DomEvent';

const SecondarySidebar = ({
    loading,
    devices,
    devicesSelected,
    changeDevicesSelected,
    changeDeviceSelected,
    handleOptionsSelected,
    optionSelected,
    cargarDispositivos,
}) => {
    const [isNavCollapsed, setIsNavCollapsed] = useState(false);
    const [searchTerm, setSearchTerm] = useState('');

    const handleSearchChange = (event) => {
        setSearchTerm(event.target.value);
    };

    const filteredDevices = useMemo(() => {
        if (!searchTerm) return devices;
        return devices.filter((device) =>
            device.name.toLowerCase().includes(searchTerm.toLowerCase()),
        );
    }, [searchTerm, devices]);

    return (
        <div
            className={`sidebar sidebar-secondary sidebar-expand-lg ${isNavCollapsed ? 'sidebar-collapsed' : ''}`}>
            {/* Expand button */}
            <button
                type="button"
                onClick={() => setIsNavCollapsed(!isNavCollapsed)}
                className="btn btn-sidebar-expand sidebar-control sidebar-secondary-toggle h-100">
                <i className="ph-caret-right"></i>
            </button>

            <div className="sidebar-content sidebarInter">
                {/* Header */}
                <div className="sidebar-section sidebar-section-body d-flex align-items-center pb-0 mb-0">
                    <p className="mb-0 text-small fw-bold m-0 p-0">
                        DISPOSITIVOS
                    </p>
                    <div className="ms-auto m-0 p-0">
                        <button
                            type="button"
                            onClick={() => setIsNavCollapsed(!isNavCollapsed)}
                            className="btn btn-light border-transparent btn-icon rounded-pill btn-xs sidebar-control sidebar-secondary-toggle d-none d-lg-inline-flex">
                            <i className="ph-arrows-left-right"></i>
                        </button>

                        <button
                            type="button"
                            className="btn btn-light border-transparent btn-icon rounded-pill btn-sm sidebar-mobile-secondary-toggle d-lg-none">
                            <i className="ph-x"></i>
                        </button>
                    </div>
                </div>
                {/* /header */}

                {/* Sidebar search */}
                <div className="sidebar-section">
                    <div id="sidebar_secondary_search">
                        <div className="sidebar-section-body p-1 pb-2">
                            <Row>
                                <Col md={8}>
                                    <div className="form-control-feedback form-control-feedback-end">
                                        <Input
                                            bsSize="sm"
                                            type="search"
                                            value={searchTerm}
                                            onChange={handleSearchChange}
                                            placeholder="Buscar dispositivo..."
                                        />
                                        <div className="form-control-feedback-icon">
                                            <i className="ph-magnifying-glass opacity-50"></i>
                                        </div>
                                    </div>
                                </Col>
                                <Col md={4}>
                                    <button
                                        type="button"
                                        onClick={() => {
                                            cargarDispositivos();
                                        }}
                                        title="Actualizar lista"
                                        className="btn btn-primary btn-sm border-transparent btn-icon rounded-pill float-end me-3">
                                        <i className="ph ph-arrow-clockwise"></i>
                                    </button>
                                </Col>
                            </Row>
                        </div>
                    </div>
                </div>
                {/* /sidebar search */}

                {/* Sub navigation */}
                <div className="p-2 pt-0 pr-4">
                    {filteredDevices && filteredDevices.length > 0 ? (
                        <Table>
                            <tbody>
                                <tr className="bg-dark-200">
                                    <td className="align-middle p-0 m-0">
                                        <div className="d-flex align-items-center p-0 m-0">
                                            <div className="d-inline-block me-2">
                                                <Input
                                                    type="checkbox"
                                                    checked={
                                                        devicesSelected.length ===
                                                        filteredDevices.length
                                                    }
                                                    onChange={(e) => {
                                                        changeDevicesSelected(
                                                            e.target.checked,
                                                        );
                                                    }}
                                                />
                                            </div>
                                            <div className="d-inline-block me-2">
                                                <i className="ph-eye"></i>
                                            </div>
                                            <div>
                                                <p className="text-body m-0 p-0 fw-semibold letter-icon-title"></p>
                                                <div className="text-muted fs-xs"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                {filteredDevices.map((device) => (
                                    <ItemTable
                                        key={`item-${device?.dev_eui}`}
                                        item={device}
                                        handleOptionsSelected={
                                            handleOptionsSelected
                                        }
                                        devicesSelected={devicesSelected}
                                        changeDeviceSelected={
                                            changeDeviceSelected
                                        }
                                        optionSelected={optionSelected}
                                    />
                                ))}
                            </tbody>
                        </Table>
                    ) : (
                        <p>No existen dispositivos</p>
                    )}
                </div>
                {/* /sub navigation */}
            </div>
            {/* /sidebar content */}
        </div>
    );
};

export default React.memo(SecondarySidebar);
