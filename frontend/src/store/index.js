import { createStore } from 'vuex';
import auth from './modules/auth';
import dictionary from './modules/dictionary';
import favorites from './modules/favorites';
import api from '@/api'

const store = createStore({
    state: {
        loading: false
    },
    mutations: {
        SET_LOADING(state, value) {
            state.loading = value
        }
    },
    getters: {
        isLoading: state => state.loading,
        // ...other getters
    },
    modules: {
        auth,
        dictionary,
        favorites
    }
})

// Subscribe to the loading bus
api.loadingBus.on(isLoading => {
    store.commit('SET_LOADING', isLoading)
});

export default store