import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

<<<<<<< HEAD
// Bootstrap
=======
// استدعاء Bootstrap لتنسيقات UI والأيقونات
>>>>>>> c612875508df5400cd64da2b71a92d9c9198e51e
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'bootstrap-icons/font/bootstrap-icons.css'

<<<<<<< HEAD
// ملفات التنسيق العامة
import '@/assets/styles/variables.css'
import '@/assets/styles/main.css'
import '@/assets/styles/responsive.css'

=======
>>>>>>> c612875508df5400cd64da2b71a92d9c9198e51e
const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')