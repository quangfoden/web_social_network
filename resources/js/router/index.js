import { createRouter, createWebHistory } from 'vue-router';

import WelComeView from '../components/WelComeView.vue'

// Admin account 
import LoginAdminView from '../components/admin/account/LoginView.vue'


// user account 
import LoginUserView from '../components/user/account/LoginView.vue'
import RegisterUserView from '../components/user/account/RegisterView.vue'
import ForgotPasswordUserView from '../components/user/account/ForgotPasswordView.vue'
import ResetPasswordUserView from '../components/user/account/ResetPasswordView.vue'

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
import TimeLine from '../components/user/profile/TimeLineView.vue';
import AboutView from '../components/user/profile/AboutView.vue';
import EditProfile from '../components/user/profile/EditProfileView.vue';

import RepositoryParent from '../components/user/profile/RepositoryParent.vue';
import Trash from '../components/user/profile/Trash.vue';

// friend
import FriendsParent from '../components/user/AllFriends/FriendsParent.vue'
import allYourFriends from '../components/user/AllFriends/allYourFriends.vue'
import AllRequestFriend from '../components/user/AllFriends/AllRequestFriend.vue'
import FriendSuggestions from '../components/user/AllFriends/FriendSuggestions.vue'

const ErrorPaBlogge = {
    template:
        '<div class="error-page">Liên kết này không còn sử dụng được nữa. Vui lòng thử lại sau <i class="fas fa-heart text-danger"></i></div>'
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
        path: '/admin/login',
        name: "Login Admin",
        component: LoginAdminView
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
        path: '/login',
        name: "Login User",
        component: LoginUserView
    },
    {
        path: '/forgot-password',
        name: "Forgot Password User",
        component: ForgotPasswordUserView
    },

    {
        path: '/reset-password/:token',
        name: 'Reset Password User',
        component: ResetPasswordUserView
    },

    {
        path: '/register',
        name: "Register User",
        component: RegisterUserView
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
                redirect: { name: 'TimeLine User' },
                children: [
                    {
                        path: '',
                        name: 'TimeLine User',
                        component: TimeLine
                    },
                    {
                        path: 'about',
                        name: 'About User',
                        component: AboutView,
                    },
                    {
                        path: 'edit-profile',
                        name: 'Edit Profile',
                        component: EditProfile,
                    },
                ]
            },
            {
                path: 'repository/:id',
                name: 'Repository User',
                component: RepositoryParent,
                children: [
                    {
                        path: 'trash',
                        name: 'Trash User',
                        component: Trash
                    }
                ]
            },
            {
                path: 'friends',
                name: 'FriendsParent',
                component: FriendsParent,
                children:
                    [
                        {
                            path: 'all-firend',
                            name: 'allYourFriends',
                            component: allYourFriends,

                        },
                        {
                            path: 'firends-request',
                            name: 'AllFriendsRequest',
                            component: AllRequestFriend,

                        },
                        {
                            path: 'firends-suggestions',
                            name: 'FriendSuggestions',
                            component: FriendSuggestions,

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
import { useUserStatus } from '../core/coreFunction';

// router.beforeEach((to, from, next) => {
//     // store.dispatch('fetchAccounts')
//     // store.dispatch('friends/fetchIsFriends')
//     //     .then(() => {
//     //         next();
//     //     }).catch(error => {
//     //         console.error('Error fetching accounts:', error);
//     //         next(error);
//     //     });
//     const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
//     const requiresAuthUser = to.matched.some(record => record.meta.requiresAuthUser)
//     const loginResponse = JSON.parse(localStorage.getItem('loginResponse'));

//     const isAdmin = loginResponse?.role?.includes('admin');
//     const isUser = loginResponse?.role?.includes('user');
//     const token = localStorage.getItem('auth_token');
//     const isAuthenticated =
//         store.getters.getLoginResponse.authenticated && isAdmin
//         || JSON.parse(localStorage.getItem('loginResponse'))?.authenticated && isAdmin
//     const isUserAuthenticated =
//         store.getters.getLoginResponse.authenticated && isUser
//         || JSON.parse(localStorage.getItem('loginResponse'))?.authenticated && isUser
//     let authUser = undefined
//     if (store.getters.getAuthUser.id !== undefined) {
//         authUser = store.getters.getAuthUser;
//     }
//     authUser = JSON.parse(localStorage.getItem('authUser'));
//     if (authUser) {
//         const { userStatusRef } = useUserStatus(authUser.id);
//         console.log('User status updated in global router middleware.');
//         next();
//     }
//     if (requiresAuth) {
//         if (!isAuthenticated) {
//             next('/admin/login');
//             return;
//         }
//     }
//     if (requiresAuthUser) {
//         if (!isUserAuthenticated && !token) {
//             next('/login');
//             return;
//         }
//     }

//     next();
// })
// export default router
router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    const requiresAuthUser = to.matched.some(record => record.meta.requiresAuthUser);
    const loginResponse = JSON.parse(localStorage.getItem('loginResponse'));

    const isAdmin = loginResponse?.role?.includes('admin');
    const isUser = loginResponse?.role?.includes('user');
    const token = localStorage.getItem('auth_token');
    
    const isAuthenticated =
        store.getters.getLoginResponse.authenticated && isAdmin
        || loginResponse?.authenticated && isAdmin;
    
    const isUserAuthenticated =
        store.getters.getLoginResponse.authenticated && isUser
        || loginResponse?.authenticated && isUser;
    
    let authUser = store.getters.getAuthUser.id !== undefined
        ? store.getters.getAuthUser
        : JSON.parse(localStorage.getItem('authUser'));

    if (authUser) {
        const { userStatusRef } = useUserStatus(authUser.id);
        
        // Gọi fetchAccounts và fetchIsFriends chỉ khi người dùng đã đăng nhập
        if (isAuthenticated || isUserAuthenticated) {
            Promise.all([
                store.dispatch('fetchAccounts'),
                store.dispatch('friends/fetchFriendsByUserId', authUser.user_id)
            ])
                .then(() => {
                    next();
                })
                .catch(error => {
                    console.error('Error fetching accounts:', error);
                    next(error);
                });
            return; // Dừng lại cho đến khi fetch hoàn thành
        }
    }

    // Kiểm tra yêu cầu xác thực
    if (requiresAuth && !isAuthenticated) {
        next('/admin/login');
        return;
    }
    
    if (requiresAuthUser && !isUserAuthenticated && !token) {
        next('/login');
        return;
    }

    next();
});

export default router;
