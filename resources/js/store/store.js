import { createStore } from 'vuex';
import register from './modules/auth';



export const store = createStore({
    modules: {
        register
    },
});