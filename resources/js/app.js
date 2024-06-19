require('./bootstrap');
import { createApp } from 'vue';
import App from './components/App.vue';

import axios from 'axios';
import VueAxios from 'vue-axios';
import { createRouter, createWebHistory } from 'vue-router';
import { routes } from './routes';

import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

// Crear instancia de router
const router = createRouter({
  history: createWebHistory(),
  routes: routes
});

// Crear la aplicación
const app = createApp(App);

app.config.globalProperties.$toastr = toastr;

// Usar plugins
app.use(router);
app.use(VueAxios, axios);

// Montar la aplicación
app.mount('#app');
