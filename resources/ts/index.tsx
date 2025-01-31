import React from 'react';
import ReaqctDOM from 'react-dom';
import { createRoot } from 'react-dom/client';
import Counter from './ContexSample'
import {Parent} from './Parent'

const container = document.getElementById('app');
const root = createRoot(container!); 

root.render(
    <React.StrictMode>
        <Parent>
        </Parent>
    </React.StrictMode>
);