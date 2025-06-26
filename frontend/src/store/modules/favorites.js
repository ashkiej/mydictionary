// store/modules/favorites.js
import api from '@/api'
export default {
    namespaced: true,
    state: {
        favorites: []
    },
    mutations: {
        SET_FAVORITES(state, favorites) {
            state.favorites = favorites
        },
        ADD_FAVORITE(state, favorite) {
            state.favorites.unshift(favorite)
        },
        UPDATE_FAVORITE(state, updatedFavorite) {
            const index = state.favorites.findIndex(f => f.id === updatedFavorite.id)
            if (index !== -1) {
                state.favorites.splice(index, 1, updatedFavorite)
            }
        },
        REMOVE_FAVORITE(state, id) {
            state.favorites = state.favorites.filter(f => f.id !== id)
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
            await api.delete(`/api/favorites/${id}`)
            commit('REMOVE_FAVORITE', id)
        }
    }
}