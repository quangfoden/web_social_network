<template>
    <TopBar />
    <AllFriendsView />
</template>
<script>
import TopBar from '../layouts/TopBar.vue';
import AllFriendsView from '../AllFriends/AllFriendsView.vue'
export default {
    components: {
        TopBar,
        AllFriendsView
    },
}
</script>