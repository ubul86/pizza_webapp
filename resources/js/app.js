import { createApp } from 'vue';
import App from './App.vue';
import router from './routers';
import { createPinia } from 'pinia';
import vuetify from './plugins/vuetify';
import Swal from 'sweetalert2';
import '@mdi/font/css/materialdesignicons.css';
import Toast, { POSITION } from 'vue-toastification';
import 'vue-toastification/dist/index.css';

const app = createApp(App);

app.use(router);
app.use(createPinia());
app.use(vuetify);

app.use(Toast, {
    position: POSITION.TOP_RIGHT,
    timeout: 5000,
});

app.config.globalProperties.$swal = Swal;

app.mount('#app');
