// app.js
import { createApp } from 'vue';
import store from './store'; // استيراد Vuex من store.js
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import TaskList from './components/TaskList.vue';

const app = createApp({
    components: {
        TaskList
    }
});

app.use(store);
app.mount('#app');
