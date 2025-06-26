// store/modules/dictionary.js
import api from '@/api'
export default {
    namespaced: true,
    actions: {
        searchWord({ commit }, term) {
            return api.get(`/api/dictionary/search?q=${encodeURIComponent(term)}`)
                .then(response => response.data)
        }
    }
}