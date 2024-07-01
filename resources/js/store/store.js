import { createStore } from 'vuex';
import auth from './modules/auth';
import posts from './modules/posts';
import users from './modules/users';
import chat from './modules/chat';



export const store = createStore({
    modules: {
        auth,
        post:posts,
        users,
        chat
    },
});