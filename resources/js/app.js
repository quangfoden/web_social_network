require('./bootstrap');
import '@mdi/font/css/materialdesignicons.min.css';
import '@fortawesome/fontawesome-free/css/all.css';

import { createApp } from 'vue';
import App from './App.vue';
import Vuex from 'vuex';
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
const pinia = createPinia();
pinia.use(piniaPluginPersistedstate)
import rippleDirective from './directives/ripple.js';
import VueAxios from 'vue-axios';
import router from "./router/index"
import "bootstrap";
import { store } from './store/store';
import "bootstrap/dist/css/bootstrap.min.css";
import config from './config'
import axios from 'axios';
import Swal from 'sweetalert2';
const app = createApp(App)
app.config.globalProperties.$swal = Swal;
app.config.globalProperties.$config = config;
app.use(store, Vuex, axios, VueAxios);
app.use(pinia);
app.directive('ripple', rippleDirective);
app.use(router).mount('#app');

import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// // Khởi tạo Pusher
// window.Pusher = Pusher;
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY, 
    cluster: process.env.MIX_PUSHER_APP_CLUSTER, 
    forceTLS: true,
    encrypted: true,
    authEndpoint:'/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    }
});