<template>
    <div id="app_vue">
        <div
            v-if="$route.path.startsWith('/login') || $route.path.startsWith('/register') || $route.path.startsWith('/forgot-password') || $route.path.startsWith('/reset-password')">
            <LayoutUserAccount />
        </div>
        <div v-else-if="$route.path == '/admin/login'">
            <LayoutAdminAccount />
        </div>
        <div v-else-if="isAdminRoute && authUser">
            <LayoutParent :authUser="authUser" />
        </div>
        <div v-else-if="isUserRoute && authUser">
            <PageUserParent :authUser="authUser" />
        </div>
        <div v-else>
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import LayoutUserAccount from './components/user/account/layoutAccountView.vue'
import LayoutAdminAccount from './components/admin/account/layoutAccountView.vue'
import WelComeView from './components/WelComeView.vue'
import LayoutParent from './components/admin/layouts/LayoutParent.vue';
import PageUserParent from './components/user/PageUserParent.vue';
export default {
    components: {
        LayoutUserAccount,
        LayoutAdminAccount,
        LayoutParent,
        WelComeView,
        PageUserParent
    },
    data() {
        return {

        }
    },
    computed: {
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
        isAdminRoute() {
            return this.$route.path.startsWith("/admin");
        },
        isUserRoute() {
            return this.$route.path.startsWith("/");
        }
    }
}
</script>