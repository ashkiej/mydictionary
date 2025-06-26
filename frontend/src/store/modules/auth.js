// store/modules/auth.js
import api from '@/api'
export default {
    namespaced: true,
    state: {
        user: null,
        token: null
    },
    getters: {
        isAuthenticated: state => !!state.token,
        currentUser: state => state.user
    },
    mutations: {
        SET_USER(state, user) {
            state.user = user
        },
        SET_TOKEN(state, token) {
            state.token = token
        },
        CLEAR_AUTH(state) {
            state.user = null
            state.token = null
        }
    },
    actions: {
        login({ commit }, credentials) {
            return api.post('/api/auth/login', credentials)
                .then(response => {
                    commit('SET_USER', response.data.user)
                    commit('SET_TOKEN', response.data.token)
                    localStorage.setItem('authToken', response.data.token)
                    api.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
                })
        },
        register({ commit }, userData) {
            return api.post('/api/auth/register', userData)
                .then(response => {
                    commit('SET_USER', response.data.user)
                    return Promise.resolve(response.data)
                })
                .catch(error => {
                    return Promise.reject(error)
                })
        },
        logout({ commit }) {
            return api.post('/api/auth/logout')
                .finally(() => {
                    commit('CLEAR_AUTH')
                    localStorage.removeItem('authToken')
                    delete api.defaults.headers.common['Authorization']
                })
        },
        loadUser({ commit }) {
            const token = localStorage.getItem('authToken')
            if (token) {
                return api.get('/api/user')
                    .then(response => {
                        commit('SET_USER', response.data)
                        commit('SET_TOKEN', token)
                        api.defaults.headers.common['Authorization'] = `Bearer ${token}`
                    })
                    .catch(error => {
                        commit('CLEAR_AUTH')
                        localStorage.removeItem('authToken')
                        delete api.defaults.headers.common['Authorization']
                    })
            }
        }
    }
}