<template>
    <router-view></router-view>
    <ChatBox v-if="isChatBoxOverLay" />
</template>
<script>
import { useGeneralStore } from '../../store/general';
import { storeToRefs } from 'pinia';
import ChatBox from './components/ChatBox.vue';
export default {
    components: {
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