import React from 'react'
import ReactDOM from 'react-dom/client'
import Hello from './components/Hello'

const el = document.getElementById('app')

if (el) {
    ReactDOM.createRoot(el).render(
        <React.StrictMode>
            <Hello />
        </React.StrictMode>
    )
}