import React from 'react';
import ReaqctDOM from 'react-dom';
import { createRoot } from 'react-dom/client';

const container = document.getElementById('app');
const root = createRoot(container!);

root.render(
    <React.StrictMode>
        あ
    </React.StrictMode>
);
