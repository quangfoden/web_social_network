<script setup>
import Magnify from 'vue-material-design-icons/Magnify.vue';
import Home from 'vue-material-design-icons/Home.vue';
import AccountMultiple from 'vue-material-design-icons/AccountMultiple.vue';
import StorefrontOutline from 'vue-material-design-icons/StorefrontOutline.vue';
import AccountGroup from 'vue-material-design-icons/AccountGroup.vue';
import ControllerClassicOutline from 'vue-material-design-icons/ControllerClassicOutline.vue';
import ClockTimetwoOutline from 'vue-material-design-icons/ClockTimetwoOutline.vue';
import VideoImage from 'vue-material-design-icons/VideoImage.vue';
import Flag from 'vue-material-design-icons/Flag.vue';
import DotsHorizontal from 'vue-material-design-icons/DotsHorizontal.vue';

</script>
<template>
    <div id="RepositoryLeft" style="height: 100vh;" class="card mb-3 mt-3 col-12 col-lg-4 col-xl-3 order-2 order-lg-1">
        <div class="leffsidebar_lists">
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <AccountMultiple :size="40" fillColor="#5BD7C6" />
                <div class="text-items">Nhật ký hoạt động</div>
            </a>
            <a class="d-flex align-items-center justify-content-start w-100 left-item">
                <Flag :size="40" fillColor="#F2682C" />
                <div class="text-items">Kho lưu trữ</div>
            </a>

            <router-link :to="{ name: 'Trash User' }" class="text-items">
                <i class="mdi mdi-delete"></i>
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
    }
}
</script>