<template>
    <div id="app_vue">
        <div v-if="$route.path == '/'">
            <LayoutAccount />
        </div>
        <div
            v-if="$route.path.startsWith('/user-manage') || $route.path.startsWith('/post-manage') || $route.path.startsWith('/comment-manage')">
            <AdminView />
        </div>
    </div>
</template>
<script>
import LayoutAccount from './components/account/layoutAccountView.vue'
import AdminView from './components/admin/adminView.vue'
export default {
    components: {
        LayoutAccount,
        AdminView
    },
    data() {
        return {
            authen: false
        }
    },
    mounted() {
        console.log(this.authen)
    }
}
</script>