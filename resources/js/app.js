require('./bootstrap');
import '@fortawesome/fontawesome-free/css/all.css';
import { createApp } from 'vue';
import App from './App.vue';
import router from "./router/index"
import "bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";
const app = createApp(App)

app.use(router).mount('#app');