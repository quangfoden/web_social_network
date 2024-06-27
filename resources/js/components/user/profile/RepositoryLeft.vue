<script setup>
import Magnify from 'vue-material-design-icons/Magnify.vue';
import Home from 'vue-material-design-icons/Home.vue';
import History from 'vue-material-design-icons/History.vue';
import StorefrontOutline from 'vue-material-design-icons/StorefrontOutline.vue';
import AccountGroup from 'vue-material-design-icons/AccountGroup.vue';
import ControllerClassicOutline from 'vue-material-design-icons/ControllerClassicOutline.vue';
import ClockTimetwoOutline from 'vue-material-design-icons/ClockTimetwoOutline.vue';
import VideoImage from 'vue-material-design-icons/VideoImage.vue';
import Archive from 'vue-material-design-icons/Archive.vue';
import Delete from 'vue-material-design-icons/Delete.vue';
import ArrowLeft from 'vue-material-design-icons/ArrowLeft.vue';

</script>
<template>
    <div id="RepositoryLeft" style="height: 100vh;" class="card mb-3 col-12 col-lg-4 col-xl-3 order-2 order-lg-1">
        <div class="leffsidebar_lists">
            <a @click="goBack" class="d-flex align-items-center justify-content-start w-100 left-item">
                <ArrowLeft :size="35" fillColor="#B0B3B8" />
                <div class="text-items">Trở về</div>
            </a>
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <History :size="35" fillColor="#6c757d" />
                <div class="text-items">Nhật ký hoạt động</div>
            </a>
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <Archive :size="35" fillColor="#6c757d" />
                <div class="text-items">Kho lưu trữ tin</div>
            </a>

            <router-link :to="{ name: 'Trash User' }"
                class="d-flex align-items-center justify-content-start w-100 left-item">
                <Delete :size="35" fillColor="#6c757d" />
                Thùng rác
            </router-link>

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
        // setStatePage() {
        //     this.$store.dispatch('post/setStateToOne');
        // }
        goBack() {
            this.$router.go(-1);  // Trở về trang trước
        },
    }
}
</script>