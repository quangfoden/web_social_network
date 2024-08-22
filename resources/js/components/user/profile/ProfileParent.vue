<script setup>
import ArrowLeft from 'vue-material-design-icons/ArrowLeft.vue';
</script>
<template>
    <div style="position: fixed; top: 50px; z-index: 10000;" v-show="showClickBack">
        <a style="width: 100px; background:#242526;" @click="goBack" class="primary-text custom-cursor-pointer my-2 d-flex align-items-center justify-content-start left-item">
            <ArrowLeft :size="35" fillColor="#B0B3B8" />
            <div class="text-items">Trở về</div>
        </a>
    </div>
    <TopBar />
    <ProfileView />
</template>
<script>
import TopBar from '../layouts/TopBar.vue';
import ProfileView from './ProfileView.vue'
import { storeToRefs } from 'pinia';
import { useGeneralStore } from '../../../store/general';

export default {
    components: {
        TopBar,
        ProfileView
    },
    data() {
        const useGeneral = useGeneralStore();
        const { showClickBack } = storeToRefs(useGeneral)
        return {
            showClickBack
        }
    },
    methods: {
        goBack() {
            this.$router.go(-1);  // Trở về trang trước
            this.showClickBack = false
        },
    }
}
</script>