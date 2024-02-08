import { createApp } from 'vue'
import { i18nVue } from "laravel-vue-i18n";
import App from  './App.vue'
import router from '../assets/js/vue/router/indexRouter'
import {createPinia} from "pinia";

const pinia = createPinia()

const app = createApp(App)
    .use(i18nVue, {
        resolve: async lang => await import(`../../lang/${lang}.json`)
    })


app.use(router)
app.use(pinia)
app.mount('#app')
