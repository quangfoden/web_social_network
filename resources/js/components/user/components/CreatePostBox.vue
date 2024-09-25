<template>
    <div class="central-meta postbox">
    <span v-if="isLoading" class="loader"></span>
        <span class="create-post">Cập nhật trạng thái ?</span>
        <div class="new-postbox">
            <figure>
                <img class="img-user" width="40" height="40" :src="image" alt="">
            </figure>
            <div class="newpst-input">
                <form @submit.prevent="submitPost" enctype="multipart/form-data">
                    <select v-if="isPostOverlay" class="bg-item primary-text border" v-model="form.privacy" id="privacy"
                        required>
                        <option v-for="option in privacyOptions" :value="option.value">{{ option.name }}
                        </option>
                    </select>
                    <textarea @focus="isPostOverlay = true" v-model="form.content" rows="2" :placeholder="placeholder"></textarea>
                    <div v-if="imageUrls && isPostOverlay" class="p-2 position-relative cus-img-dis">
                        <div class="items" v-for="(image) in imageUrls" :key="index">
                            <!-- <Close @click="clearImage(image)"
                                class="position-absolute bg-white p-1 m-2 right-2 z-1000 rounded-full border custom-cursor-pointer"
                                :size="22" fillColor="#5E6771" /> -->
                                <span @click="clearImage(image)" style="z-index: 2000;" class="position-absolute bg-white p-1 m-2 right-2 z-1000 rounded-full border custom-cursor-pointer">
                                    <i class="fas fa-close"></i>
                                </span>
                            <div v-if="image.fileType === 'image'">
                                <img class="rounded-lg mx-auto w-100" :src="image.url" alt="">
                            </div>
                            <div v-else-if="image.fileType === 'video'">
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
                    <div @click="isPostOverlay = true" class="attachments mt-4">
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
                        <button v-if="isPostOverlay && !isLoading" :disabled="isSubmitDisabled" class="post-btn" type="submit">Đăng</button>
                        <button v-if="isPostOverlay && isLoading" disabled class="post-btn" type="submit">Đang tải...</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- add post new box -->
</template>
<script>
import { mapState, mapActions } from 'vuex';
import { useGeneralStore } from '../../../store/general';
import { storeToRefs } from 'pinia';
import { ref } from 'vue';
import { v4 as uuidv4 } from 'uuid';
export default {
    data() {
        const useGeneral = useGeneralStore()
        const { isPostOverlay } = storeToRefs(useGeneral)
        return {
            isPostOverlay,
            privacyOptions: [
                { name: "Công khai", value: "public" },
                { name: "Bạn bè", value: "friends" },
                { name: "Chỉ mình tôi", value: "only_me" },
            ],
            form: ref({
                content: '',
                privacy: ref('public'),
            }),
            files: ref([]),
            imageUrls: ref([]),
            deletedImages: ref([]),
            imagePositions: ref([]),
            isLoading: false
        }
    },
    props: {
        image: String,
        placeholder: String,
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
            return this.form.content === '' && this.files.length === 0;
        },
    },
    methods: {
        ...mapActions('post', ['addNewPost']),
        submitPost() {
            this.isLoading = true
            const formData = new FormData();
            formData.append('content', this.form.content);
            formData.append('privacy', this.form.privacy);
            for (let file of this.files) {
                file.id = uuidv4();
                formData.append('files[]', file);
            }
            this.$store.dispatch('post/addNewPost', formData)
                .then(() => {
                    this.isLoading = false
                    this.form.content = '';
                    this.form.media = [];
                    formData.values = ''
                    this.isPostOverlay = false
                })
                .catch(error => {
                    this.isLoading = false
                    this.form.content = '';
                    this.form.media = [];
                    formData.values = ''
                    this.isPostOverlay = false
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
                const fileType = this.getFileType(file);
                const promise = this.readFile(file)
                allPromises.push(promise)
                promise
                    .then(url => {
                        this.imageUrls.push({
                            url,
                            id: file.id,
                            fileType
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
            this.files = this.files.filter(f => f.id !== image.id)
            this.imageUrls = this.imageUrls.filter(f => f.id !== image.id)
        },
    }
}
</script>