<template>
    <div @click="isPostOverlay = false" v-show="isPostOverlay" class="postoverlay"></div>
    <TopBar />
    <router-view></router-view>
    <SidebarRight />
    <SidebarLeft />
</template>
<script>
import { useGeneralStore } from '../../../store/general';
import { storeToRefs } from 'pinia';
import SidebarLeft from './SidebarLeft.vue';
import SidebarRight from './SidebarRight.vue';
import TopBar from './TopBar.vue';
import ChatBox from '../components/ChatBox.vue';
export default {
    components: {
        SidebarLeft,
        SidebarRight,
        TopBar,
        ChatBox
    },
    data() {
        const useGeneral = useGeneralStore();
        const { isChatBoxOverLay,isEditPostOverlay, isPostOverlay } = storeToRefs(useGeneral)
        return {
            isChatBoxOverLay,
            isPostOverlay,
            isEditPostOverlay
        }
    },
    method: {

    }
}
</script>