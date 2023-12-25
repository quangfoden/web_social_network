import { createRouter, createWebHistory } from 'vue-router';

import WelComeView from '../components/WelComeView.vue'

import LoginView from '../components/account/LoginView.vue'
import RegisterView from '../components/account/RegisterView.vue'
import ForgotPasswordView from '../components/account/ForgotPasswordView.vue'

import UserParent from '../components/admin/Users/UserParent.vue'
import AllUser from '../components/admin/Users/AllUser.vue'


import PostParent from '../components/admin/Posts/PostParent.vue'
import AllPost from '../components/admin/Posts/AllPost.vue'

import CommentParent from '../components/admin/Comments/CommentParent.vue'
import AllComment from '../components/admin/Comments/AllComment.vue'

const ErrorPaBlogge = {
    template:
        '<div class="error-page">Trang Này Hiện Đang Trong Quá Trình Phát Triển <i class="fas fa-heart text-danger"></i></div>'
};
export const routes = [


    {
        path: '/error',
        name: "Error",
        component: ErrorPaBlogge
    },
    {
        path: '/',
        name: "WelCome View",
        component: WelComeView
    },
    {
        path: '/login',
        name: "Login",
        component: LoginView
    },

    {
        path: '/register',
        name: "Register",
        component: RegisterView
    },
    {
        path: '/forgot-password',
        name: "ForgotPassword",
        component: ForgotPasswordView
    },
    {
        path: '/user-manage',
        name: "User Manage",
        component: UserParent,
        meta: { requiresAuth: true },
        children: [
            {
                path: 'all',
                name: "All User",
                component: AllUser,
            },
        ]

    },
    {
        path: '/post-manage',
        name: "Post Manage",
        component: PostParent,
        meta: { requiresAuth: true },
        children: [
            {
                path: 'all',
                name: "All Post",
                component: AllPost,
            },
        ]
    },
    {
        path: '/comment-manage',
        name: "Comment Manage",
        component: CommentParent,
        children: [
            {
                path: 'all',
                name: "All Comment",
                component: AllComment,
            },
        ]
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})
import { store } from '../store/store';
router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
    const isAuthenticated =
        store.getters.getLoginResponse.authenticated
        || JSON.parse(localStorage.getItem('loginResponse'))?.authenticated
    let authUser = undefined
    if (store.getters.getAuthUser.id !== undefined) {
        authUser = store.getters.getAuthUser;
    }
    authUser = JSON.parse(localStorage.getItem('authUser'));
    if (requiresAuth) {
        if (!isAuthenticated) {
            next('/login');
            return;
        }
    }

    next();
})
export default router