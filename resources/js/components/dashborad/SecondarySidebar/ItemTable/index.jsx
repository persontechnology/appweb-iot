import React from "react";
import { Table } from "reactstrap";

const ItemTable = ({ item }) => {
    const getIconByType = (type) => {
        switch (type) {
            case "movement":
                return <i className="ph-arrows-left-right"></i>; // Example for movement icon (left arrow)
            case "distance":
                return <i className="ph-map-pin"></i> ; // Example for distance icon (map pin)
            case "temperature":
                return <i className="ph-thermometer"></i>; // Example for temperature icon (thermometer)
            default:
                return <i className="ph-thermometer"></i>;; // Default icon if no match
        }
    };
    return (
        <tr>
            <td>
                <div className="d-flex align-items-center">
                    <a href="#" className="d-inline-block me-3">
                        <i className="ph-arrows-left-right"></i>
                    </a>
                    <div>
                        <a
                            href="#"
                            className="text-body fw-semibold letter-icon-title"
                        >
                            {item.name}
                        </a>
                        <div className="text-muted fs-sm">
                            {item?.description}
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <span className="text-muted">{item?.time}</span>
            </td>
            <td>
                <strong>{item?.price}</strong>
            </td>
        </tr>
    );
};

export default ItemTable;
