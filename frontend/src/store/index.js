import { createStore } from 'vuex';
import auth from './modules/auth';
import dictionary from './modules/dictionary';
import favorites from './modules/favorites';

export default createStore({
    modules: {
        auth,
        dictionary,
        favorites
    }
});