<script setup>
import Magnify from 'vue-material-design-icons/Magnify.vue';
import Home from 'vue-material-design-icons/Home.vue';
import HomeOutline from 'vue-material-design-icons/HomeOutline.vue';
import TelevisionPlay from 'vue-material-design-icons/TelevisionPlay.vue';
import StorefrontOutline from 'vue-material-design-icons/StorefrontOutline.vue';
import AccountGroup from 'vue-material-design-icons/AccountGroup.vue';
import ControllerClassicOutline from 'vue-material-design-icons/ControllerClassicOutline.vue';
import DotsGrid from 'vue-material-design-icons/DotsGrid.vue';
import FacebookMessenger from 'vue-material-design-icons/FacebookMessenger.vue';
import Bell from 'vue-material-design-icons/Bell.vue';
import Logout from 'vue-material-design-icons/Logout.vue';

</script>

<template>
    <div id="Mainnav">
        <router-link :to="{ name: '' }" class="text_name">
            <img width="40" src="/images/icons/FacebookLogoCircle.png" alt="">
        </router-link>
        <div id="Navleft">
            <Magnify class="p-2" :size="22" fillColor="#64676B" />
            <input class="search" placeholder="Search..." type="text">
        </div>
        <div id="NavCenter">
            <router-link :to="{ name:'Home Section' }" class="w-100">
                <div :class="$route.path == '/' ? 'mt-1' : ''"
                    class="list_items d-flex align-items-center justify-content-center w-100">
                    <div>
                        <Home v-if="$route.path == '/'" class="mx-auto" :size="27" fillColor="#1A73E3" />
                        <HomeOutline v-else class="mx-auto" :size="32" fillColor="#64676B" />
                    </div>
                </div>
                <div v-if="$route.path == '/'" class="border-b-4 border-b-blue-400 rounded-400 item-active"></div>
            </router-link>
            <div class="list_items d-flex align-items-center justify-content-center w-100">
                <TelevisionPlay class="mx-auto" :size="27" fillColor="#64676B" />
            </div>
            <div class="list_items d-flex align-items-center justify-content-center w-100">
                <StorefrontOutline class="mx-auto" :size="27" fillColor="#64676B" />
            </div>
            <div class="list_items d-flex align-items-center justify-content-center w-100">
                <span class="rounded-full border-[2px] border-[#64676B] p-1">
                    <AccountGroup class="mx-auto" :size="22" fillColor="#64676B" />
                </span>
            </div>
            <div class="list_items d-flex align-items-center justify-content-center w-100">
                <ControllerClassicOutline class="mx-auto" :size="32" fillColor="#64676B" />
            </div>
        </div>
        <div id="NavLeft" class="d-flex align-items-center justify-content-end">
            <div class="nav_left_items">
                <DotsGrid :size="23" fillColor="#050505" />
            </div>
            <div class="nav_left_items">
                <FacebookMessenger :size="23" fillColor="#050505" />
            </div>
            <div class="nav_left_items">
                <Bell :size="23" fillColor="#050505" />
            </div>
            <div class="d-flex align-items-center justify-content-center position-relative">
                <div @click="showMenu = !showMenu">
                    <img :src="authUser.avatar" alt="" class="custom">
                </div>
                <div v-if="showMenu" class="show_menu position-absolute">
                    <router-link :to="{name:'Profile User'}" @click="showMenu = !showMenu">
                        <div class="d-flex menu_item align-items-center gap-3 ">
                            <img :src="authUser.avatar" alt="" class="custom">
                            <span>{{ authUser.user_name }}</span>
                        </div>
                    </router-link>
                    <a @click="logoutSubmit" class="w-full  ml-2" as="button" method="post">
                        <div class="d-flex menu_item align-items-center justify-content-center gap-1">
                            <Logout :size="30" class="pl-2" />
                            <span>Logout</span>
                        </div>
                    </a>

                </div>
            </div>
        </div>
        <CreatePostOverLay v-if="isPostOverlay" />
        <!--  <CropperModal v-if="isCropperModal" @showModal="isCropperModal = false" /> -->
        <MediaDisplay v-if="isFileDisplay.length > 0" />

    </div>
</template>
<script>
import { mapGetters, mapMutations, mapActions } from "vuex"
import { useGeneralStore } from '../../../store/general';
import { ref } from 'vue';
import { storeToRefs } from 'pinia';
import CropperModal from '../components/CropperModal.vue'
import MediaDisplay from '../Components/mediaDisplay.vue'
import CreatePostOverLay from '../Components/CreatePostOverLay.vue'

export default {
    components: {
        CropperModal,
        MediaDisplay,
        CreatePostOverLay
    },
    data() {
        const useGeneral = useGeneralStore();
        const { isPostOverlay, isCropperModal, isFileDisplay } = storeToRefs(useGeneral)
        return {
            showMenu: false,
            isPostOverlay,
            isCropperModal,
            isFileDisplay
        }
    },
    computed: {
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
    },
    methods: {
        ...mapActions(["logout"]),

        logoutSubmit() {
            this.logout();
        },

    }
}
</script>