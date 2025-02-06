import React, { useState } from "react"; // Asegúrate de importar StrictMode desde React

const LoadingComponent = ({ loading }) => {

    return (
        <div className="flex items-center justify-center h-full">
            <h1 className="text-gray-500">Cargando...</h1>
        </div>
    );
};

export default LoadingComponent;
