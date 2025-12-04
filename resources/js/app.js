import './bootstrap';
import 'bootstrap';
import { createApp } from 'vue';

// Importa tu componente principal de Vue
import App from './App.vue';

createApp(App).mount('#app');
// Initialize any Bootstrap tooltips
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
