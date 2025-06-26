import { createStore } from 'vuex';
import auth from './modules/auth';
import dictionary from './modules/dictionary';
import favorites from './modules/favorites';

export default createStore({
    state: {
        loading: false
    },
    mutations: {
        SET_LOADING(state, value) {
            state.loading = value
        }
    },
    modules: {
        auth,
        dictionary,
        favorites
    }
});