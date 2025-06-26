// src/api/index.js
import axios from 'axios'

const api = axios.create({
    baseURL: import.meta.env.VUE_APP_API_URL || 'http://localhost:8000',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
})

// Add request interceptor
api.interceptors.request.use(config => {
    const token = localStorage.getItem('authToken')
    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }
    return config
})

export default api