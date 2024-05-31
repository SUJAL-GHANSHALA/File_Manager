import { createApp } from 'vue'
import App from './App.vue'
import router from './routes'

import PrimeVue from 'primevue/config';
import 'primevue/resources/themes/lara-light-indigo/theme.css';
import 'primevue/resources/primevue.min.css';
// import 'primeicons/primeicons.css';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';  
import Row from 'primevue/row'; 

import store from './store'

const app =createApp(App);

app.use(store);
app.use(PrimeVue);
app.component('DataTable', DataTable);
app.component('Column', Column);
app.component('ColumnGroup', ColumnGroup);
app.component('Row', Row);

app.use(router);
app.mount('#app')

