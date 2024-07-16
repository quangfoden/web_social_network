<script setup>
import Magnify from 'vue-material-design-icons/Magnify.vue';
import Home from 'vue-material-design-icons/Home.vue';
import AccountMultiple from 'vue-material-design-icons/AccountMultiple.vue';
import TelevisionPlay from 'vue-material-design-icons/TelevisionPlay.vue';
import StorefrontOutline from 'vue-material-design-icons/StorefrontOutline.vue';
import AccountGroup from 'vue-material-design-icons/AccountGroup.vue';
import ControllerClassicOutline from 'vue-material-design-icons/ControllerClassicOutline.vue';
import ClockTimetwoOutline from 'vue-material-design-icons/ClockTimetwoOutline.vue';
import VideoImage from 'vue-material-design-icons/VideoImage.vue';
import Flag from 'vue-material-design-icons/Flag.vue';
import DotsHorizontal from 'vue-material-design-icons/DotsHorizontal.vue';
import Restore from 'vue-material-design-icons/Restore.vue';
</script>
<template>
    <div id="LeftSection" class="card mb-3">
        <div class="leffsidebar_lists">
            <router-link :to="{ name: 'Profile User', params: { id: authUser.user_id } }"
                class="d-flex align-items-center justify-content-start w-100 left-item">
                <img :src="authUser.avatar" alt="" class="custom">
                <div class="text-profile">{{ authUser.user_name }}</div>
            </router-link>
            <router-link :to="{ name: 'FriendsParent' }"
                class="d-flex align-items-center justify-content-start w-100 left-item">
                <AccountMultiple :size="35" fillColor="#5BD7C6" />
                <div class="text-items">Bạn bè</div>
            </router-link>
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <Flag :size="35" fillColor="#F2682C" />
                <div class="text-items">Trang</div>
            </a>
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <ClockTimetwoOutline :size="35" fillColor="#21AAFA" />
                <div class="text-items">Gần đây nhất</div>
            </a>
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <AccountGroup :size="35" fillColor="#2A09FD" />
                <div class="text-items">Nhóm</div>
            </a>
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <StorefrontOutline :size="35" fillColor="#48C0D8" />
                <div class="text-items">marketplace</div>
            </a>
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <TelevisionPlay :size="35" fillColor="#9739CF" />
                <div class="text-items">Video</div>
            </a>
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <Restore :size="35" fillColor="#32D4D0" />
                <div class="text-items">Kỷ niệm</div>
            </a>
        </div>
    </div>
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