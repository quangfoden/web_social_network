<script setup>
import Magnify from 'vue-material-design-icons/Magnify.vue';
import Home from 'vue-material-design-icons/Home.vue';
import History from 'vue-material-design-icons/History.vue';
import StorefrontOutline from 'vue-material-design-icons/StorefrontOutline.vue';
import AccountGroup from 'vue-material-design-icons/AccountGroup.vue';
import AccountCircle from 'vue-material-design-icons/AccountCircle.vue';
import AccountPlus from 'vue-material-design-icons/AccountPlus.vue';
import Group from 'vue-material-design-icons/Group.vue';
import Archive from 'vue-material-design-icons/Archive.vue';
import Delete from 'vue-material-design-icons/Delete.vue';
import ArrowLeft from 'vue-material-design-icons/ArrowLeft.vue';

</script>
<template>
    <div id="Friends" style="height: 100vh;" class="card mb-3 col-12 col-lg-4 order-2 order-lg-2">
        <div class="leffsidebar_lists">
            <a @click="goBack" class="d-flex align-items-center justify-content-start w-100 left-item">
                <ArrowLeft :size="35" fillColor="#B0B3B8" />
                <div class="text-items">Trở về</div>
            </a>
            <router-link :to="{name:'allYourFriends'}" class="d-flex align-items-center justify-content-start w-100 left-item">
                <AccountGroup :size="35" fillColor="#007bff" />
                <div class="text-items">Tất cả bạn bè</div>
            </router-link>
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <AccountPlus :size="35" fillColor="#007bff" />
                <div class="text-items">Yêu cầu kết bạn</div>
            </a>
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <Group :size="35" fillColor="#007bff" />
                <div class="text-items">Gợi ý bạn bè</div>
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
        goBack() {
            this.$router.go(-1);  // Trở về trang trước
        },
    }
}
</script>