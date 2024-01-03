import { createStore } from 'vuex';
import auth from './modules/auth';
import posts from './modules/posts';



export const store = createStore({
    modules: {
        auth,
        post:posts
    },
});