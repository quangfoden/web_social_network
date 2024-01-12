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
                    <div class="close position-absolute rounded-full custom-cursor-pointer" @click="isPostOverlay = false">
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
                            <div v-if="form.media" class="p-2 position-relative cus-img-dis">
                                <div v-for="(media, index) in form.media" :key="index">
                                    <Close @click="clearImage(index)"
                                        class="position-absolute bg-white p-1 m-2 right-2 z-1000 rounded-full border custom-cursor-pointer"
                                        :size="22" fillColor="#5E6771" />
                                    <div v-if="media.type === 'image'"><img class="rounded-lg mx-auto w-100"
                                            :src="media.url" alt=""></div>
                                    <div v-if="media.type === 'video'"> <video class="rounded-lg mx-auto w-100" controls>
                                            <source :src="media.url" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border-2 rounded-xl mt-4 shadow-sm d-flex align-items-center justify-content-between">
                            <div class="font-extrabold w-100 d-block">Tạo bài viết của bạn</div>
                            <div class="d-flex align-items-center">
                                <label class="hover-200 rounded-full p-2 custom-cursor-pointer" for="image">
                                    <Image :size="27" fillColor="#43BE62" />
                                </label>
                                <input type="file" ref="fieldCreatePost" id="image" accept="image/*,video/*" multiple class="hidden"
                                    @input="getUploadedImage($event)">
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
import { useGeneralStore } from '../../../../js/store/general';
import { storeToRefs } from 'pinia';
import { ref, reactive } from 'vue'
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
            medias: [],
            form: reactive({
                content: null,
                media: [],
                privacy: ref('public'),
            }),

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
        ...mapActions('post', ['fetchPosts']),
        submitPost() {
            this.$store.dispatch('post/addNewPost', this.form)
                .then((addedPost) => {
                    this.isPostOverlay = false
                    this.fetchPosts()
                    this.$swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Bài viết được thêm thành công",
                        showConfirmButton: false,
                        timer: this.$config.notificationTimer ?? 3000,
                    });
                    this.form = {};
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
        getUploadedImage(e) {
            for (let i = 0; i < e.target.files.length; i++) {
                const file = e.target.files[i];
                let mediaType;

                if (file.type.startsWith('image/')) {
                    mediaType = 'image';
                } else if (file.type.startsWith('video/')) {
                    mediaType = 'video';
                }
                const url = URL.createObjectURL(file);
                this.form.media.push({ type: mediaType, file, url });
            }
        },
        clearImage(index) {
            this.form.media.splice(index, 1);
        }
    }
}
</script>