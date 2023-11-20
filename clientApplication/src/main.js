//import './assets/main.css'
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap-icons/font/bootstrap-icons.css"
import "bootstrap"
import axios from 'axios'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

const app = createApp(App)

const serverBaseUrl = 'http://server-api.test'
app.provide('serverBaseUrl', serverBaseUrl)

axios.defaults.baseURL = serverBaseUrl + '/api'
axios.defaults.headers.common['Content-Type'] = 'application/json'

app.use(createPinia())
app.use(router)

app.mount('#app')
