<script setup>
import Close from 'vue-material-design-icons/Close.vue'

</script>

<template>
    <div id="MediaDisplay">
        <Close @click="isFileDisplay = []" fillColor="#000000" :size="30" class="imagedisplay_close" />
        <div class="media_display" v-if="isVideo(isFileDisplay)">
            <video class="displ" controls>
                <source :src="isFileDisplay" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div v-else class="media_display">
            <img class="displ" :src="isFileDisplay" alt="Image">
        </div>

    </div>
</template>
<script>
import { toRefs, reactive } from 'vue';
import { useGeneralStore } from '../../../store/general';
import { storeToRefs } from 'pinia';
export default {
    data() {
        const useGeneral = useGeneralStore()
        const { isFileDisplay } = storeToRefs(useGeneral)
        return {
            isFileDisplay,
        }
    },
    methods: {
        isImage(filename) {
            // Kiểm tra đuôi mở rộng để xác định là ảnh hay không
            return /\.(jpg|jpeg|png|gif)$/i.test(filename);
        },
        isVideo(filename) {
            // Kiểm tra đuôi mở rộng để xác định là video hay không
            return /\.(mp4|webm|ogg)$/i.test(filename);
        },
    }
}
</script>