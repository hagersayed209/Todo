import './bootstrap';

import Alpine from 'alpinejs';

import 'bootstrap/dist/css/bootstrap.min.css';


window.Alpine = Alpine;

Alpine.start();
x
createApp({
    components: {
        Tasks,
    }
}).mount('#app');
