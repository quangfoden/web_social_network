<script setup>
import VideoImage from 'vue-material-design-icons/VideoImage.vue'
import Image from 'vue-material-design-icons/Image.vue'
import EmoticonOutline from 'vue-material-design-icons/EmoticonOutline.vue'
import Close from 'vue-material-design-icons/Close.vue'
import Undo from 'vue-material-design-icons/Undo.vue'
import Earth from 'vue-material-design-icons/Earth.vue'
import DotsHorizontal from 'vue-material-design-icons/DotsHorizontal.vue'
const emit = defineEmits(['showModal'])

</script>
<template>
    <div class="edit-post-overlay">
        <span @click="closeModalEditPost"
            style="cursor: pointer;position: absolute;z-index:1;right: 10px;top: 10px;padding: 5px;"><i
                style="font-size: 20px;" class="fas fa-close"></i></span>
        <span v-if="isLoading" class="loader"></span>
        <span class="create-post">Chỉnh sửa trạng thái</span>
        <div class="new-postbox">
            <figure>
                <img class="img-user" width="40" height="40" :src="authUser.avatar" alt="">
            </figure>
            <div class="newpst-input">
                <form @submit.prevent="submitEditPost(postEdit.id)" enctype="multipart/form-data">
                    <select class="bg-item primary-text border" v-model="form.privacyPostEdit" id="privacy" required>
                        <option v-for="option in privacyOptions" :value="option.value">{{ option.name }}
                        </option>
                    </select>
                    <textarea v-model="form.contentPostEdit" rows="2" :placeholder="placeholder"></textarea>
                    <div v-if="imageUrls" class="p-2 position-relative cus-img-dis">
                        <div class="items" v-for="(image) in imageUrls" :key="index">
                            <!-- <Close @click="clearImage(image)"
                                class="position-absolute bg-white p-1 m-2 right-2 z-1000 rounded-full border custom-cursor-pointer"
                                :size="22" fillColor="#5E6771" /> -->
                            <Undo style="z-index: 1;" v-show="image.deleted" @click="revertImage(image)"
                                class="position-absolute bg-white p-1 m-2 z-1000 right-2 rounded-full border custom-cursor-pointer"
                                :size="22" fillColor="#5E6771" />
                            <div :class="{ 'opacity-50': image.deleted }">
                                <span @click="clearImage(image)" v-show="!image.deleted" style="z-index: 2000;"
                                    class="position-absolute bg-white p-1 m-2 right-2 z-1000 rounded-full border custom-cursor-pointer">
                                    <i class="fas fa-close"></i>
                                </span>
                                <div v-if="image.type.includes('image')">
                                    <img class="rounded-lg mx-auto w-100" :src="image.url" alt="">
                                </div>
                                <div v-else-if="image.type.includes('video')">
                                    <video class="rounded-lg mx-auto w-100" autoplay controls>
                                        <source :src="image.url" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                <div v-else class="createFile_" style="word-wrap: break-word;">
                                    <a :href="image.url" target="_blank">
                                        {{ image.url }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="attachments mt-4">
                        <ul>
                            <li>
                                <i class="fa fa-music"></i>
                                <label for="music" class="fileContainer">
                                    <input id="music" title="Âm nhạc" type="file">
                                </label>
                            </li>
                            <li>
                                <i class="fas fa-photo-video"></i>
                                <label for="image-video" class="fileContainer">
                                    <input @input="onFileChange($event)" title="Ảnh/Video" id="image-video"
                                        accept="image/*,video/*" multiple type="file">
                                </label>
                            </li>
                        </ul>
                        <button v-if="!isLoading" :disabled="isSubmitDisabled" class="post-btn" type="submit">Cập
                            nhật</button>
                        <button v-if="isLoading" disabled class="post-btn" type="submit">Đang
                            cập nhật...</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- edit post new box -->
</template>
<script>
import { mapState, mapActions } from 'vuex';
import { useGeneralStore } from '@resources/js/store/general';
import { storeToRefs } from 'pinia';
import { ref, reactive } from 'vue'
import { v4 as uuidv4 } from 'uuid';
export default {
    props: {
        postEdit: {
            type: Object,
            required: true,
        },
        medias: {
            type: Object,
            required: true,
        },
    },
    data() {
        const useGeneral = useGeneralStore()
        const { isFileDisplay } = storeToRefs(useGeneral)
        return {
            privacyOptions: [
                { name: "Công khai", value: "public" },
                { name: "Bạn bè", value: "friends" },
                { name: "Chỉ mình tôi", value: "only_me" },
            ],
            isFileDisplay,
            form: ref({
                contentPostEdit: this.postEdit.content,
                privacyPostEdit: this.postEdit.privacy,
            }),
            deletedImages: ref([]),
            files: ref([]),
            imageUrls: ref([]),
            isLoading: false,
        }
    },
    computed: {
        ...mapState('post', ['posts']),
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
        isSubmitDisabled() {
            return this.form.contentPostEdit === '' && this.files.length === 0;
        },
    },
    created() {

    },
    methods: {
        ...mapActions('post', ['addNewPost']),
        ...mapActions('post', ['editPost']),

        closeModalEditPost() {
            this.$emit('close-modalEditPost');
        },
        submitEditPost(postId) {
            this.isLoading = true
            const formData = new FormData();
            formData.append('content', this.form.contentPostEdit ? this.form.contentPostEdit : '');
            formData.append('privacy', this.form.privacyPostEdit);
            for (let deletedImage of this.deletedImages) {
                formData.append('deletedImages[]', deletedImage);
            }
            for (let file of this.files) {
                file.id = uuidv4();
                formData.append('files[]', file);
            }
            this.$store.dispatch('post/editPost', { postId: postId, formData: formData })
                .then(() => {
                    formData.values = ''
                    this.isLoading = false
                    this.closeModalEditPost()
                })
                .catch(error => {
                    this.isLoading = false
                    this.$swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: error,
                        showConfirmButton: false,
                        timer: this.$config.notificationTimer ?? 3000,
                    });
                    this.closeModalEditPost()
                });
        },
        getFileType(file) {
            const extension = file.name.split('.').pop().toLowerCase();
            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'jfif'];
            if (imageExtensions.includes(extension)) {
                return 'image';
            }
            const videoExtensions = ['mp4', 'avi', 'mov', 'mkv'];
            if (videoExtensions.includes(extension)) {
                return 'video';
            }
            return null;
        },
        onFileChange($event) {
            const chosenFiles = [...$event.target.files];
            this.files = [...this.files, ...chosenFiles];
            $event.target.value = ''
            const allPromises = [];
            for (let file of chosenFiles) {
                file.id = uuidv4()
                const promise = this.readFile(file)
                const type = this.getFileType(file);
                allPromises.push(promise)
                promise
                    .then(url => {
                        this.imageUrls.push({
                            url,
                            id: file.id,
                            type
                        })
                    })
            }
        },
        readFile(file) {
            return new Promise((resolve, reject) => {
                const fileReader = new FileReader()
                fileReader.readAsDataURL(file)
                fileReader.onload = () => {
                    resolve(fileReader.result)
                }
                fileReader.onerror = reject
            })
        },
        clearImage(image) {
            if (image.isProp) {
                this.deletedImages.push(image.id)
                image.deleted = true;
            } else {
                this.files = this.files.filter(f => f.id !== image.id)
                this.imageUrls = this.imageUrls.filter(f => f.id !== image.id)
            }
        },
        revertImage(image) {
            this.deletedImages = this.deletedImages.filter(id => id !== image.id)
            image.deleted = false;
        }
    },
    created() {
        this.$watch(
            () => this.medias,
            (newImages, oldImages) => {
                this.imageUrls = [
                    ...this.imageUrls,
                    ...newImages.map(im => ({ ...im, isProp: true, deleted: false }))
                ];
            },
            { immediate: true, deep: true }
        );
    },
}
</script>