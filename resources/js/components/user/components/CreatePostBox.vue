<script setup>


import VideoImage from 'vue-material-design-icons/VideoImage.vue';
import Image from 'vue-material-design-icons/Image.vue';
import EmoticonOutline from 'vue-material-design-icons/EmoticonOutline.vue';

</script>

<template>
    <div id="CreatePostBox" class="">
        <div class="d-flex align-items-center py-3 border-bottom">
            <a href="/">
                <img class="img-pr" :src="image" alt="">
            </a>
            <div @click="showCreatepostBox" class="custom-cursor-pointer d-flex align-items-center justify-content-start">
                <div class="text-left pl-2">
                    {{ placeholder }}
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center py-3 border-bottom">
            <button class="icon-cus d-flex align-items-center justify-content-center p-1 w-100">
                <VideoImage :size="35" fillColor="#F12848" />
                <div class="text-cus">Live Video</div>
            </button>
            <button class="icon-cus d-flex align-items-center justify-content-center p-1 w-100">
                <Image :size="35" fillColor="#43BE62" />
                <div class="text-cus">PhoTo/Video</div>
            </button>
            <button class="icon-cus d-flex align-items-center justify-content-center p-1 w-100">
                <EmoticonOutline :size="35" fillColor="#F8B927" />
                <div class="text-cus">Feeling/activity</div>
            </button>
        </div>
    </div>
</template>
<script>
import { useGeneralStore } from '../../../store/general';
import { storeToRefs } from 'pinia';
import { ref } from 'vue';
export default {
    data() {
        const useGeneral = useGeneralStore()
        const { isPostOverlay } = storeToRefs(useGeneral)
        return {
            isPostOverlay
        }
    },
    props: {
        image: String,
        placeholder: String
    },
    methods: {
        showCreatepostBox() {
            this.isPostOverlay = true
        }
    }
}
</script>