<template>
    <div class="fixed-sidebar left">
        <div class="menu-left">
            <ul class="left-menu">
                <li v-ripple>
                    <router-link :to="{ name: 'Profile User', params: { id: authUser.user_id } }" class="user-img" href="#" title="">
                        <img class="img-user" :src="authUser.avatar" alt="">
                        <span class="title-menu">{{ authUser.user_name }}</span>
                    </router-link>
                </li>
                <li v-ripple>
                    <a href="#" title="Bạn bè" data-toggle="tooltip" data-placement="right">
                        <i class="fas fa-user-group"></i> 
                        <span class="title-menu">Bạn bè</span>
                    </a>
                </li>
                <li v-ripple>
                    <a href="#" title="Tin nhắn" data-toggle="tooltip" data-placement="right">
                        <i class="fa fa-commenting"></i> 
                        <span class="title-menu">Tin nhắn</span>
                    </a>
                </li>
                <li v-ripple>
                    <a href="#" title="Video" data-toggle="tooltip" data-placement="right">
                        <i class="fas fa-video"></i>
                        <span class="title-menu">Video</span>
                    </a>
                </li>
                <li v-ripple>
                    <a href="#" title="Đã lưu" data-toggle="tooltip" data-placement="right">
                        <i class="fas fa-bookmark"></i>
                        <span class="title-menu">Đã lưu</span>
                    </a>
                </li>
                <li v-ripple>
                    <a href="#" title="Sinh nhật" data-toggle="tooltip" data-placement="right">
                        <i class="fas fa-cake-candles"></i>
                        <span class="title-menu">Sinh nhật</span>
                    </a>
                </li>
                <li v-ripple>
                    <a href="notifications.html" title="Nhóm" data-toggle="tooltip" data-placement="right">
                        <i class="fas fa-users"></i>
                        <span class="title-menu">Nhóm</span>
                    </a>
                </li>
            </ul>
        </div>
    </div><!-- left sidebar menu -->
</template>
<script>
import { mapState, mapActions } from 'vuex';
export default {
    computed: {
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
    },
    methods: {

    }
}
</script>