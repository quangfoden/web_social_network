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
    <div id="LeftSection">
        <div class="leffsidebar_lists">
            <a href="/" class="d-flex align-items-center justify-content-start w-100 left-item">
                <img :src="authUser.avatar" alt="" class="img-cus">
                <div class="text-profile">{{ authUser.user_name }}</div>
            </a>
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <AccountMultiple :size="40" fillColor="#5BD7C6" />
                <div class="text-items">Friends</div>
            </a>
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <Flag :size="40" fillColor="#F2682C" />
                <div class="text-items">Pages</div>
            </a>
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <ClockTimetwoOutline :size="40" fillColor="#21AAFA" />
                <div class="text-items">Most Recent</div>
            </a>
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <AccountGroup :size="40" fillColor="#2A09FD" />
                <div class="text-items">Groups</div>
            </a>
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <StorefrontOutline :size="40" fillColor="#48C0D8" />
                <div class="text-items">marketplace</div>
            </a>
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <TelevisionPlay :size="40" fillColor="#9739CF" />
                <div class="text-items">Watch</div>
            </a>
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <Restore :size="40" fillColor="#32D4D0" />
                <div class="text-items">Memories</div>
            </a>
        </div>
    </div>
</template>
<script>
export default {
    computed: {
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
    },
}
</script>