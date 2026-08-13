import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import i18n from './i18n'

import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'bootstrap-icons/font/bootstrap-icons.css'

// حل تحذير Canvas2D (getImageData) الخاص بمكتبة الخريطة الحرارية Leaflet Heatmap
if (typeof window !== 'undefined' && HTMLCanvasElement.prototype.getContext) {
  const originalGetContext = HTMLCanvasElement.prototype.getContext;
  HTMLCanvasElement.prototype.getContext = function (type, attributes) {
    if (type === '2d') {
      attributes = Object.assign({}, attributes, { willReadFrequently: true });
    }
    return originalGetContext.call(this, type, attributes);
  };
}

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(i18n)

app.mount('#app')
