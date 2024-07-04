<script setup>
import Close from 'vue-material-design-icons/Close.vue'

</script>

<template>
    <div id="MediaDisplay">
        <Close @click="isFileDisplay = []" fillColor="#000000" :size="30" class="imagedisplay_close" />
        <div class="media_display" v-if="isVideo(isFileDisplay) || typeFile(isFileDisplay)">
            <video class="displ" controls>
                <source :src="isFileDisplay" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div v-if="isImage(isFileDisplay) || typeFile(isFileDisplay)" class="media_display">
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
        typeFile(fileUrl) {
            const extension = this.getFileExtension(fileUrl);
            if (extension === 'jpg' || extension === 'jpeg' || extension === 'png' || extension === 'gif') {
                return true;
            } else if (extension === 'mp4' || extension === 'mov' || extension === 'avi') {
                return true;
            } else {
                console.log("Unknown file type.");
                return false;
            }
        },
    }
}
</script>