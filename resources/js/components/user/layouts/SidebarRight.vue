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
    <div id="RightSection" class="mb-3">
        <div class="rightsection_cus">
            <div class="d-flex align-items-center justify-content-between">
                <div v-if="$route.path === '/'" class="font-semibold primary-text">Liên hệ</div>
                <div v-else="$route.path.startsWith('profile')" class="font-semibold primary-text">Liên hệ chung</div>
                <div class="d-flex align-items-center">
                    <div class="icon-right">
                        <VideoImage :size="23" fillColor="#fff" />
                    </div>
                    <div class="icon-right">
                        <Magnify :size="23" fillColor="#fff" />
                    </div>
                    <div class="icon-right">
                        <DotsHorizontal :size="23" fillColor="#fff" />
                    </div>
                </div>
            </div>
            <div class="list_friends">
                <div @click="fetchFriendChat(account.id)" v-for="(account, index) in accounts" :key="account.id"
                    class="d-flex align-items-center justify-content-start friend_pr ">
                    <img class="" :src="account.avatar" alt="">
                    <div class="text-pr primary-text">{{ account.user_name }}</div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState, mapActions, mapGetters } from 'vuex';
import { useGeneralStore } from '../../../store/general';
import { storeToRefs } from 'pinia';
export default {
    data() {
        const useGeneral = useGeneralStore();
        const { isChatBoxOverLay, isLoadingChatBox } = storeToRefs(useGeneral)
        return {
            isChatBoxOverLay,
            isLoadingChatBox
        }
    },
    mounted() {
        console.log(this.$route.path);
    },
    computed: {
        ...mapState({
            accounts: state => state.users.accounts
        }),
    },
    methods: {
        ...mapActions('chat', ['getFriend']),
        fetchFriendChat(accountId) {
            this.isLoadingChatBox = true
            this.isChatBoxOverLay = true
            this.getFriend(accountId)
                .then(response => {
                    this.isLoadingChatBox = false
                })
                .catch(error => {
                    console.error("Error in fetchFriendChat:", error);
                    this.isLoadingChatBox = true
                });
        },
    }
}
</script>