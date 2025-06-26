import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
import './style.css'

// Set base axios URL
axios.defaults.baseURL = import.meta.env.VUE_APP_API_URL || 'http://localhost:8000'

// Create a simple event emitter for toast notifications
class EventEmitter {
    constructor() {
        this.events = {}
    }

    on(event, callback) {
        if (!this.events[event]) {
            this.events[event] = []
        }
        this.events[event].push(callback)
    }

    emit(event, data) {
        if (this.events[event]) {
            this.events[event].forEach(callback => callback(data))
        }
    }

    off(event, callback) {
        if (this.events[event]) {
            this.events[event] = this.events[event].filter(cb => cb !== callback)
        }
    }
}

const eventBus = new EventEmitter()

// Create Vue app
const app = createApp(App)

// Toast notification plugin
const toastPlugin = {
    install(app) {
        const toast = {
            success(message) {
                eventBus.emit('toast', { message, type: 'success' })
            },
            error(message) {
                eventBus.emit('toast', { message, type: 'error' })
            },
            info(message) {
                eventBus.emit('toast', { message, type: 'info' })
            },
            warning(message) {
                eventBus.emit('toast', { message, type: 'warning' })
            }
        }

        // Global properties (for Options API)
        app.config.globalProperties.$toast = toast
        app.config.globalProperties.$eventBus = eventBus

        // Provide/inject (for Composition API)
        app.provide('toast', toast)
        app.provide('eventBus', eventBus)
    }
}

// Use plugins and mount app
app.use(store)
store.dispatch('auth/loadUser')
app.use(router)
app.use(toastPlugin)
app.mount('#app')