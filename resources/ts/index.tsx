import React from 'react';
import ReaqctDOM from 'react-dom';
import { createRoot } from 'react-dom/client';
import Counter from './ContexSample'

const container = document.getElementById('app');
const root = createRoot(container!); 

root.render(
    <React.StrictMode>
        <div>
            <Counter initalValue={0}></Counter>
        </div>
    </React.StrictMode>
);