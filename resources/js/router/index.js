import { createRouter, createWebHistory } from 'vue-router';

import LoginView from '../components/account/LoginView.vue'
import RegisterView from '../components/account/RegisterView.vue'
import ForgotPasswordView from '../components/account/ForgotPasswordView.vue'

import UserParent from '../components/admin/Users/UserParent.vue'
import AllUser from '../components/admin/Users/AllUser.vue'
import AddUser from '../components/admin/Users/AddUser.vue'

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
        children: [
            {
                path: 'all',
                name: "All User",
                component: AllUser,
            },
            {
                path: 'add',
                name: "Add User",
                component: AddUser,
            }
        ]

    },
    {
        path: '/post-manage',
        name: "Post Manage",
        component: PostParent,
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

export default router