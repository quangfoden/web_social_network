import { createStore } from 'vuex';
import auth from './modules/auth';
import posts from './modules/posts';
import users from './modules/users';
import chat from './modules/chat';
import friends from './modules/friends';



export const store = createStore({
    modules: {
        auth,
        post:posts,
        users,
        chat,
        friends
    },
});