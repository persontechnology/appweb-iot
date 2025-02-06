import axios from "axios";
import React, { useEffect, useMemo, useState } from "react"; // Asegúrate de importar StrictMode desde React
import { Menu, MenuItem, Sidebar, SubMenu } from "react-pro-sidebar";
import { Button, Input, Table } from "reactstrap";
import LoadingComponent from "../../loadingComponent";
import ItemTable from "./ItemTable";

const SecondarySidebar = ({ loading, devices }) => {
    // Estado para manejar el colapso de las secciones
    const [isSearchCollapsed, setIsSearchCollapsed] = useState(false);
    const [isNavCollapsed, setIsNavCollapsed] = useState(false);
    // Estado para manejar el término de búsqueda
    const [searchTerm, setSearchTerm] = useState("");

    // Función para manejar el cambio en el input de búsqueda
    const handleSearchChange = (event) => {
        setSearchTerm(event.target.value);
    };

    // Filtrado de dispositivos basado en el término de búsqueda
    const filteredDevices = useMemo(() => {
        if (!searchTerm) return devices;
        return devices.filter((device) =>
            device.name.toLowerCase().includes(searchTerm.toLowerCase())
        );
    }, [searchTerm, devices]);
    console.log(filteredDevices);

    return (
        <div
            className={`sidebar  sidebar-secondary sidebar-expand-lg ${
                isNavCollapsed ? "sidebar-collapsed" : ""
            }`}
        >
            {/* Expand button */}
            <button
                type="button"
                onClick={() => setIsNavCollapsed(!isNavCollapsed)}
                className={`btn btn-sidebar-expand sidebar-control sidebar-secondary-toggle h-100
                }`}
            >
                <i className="ph-caret-right"></i>
            </button>
            {/* /expand button */}
            {/* Sidebar content */}
            <div className="sidebar-content sidebarInter">
                {/* Header */}
                <div className="sidebar-section sidebar-section-body d-flex align-items-center pb-0">
                    <h5 className="mb-0">DISPOSITIVOS</h5>
                    <div className="ms-auto">
                        <button
                            type="button"
                            onClick={() => setIsNavCollapsed(!isNavCollapsed)}
                            className="btn btn-light border-transparent btn-icon rounded-pill btn-sm sidebar-control sidebar-secondary-toggle d-none d-lg-inline-flex"
                        >
                            <i className="ph-arrows-left-right"></i>
                        </button>

                        <button
                            type="button"
                            className="btn btn-light border-transparent btn-icon rounded-pill btn-sm sidebar-mobile-secondary-toggle d-lg-none"
                        >
                            <i className="ph-x"></i>
                        </button>
                    </div>
                </div>
                {/* /header */}

                {/* Sidebar search */}
                <div className="sidebar-section">
                    <div id="sidebar_secondary_search">
                        <div className="sidebar-section-body">
                            <div className="form-control-feedback form-control-feedback-end">
                                <Input
                                    type="search"
                                    value={searchTerm}
                                    onChange={handleSearchChange}
                                    placeholder="Buscar dispositivo..."
                                />
                                <div className="form-control-feedback-icon">
                                    <i className="ph-magnifying-glass opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {/* /sidebar search */}

                {/* Sub navigation */}
                <div className="p-2 pt-0 pr-4">
                    {filteredDevices && filteredDevices.length > 0 ? (
                        <Table>
                            <tbody>
                                {filteredDevices.map((device) => (
                                    <ItemTable key={`item-${device?.dev_eui}`} item={device} />
                                ))}
                            </tbody>
                        </Table>
                    ) : (
                        <p>no existe</p>
                    )}
                </div>
                {/* /sub navigation */}
            </div>
            {/* /sidebar content */}
        </div>
    );
};

export default React.memo(SecondarySidebar);
