import { createStore } from 'vuex';
import register from './modules/register';
import login from './modules/login';


export const store = createStore({
    modules: {
        register,
        login
    },
});