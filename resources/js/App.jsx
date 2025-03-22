import React from 'react';
import { createRoot } from 'react-dom/client';
import Dashboard from './components/dashborad';

if (document.getElementById('react-dashboard')) {
    createRoot(document.getElementById('react-dashboard')).render(
        <Dashboard />,
    );
}
