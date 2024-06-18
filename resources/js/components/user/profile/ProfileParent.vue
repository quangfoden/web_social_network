<template>
    <TopBar />
    <ProfileView />
</template>
<script>
import TopBar from '../layouts/TopBar.vue';
import ProfileView from './ProfileView.vue'
export default {
    components: {
        TopBar,
        ProfileView
    },
}
</script>