<template>
    <TopBar />
    <div class="wrapper_customs">
        <div class="wrapper_2">
            <SidebarLeft />
            <router-view></router-view>
            <SidebarRight />
        </div>
    </div>
    <ChatBox v-if="isChatBoxOverLay" />
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
        const { isChatBoxOverLay } = storeToRefs(useGeneral)
        return {
            isChatBoxOverLay
        }
    },
    method: {
     
    }
}
</script>