// src/api/index.js
import axios from 'axios'

// Simple loading event bus
const loadingBus = {
    listeners: [],
    on(fn) {
        this.listeners.push(fn)
    },
    off(fn) {
        this.listeners = this.listeners.filter(l => l !== fn)
    },
    emit(val) {
        this.listeners.forEach(fn => fn(val))
    }
}

const api = axios.create({
    baseURL: import.meta.env.VUE_APP_API_URL || 'http://localhost:8000',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
})

// Add request interceptor
let requestCount = 0
api.interceptors.request.use(config => {
    const token = localStorage.getItem('authToken')
    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }
    requestCount++
    loadingBus.emit(true)
    return config
})

// Add response interceptor to manage loading
api.interceptors.response.use(
    response => {
        requestCount--
        if (requestCount === 0) loadingBus.emit(false)
        return response
    },
    error => {
        requestCount--
        if (requestCount === 0) loadingBus.emit(false)
        return Promise.reject(error)
    }
)

api.loadingBus = loadingBus

export default api