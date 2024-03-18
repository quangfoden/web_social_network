<script setup>
import VideoImage from 'vue-material-design-icons/VideoImage.vue'
import Image from 'vue-material-design-icons/Image.vue'
import EmoticonOutline from 'vue-material-design-icons/EmoticonOutline.vue'
import Close from 'vue-material-design-icons/Close.vue'
import ChevronDown from 'vue-material-design-icons/ChevronDown.vue'
import Earth from 'vue-material-design-icons/Earth.vue'
import DotsHorizontal from 'vue-material-design-icons/DotsHorizontal.vue'
const emit = defineEmits(['showModal'])
</script>
<template>
    <div id="CreatePostOverlay">
        <div class="wrapper_crpost">
            <div class="w-100 crpost">
                <div class="d-flex align-items-center position-relative my-3 mx-1">
                    <div class="w-100 text-center">Tạo bài viết</div>
                    <div class="close position-absolute rounded-full custom-cursor-pointer"
                        @click="isPostOverlay = false">
                        <Close :size="20" fillColor="#5E6771" />
                    </div>
                </div>
                <div class="border-top">
                    <form @submit.prevent="submitPost" method="post" enctype="multipart/form-data" class="p-4">
                        <div class="d-flex align-items-center">
                            <img :src="authUser.avatar" class="rounded-full img-cus" alt="">
                            <div class="mx-2">
                                <div class="font-extrabold">{{ authUser.user_name }}</div>
                                <select v-model="form.privacy" id="privacy" required>
                                    <option v-for="option in privacyOptions" :value="option.value">{{ option.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="text-ar">
                            <textarea cols="30" v-model="form.content" class="w-100"
                                :placeholder="'bạn đang nghĩ gì ' + authUser.user_name + '...'">
                            </textarea>
                            <div v-if="imageUrls" class="p-2 position-relative cus-img-dis">
                                <div v-for="(image) in imageUrls" :key="index">
                                    <Close @click="clearImage(image)"
                                        class="position-absolute bg-white p-1 m-2 right-2 z-1000 rounded-full border custom-cursor-pointer"
                                        :size="22" fillColor="#5E6771" />
                                    <div><img class="rounded-lg mx-auto w-100" :src="image.url" alt=""></div>
                                    <!-- <div v-if="image.type === 'video'"> <video class="rounded-lg mx-auto w-100"
                                            controls>
                                            <source :src="image.url" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div> -->
                                </div>

                            </div>
                        </div>
                        <div
                            class="border-2 rounded-xl mt-4 shadow-sm d-flex align-items-center justify-content-between">
                            <div class="font-extrabold w-100 d-block">Tạo bài viết của bạn</div>
                            <div class="d-flex align-items-center">
                                <label class="hover-200 rounded-full p-2 custom-cursor-pointer" for="image">
                                    <Image :size="27" fillColor="#43BE62" />
                                </label>
                                <input type="file" ref="fieldCreatePost" id="image" accept="image/*,video/*" multiple
                                    class="hidden" @input="onFileChange($event)">
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
                            Đăng
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
    data() {
        const useGeneral = useGeneralStore()
        const { isPostOverlay, isFileDisplay } = storeToRefs(useGeneral)
        return {
            privacyOptions: [
                { name: "Công khai", value: "public" },
                { name: "Bạn bè", value: "friends" },
                { name: "Chỉ mình tôi", value: "only_me" },
            ],
            isPostOverlay,
            isFileDisplay,
            form: ref({
                content: '',
                privacy: ref('public'),
            }),
            files: ref([]),
            imageUrls: ref([]),
            deletedImages: ref([]),
            imagePositions: ref([])
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
    methods: {
        ...mapActions('post', ['addNewPost']),
        submitPost() {
            const formData = new FormData();
            formData.append('content', this.form.content);
            formData.append('privacy', this.form.privacy);
            for (let file of this.files) {
                file.id = uuidv4();
                formData.append('files[]', file);
            }
            this.$store.dispatch('post/addNewPost', formData)
                .then(() => {
                    this.form.content = null;
                    this.form.media = [];
                    setTimeout(() => {
                        this.isPostOverlay = false
                    }, 3000);
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
                });
        },
        onFileChange($event) {
            const chosenFiles = [...$event.target.files];
            this.files = [...this.files, ...chosenFiles];
            $event.target.value = ''
            const allPromises = [];
            for (let file of chosenFiles) {
                file.id = uuidv4()
                const promise = this.readFile(file)
                allPromises.push(promise)
                promise
                    .then(url => {
                        this.imageUrls.push({
                            url,
                            id: file.id
                        })
                    })
            }
            Promise.all(allPromises)
                .then(() => {
                    this.updateImagePositions()
                })
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
            this.files = this.files.filter(f => f.id !== image.id)
            this.imageUrls = this.imageUrls.filter(f => f.id !== image.id)
        },
        updateImagePositions() {
            /**
             * [
             *   [1, 1],
             *   [4, 2],
             *   [5, 3],
             * ]
             */
            this.imagePositions = Object.fromEntries(
                this.imageUrls.filter(im => !im.deleted)
                    .map((im, ind) => [im.id, ind + 1])
            )

        }
    }
}
</script>