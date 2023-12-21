require('./bootstrap');
import '@mdi/font/css/materialdesignicons.min.css';
import '@fortawesome/fontawesome-free/css/all.css';
import { createApp } from 'vue';
import App from './App.vue';
import Vuex from 'vuex';
import router from "./router/index"
import "bootstrap";
import { store } from './store/store';
import "bootstrap/dist/css/bootstrap.min.css";
import config from './config'

const app = createApp(App)
app.use(store,config,Vuex);
app.use(router).mount('#app');