import React, { useState } from 'react';
import {
    Button,
    Dropdown,
    DropdownItem,
    DropdownMenu,
    DropdownToggle,
    Input,
} from 'reactstrap';
import CustomMarker from '../../map/CustomMarker';
import { timeAgo } from '../../../../helpers/generalHelper';
import ModalData from '../../../modalData';
import moment from 'moment';

const ItemTable = ({
    item,
    devicesSelected,
    changeDeviceSelected,
    handleOptionsSelected,
    optionSelected,
}) => {
    const [dropdownOpen, setDropdownOpen] = useState(false);
    const [modalData, setModalData] = useState({
        open: false,
        search: null,
    });
    const toggle = () => setDropdownOpen((prevState) => !prevState);
    const getIconByType = () => {
        let type = item?.deviceprofile?.name;
        let colorNarker = '';

        if (item?.lecturas_latest && item?.lecturas_latest !== null) {
            // validar si la fheca de la ultima lectura es menor a la fecha acual
            const fechaActual = new Date();
            const fechaComparar = sumarHoras(
                item?.lecturas_latest.created_at,
                24,
            );
            let dsd = fechaActual < fechaComparar;
            if (!dsd) {
                colorNarker = '#FF0000';
            } else {
                colorNarker = '#009951';
            }
        } else {
            colorNarker = '#828282';
        }

        let svgCustm = CustomMarker.getMarkerIcon(
            type,
            false,
            colorNarker,
            25,
            25,
        );

        switch (type) {
            case 'GPS':
                return <div dangerouslySetInnerHTML={{ __html: svgCustm }} />;

            case 'Button':
                return <div dangerouslySetInnerHTML={{ __html: svgCustm }} />;

            case 'Distancia':
                return <div dangerouslySetInnerHTML={{ __html: svgCustm }} />;
            default:
                return <i class="ph ph-x-square"></i>;
        }
    };
    const transformBatteryLevel = (level) => {
        const getBatteryClass = () => {
            if (level <= 25) return 'text-danger';
            if (level <= 50) return 'text-warning';
            if (level <= 75) return 'text-info';
            return 'text-success';
        };

        const getBatteryIcon = () => {
            if (level <= 25) return 'ph-battery-low';
            if (level <= 50) return 'ph-battery-medium';
            if (level <= 75) return 'ph-battery-high';
            return 'ph-battery-full';
        };

        const isBatteryAlert = item?.battery_alert_level > level;

        return (
            <span>
                {level ? (
                    <span
                        className={`${getBatteryClass()} text-bold text-center`}>
                        <span className="d-flex text-center justify-content-center">
                            <i
                                className={`ph ${getBatteryIcon()} me-1`}
                                style={{ fontSize: '12px' }}></i>
                            {isBatteryAlert && (
                                <i
                                    className="ph ph-warning text-danger titilar"
                                    style={{ fontSize: '12px' }}></i>
                            )}
                        </span>
                        {level}%
                    </span>
                ) : (
                    <span className="text-muted text-bold text-center ">
                        <span className="d-flex text-center justify-content-center">
                            <i
                                style={{ fontSize: '12px' }}
                                className="ph ph-battery-warning-vertical"></i>
                            <i
                                style={{ fontSize: '12px' }}
                                className="ph ph-warning"></i>
                        </span>
                    </span>
                )}
            </span>
        );
    };

    function sumarHoras(fecha, horas) {
        const nuevaFecha = new Date(fecha);
        nuevaFecha.setHours(nuevaFecha.getHours() + horas);
        return nuevaFecha;
    }

    function formatDate(dateString) {
        return moment(dateString).format('YYYY-MM-DD');
    }

    return (
        <tr>
            <td className="align-middle p-0 m-0">
                <div className="d-flex align-items-center p-0 m-0">
                    <div className="d-inline-block me-1">
                        <Input
                            type="checkbox"
                            checked={devicesSelected?.includes(item?.dev_eui)}
                            onChange={(e) => {
                                changeDeviceSelected(e.target.checked, item);
                            }}
                            bsSize={'sm'}
                            name={''}
                        />
                    </div>
                    <div className="d-inline-block me-1">{getIconByType()}</div>
                    <div style={{ fontSize: '10px' }}>
                        <p className="text-body m-0 p-0  fw-semibold letter-icon-title">
                            {item.name}
                        </p>
                        <div
                            style={{ fontSize: 9 }}
                            className="text-muted m-0 p-0">
                            {item?.description}
                        </div>
                        <div
                            style={{ fontSize: 9 }}
                            className="fw-semibold text-primary m-0 p-0">
                            <span
                                className="d-inline-block text-truncate"
                                style={{ maxWidth: '60px' }}>
                                {item?.dev_eui}
                            </span>
                        </div>
                    </div>
                </div>
            </td>
            <td className="align-middle p-0 m-0">
                {item?.lecturas_latest && item?.lecturas_latest !== null ? (
                    <div>
                        <p
                            className="text-body m-0 p-0  fw-semibold letter-icon-title  p-0 m-0"
                            style={{ fontSize: '9px' }}>
                            {formatDate(item?.lecturas_latest?.created_at)}
                        </p>
                        <p
                            className="text-body m-0 p-0  fw-semibold letter-icon-title p-0 m-0"
                            style={{ fontSize: '9px' }}>
                            {timeAgo(item?.lecturas_latest?.created_at)}
                        </p>
                    </div>
                ) : (
                    <span
                        className=" text-danger text-bold"
                        style={{ fontSize: '9px' }}>
                        {'No hay lecturas'}
                    </span>
                )}
            </td>
            <td
                style={{ fontSize: 9 }}
                className="align-middle p-0 m-0 text-center">
                {transformBatteryLevel(item?.battery_level)}
            </td>
            <td className="align-middle p-2 m-2">
                <span
                    onClick={() => {
                        handleOptionsSelected('SELECTEDDEVICE', item);
                    }}
                    className={`${optionSelected.device?.dev_eui === item?.dev_eui ? 'text-primary text-bold' : 'text-muted opacity-75'}`}>
                    <i className="ph ph-eye"></i>
                </span>
                <Dropdown
                    className="float-end"
                    isOpen={dropdownOpen}
                    toggle={toggle}>
                    <DropdownToggle className="bg-transparent border-0 p-0">
                        <i className="ph ph-dots-three-vertical text-dark"></i>
                    </DropdownToggle>
                    <DropdownMenu>
                        <DropdownItem>
                            <i className="ph ph-eye"></i> Ver en detalle
                        </DropdownItem>
                        <DropdownItem
                            onClick={() => {
                                handleOptionsSelected('Reading', item);
                            }}>
                            <i className="ph-list"></i> Lista de lecturas
                        </DropdownItem>
                        <DropdownItem>
                            <i className="ph ph-map-pin"></i> Ver en el mapa
                        </DropdownItem>
                        <DropdownItem
                            onClick={() =>
                                setModalData({
                                    open: true,
                                    search: item?.dev_eui,
                                })
                            }>
                            <i className="ph ph-binoculars"></i> Verificar si
                            hay información
                        </DropdownItem>
                    </DropdownMenu>
                </Dropdown>
            </td>
            <ModalData
                open={modalData.open}
                search={item?.dev_eui}
                closeModal={() => {
                    setModalData({ open: false, search: '' });
                }}
            />
        </tr>
    );
};

export default ItemTable;
