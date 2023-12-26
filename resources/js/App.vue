<template>
    <div id="app_vue">
        <div v-if="$route.path == '/login' || $route.path == '/register' || $route.path == '/forgot-password'">
            <LayoutAccount />
        </div>
        <div v-else-if="isAdminRoute && authUser">
            <LayoutParent :authUser="authUser" />
        </div>
        <div v-else-if="isUserRoute && authUser">
            <PageUserParent :authUser="authUser" />
        </div>
        <div v-else>
            <WelComeView />
        </div>
    </div>
</template>
<script>
import LayoutAccount from './components/account/layoutAccountView.vue'
import WelComeView from './components/WelComeView.vue'
import LayoutParent from './components/admin/layouts/LayoutParent.vue';
import PageUserParent from './components/user/PageUserParent.vue';
export default {
    components: {
        LayoutAccount,
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
        isUserRoute(){
            return this.$route.path.startsWith("/");
        }
    }
}
</script>