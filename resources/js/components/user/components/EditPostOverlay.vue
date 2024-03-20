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
    <div id="CreatePostOverlay">
        <div class="wrapper_crpost">
            <div class="w-100 crpost">
                <div class="d-flex align-items-center position-relative my-3 mx-1">
                    <div class="w-100 text-center">Chỉnh sửa bài viết</div>
                    <div class="close position-absolute rounded-full custom-cursor-pointer" @click="closeModalEditPost">
                        <Close :size="20" fillColor="#5E6771" />
                    </div>
                </div>
                <div class="border-top">
                    <form @submit.prevent="submitEditPost(postEdit.id)" method="post" enctype="multipart/form-data"
                        class="p-4">
                        <div class="d-flex align-items-center">
                            <img :src="authUser.avatar" class="rounded-full img-cus" alt="">
                            <div class="mx-2">
                                <div class="font-extrabold">{{ authUser.user_name }}</div>
                                <select v-model="form.privacyPostEdit" id="privacy" required>
                                    <option v-for="option in privacyOptions" :value="option.value">{{ option.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="text-ar">
                            <textarea cols="30" v-model="form.contentPostEdit" class="w-100">
                            </textarea>
                            <div v-if="imageUrls" class="p-2 position-relative cus-img-dis">
                                <div v-for="(image) in  imageUrls " :key="index">
                                    <Undo v-show="image.deleted" @click="revertImage(image)"
                                        class="position-absolute bg-secondary p-1 m-2 z-1000 right-2 rounded-full border custom-cursor-pointer"
                                        :size="22" fillColor="#5E6771" />
                                    <div :class="{ 'opacity-50': image.deleted }">
                                        <Close style="z-index: 999;" v-show="!image.deleted" @click="clearImage(image)"
                                            class="position-absolute bg-white p-1 m-2 right-2 rounded-full border custom-cursor-pointer"
                                            :size="22" fillColor="#5E6771" />
                                        <div v-if="image.type === 'image' || image.type === 'image/jpeg'">
                                            <img class=" rounded-lg mx-auto w-100" :src="image.url" alt="">
                                        </div>
                                        <div v-else="image.type === 'video' || image.type==='video/mp4'"> <video
                                                class="rounded-lg mx-auto w-100" autoplay controls>
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
                        </div>
                        <div
                            class="border-2 rounded-xl mt-4 shadow-sm d-flex align-items-center justify-content-between">
                            <div class="font-extrabold w-100 d-block">Thêm vào bài viết của bạn</div>
                            <div class="d-flex align-items-center">
                                <label class="hover-200 rounded-full p-2 custom-cursor-pointer" for="image">
                                    <Image :size="27" fillColor="#43BE62" />
                                </label>
                                <input type="file" @change="onFileChange($event)" ref="EditPost" id="image"
                                    accept="image/*,video/*" multiple class="">
                                <a class="hover-200 rounded-full p-2 custom-cursor-pointer">
                                    <EmoticonOutline :size="27" fillColor="#F8B927" />
                                </a>
                                <a class="hover-200 rounded-full p-2 custom-cursor-pointer">
                                    <VideoImage :size="27" fillColor="#F12848" />
                                </a>
                                <a class="hover-200 rounded-full p-2 custom-cursor-pointer">
                                    <DotsHorizontal :size="27" fillColor="#050505" />
                                </a>
                            </div>
                        </div>
                        <button type="submit"
                            class="w-100 bg-blue-500 hover-bg-blue-600 text-white font-extrabold p-1 mt-3 rounded-lg">
                            Lưu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
        isEditPostOverlay: {
            type: Boolean,
            default: true
        }
    },
    data() {
        const useGeneral = useGeneralStore()
        const { isEditPostOverlay, isFileDisplay } = storeToRefs(useGeneral)
        return {
            privacyOptions: [
                { name: "Công khai", value: "public" },
                { name: "Bạn bè", value: "friends" },
                { name: "Chỉ mình tôi", value: "only_me" },
            ],
            isEditPostOverlay,
            isFileDisplay,
            form: ref({
                contentPostEdit: this.postEdit.content,
                privacyPostEdit: this.postEdit.privacy,
            }),
            deletedImages: ref([]),
            files: ref([]),
            imageUrls: ref([]),
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
            const formData = new FormData();
            formData.append('content', this.form.contentPostEdit);
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
                    this.closeModalEditPost()
                })
                .catch(error => {
                    // Xử lý khi có lỗi
                    this.$swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: error,
                        showConfirmButton: false,
                        timer: this.$config.notificationTimer ?? 3000,
                    });
                    this.isEditPostOverlay = false
                });
        },
        getFileType(file) {
            // Lấy phần mở rộng của tệp
            const extension = file.name.split('.').pop().toLowerCase();
            // Kiểm tra nếu phần mở rộng là của ảnh
            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (imageExtensions.includes(extension)) {
                return 'image';
            }
            // Kiểm tra nếu phần mở rộng là của video
            const videoExtensions = ['mp4', 'avi', 'mov', 'mkv'];
            if (videoExtensions.includes(extension)) {
                return 'video';
            }
            // Nếu không phải là ảnh hoặc video, trả về null
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
                console.log(newImages);
                console.log(this.imageUrls);
            },
            { immediate: true, deep: true }
        );
    },
}
</script>