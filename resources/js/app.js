require('./bootstrap');
import '@mdi/font/css/materialdesignicons.min.css';
import '@fortawesome/fontawesome-free/css/all.css';
import { createApp } from 'vue';
import App from './App.vue';
import Vuex from 'vuex';
import { createPinia } from 'pinia';
const pinia = createPinia();
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
app.use(router).mount('#app');