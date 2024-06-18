import { createRouter, createWebHistory } from 'vue-router';

import WelComeView from '../components/WelComeView.vue'

import LoginView from '../components/account/LoginView.vue'
import RegisterView from '../components/account/RegisterView.vue'
import ForgotPasswordView from '../components/account/ForgotPasswordView.vue'

import RgFaceIF from '../../js/components/user/components/regFaceId.vue'

import AdminView from '../components/admin/adminView.vue';

import ProfileParent from '../components/admin/profile/ProfileParent.vue';
import ProfileAdmin from '../components/admin/profile/ProfileView.vue';
import ChangePasswordProfile from '../components/admin/profile/ChangePassword.vue';

import UserParent from '../components/admin/Users/UserParent.vue'
import AllUser from '../components/admin/Users/AllUser.vue'


import PostParent from '../components/admin/Posts/PostParent.vue'
import AllPost from '../components/admin/Posts/AllPost.vue'

import CommentParent from '../components/admin/Comments/CommentParent.vue'
import AllComment from '../components/admin/Comments/AllComment.vue'

import PageUserParent from '../components/user/PageUserParent.vue'
import LayoutUserParent from '../components/user/layouts/LayoutsUserParent.vue'
import HomeView from '../components/user/homeSection/HomeView.vue'
import ProfileParentUser from '../components/user/profile/ProfileParent.vue';
import RepositoryParent from '../components/user/profile/RepositoryParent.vue';
import Trash from '../components/user/profile/Trash.vue';

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
        path: '/welcome',
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
        path: '/admin',
        name: "Dashboard",
        component: AdminView,
        meta: { requiresAuth: true },
        children: [
            {
                path: 'user-manage',
                name: "User Manage",
                component: UserParent,
                children: [
                    {
                        path: 'all-user',
                        name: "All User",
                        component: AllUser,
                    }
                ]
            },
            {
                name: 'Profile',
                path: '',
                component: ProfileParent,
                children: [
                    {
                        name: 'Profile Admin',
                        path: 'profile-admin',
                        component: ProfileAdmin,
                    },
                    {
                        name: 'Change Password',
                        path: 'change-password',
                        component: ChangePasswordProfile,
                    }
                ]
            }
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
    {
        path: '/',
        component: PageUserParent,
        meta: { requiresAuthUser: true },
        children: [
            {
                path: '',
                name: 'Layout User Parent',
                component: LayoutUserParent,
                children: [
                    {
                        path: '',
                        name: 'Home Section',
                        component: HomeView,
                    },
                ]
            },
            {
                path: 'face-id',
                name: 'RgFaceIF User',
                component: RgFaceIF,
            },
            {
                path: 'profile/:id',
                name: 'Profile User',
                component: ProfileParentUser,
            },
            {
                path: 'repository',
                name: 'Repository User',
                component: RepositoryParent,
                children: [
                    {
                        path: 'Trash',
                        name: 'Trash User',
                        component: Trash
                    }
                ]
            },

        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})
import { store } from '../store/store';

router.beforeEach((to, from, next) => {
    store.dispatch('fetchAccounts').then(() => {
        next(); 
    }).catch(error => {
        console.error('Error fetching accounts:', error);
        next(error); 
    });
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
    const requiresAuthUser = to.matched.some(record => record.meta.requiresAuthUser)
    const loginResponse = JSON.parse(localStorage.getItem('loginResponse'));

    const isAdmin = loginResponse?.role?.includes('admin');
    const isUser = loginResponse?.role?.includes('user');

    const isAuthenticated =
        store.getters.getLoginResponse.authenticated && isAdmin
        || JSON.parse(localStorage.getItem('loginResponse'))?.authenticated && isAdmin
    const isUserAuthenticated =
        store.getters.getLoginResponse.authenticated && isUser
        || JSON.parse(localStorage.getItem('loginResponse'))?.authenticated && isUser
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
    if (requiresAuthUser) {
        if (!isUserAuthenticated) {
            next('/login');
            return;
        }
    }

    next();
})
export default router