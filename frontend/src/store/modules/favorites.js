// store/modules/favorites.js
import api from '@/api'
export default {
    namespaced: true,
    state: {
        favorites: [],
        result: {}
    },
    mutations: {
        SET_FAVORITES(state, res) {
            state.favorites = res || []
        },
        ADD_FAVORITE(state, res) {
            state.result = res
        },
        UPDATE_FAVORITE(state, res) {
            state.result = res
        },
        REMOVE_FAVORITE(state, res) {
            state.result = res
        }
    },
    actions: {
        async fetchFavorites({ commit }) {
            const response = await api.get('/api/favorites')
            commit('SET_FAVORITES', response.data)
        },
        async addFavorite({ commit }, wordData) {
            const response = await api.post('/api/favorites', wordData)
            commit('ADD_FAVORITE', response.data)
        },
        async updateFavorite({ commit }, { id, notes }) {
            const response = await api.put(`/api/favorites/${id}`, { notes })
            commit('UPDATE_FAVORITE', response.data)
        },
        async deleteFavorite({ commit }, id) {
           const response = await api.delete(`/api/favorites/${id}`)
            commit('REMOVE_FAVORITE', response.data)
        }
    }
}