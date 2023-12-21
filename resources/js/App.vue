<template>
    <div id="app_vue">
        <div v-if="$route.path == '/login' || $route.path == '/register' || $route.path == '/forgot-password'">
            <LayoutAccount />
        </div>
        <div v-else-if="isAdminRoute && authUser">
            <AdminView :authUser="authUser" />
        </div>
        <div v-else>
            <WelComeView />
        </div>
    </div>
</template>
<script>
import LayoutAccount from './components/account/layoutAccountView.vue'
import WelComeView from './components/WelComeView.vue'
import AdminView from './components/admin/adminView.vue'
export default {
    components: {
        LayoutAccount,
        AdminView,
        WelComeView
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
            return this.$route.path.startsWith("/user-manage") || this.$route.path.startsWith("/post-manage");
        },
    }
}
</script>