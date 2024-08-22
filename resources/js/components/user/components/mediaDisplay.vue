<script setup>
import Close from 'vue-material-design-icons/Close.vue'

</script>

<template>
    <div id="MediaDisplay">
        <Close @click="isFileDisplay = []" fillColor="#000000" :size="30" class="imagedisplay_close" />
        <div class="media_display" v-if="isVideo(isFileDisplay) || fileType === 'video'">
            <video class="displ" controls autoplay>
                <source :src="isFileDisplay" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div v-if="isImage(isFileDisplay) || fileType === 'image'" class="media_display">
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
    computed: {
        fileType() {
            const extension = this.isFileDisplay.split('.').pop().split(/\#|\?/)[0];
            if (['mp4', 'avi', 'mov'].includes(extension)) {
                return 'video';
            } else if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
                return 'image';
            }
            return 'unknown';
        }
    },
    methods: {
        getFileExtension(url) {
            const index = url.lastIndexOf('.');
            const extension = url.substring(index + 1).toLowerCase();
            return extension;
        },
        isImage(fileUrl) {
            const imageExtensions = /\.(jpg|jpeg|png|gif)$/i;
            return imageExtensions.test(fileUrl);
        },
        isVideo(fileUrl) {
            const videoExtensions = /\.(mp4|webm|ogg)$/i;
            return videoExtensions.test(fileUrl);
        },
    },
    mounted() {
    }
}
</script>