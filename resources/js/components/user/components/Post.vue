<script setup>
import Image from 'vue-material-design-icons/Image.vue'
import AccountMultiple from 'vue-material-design-icons/AccountMultiple.vue';
import ThumbUp from 'vue-material-design-icons/ThumbUp.vue';
import Check from 'vue-material-design-icons/Check.vue';
import Delete from 'vue-material-design-icons/Delete.vue';
import Close from 'vue-material-design-icons/Close.vue'

</script>
<template>
    <div id="post">
        <div class="d-flex align-items-center py-3 px-0">
            <a class="mr-2">
                <img class="img-cus" :src="user.avatar" alt="">
            </a>
            <div class="d-flex align-items-center justify-content-between p-2 rounded-full w-100">
                <div>
                    <div class="text-pr">{{ user.user_name }}</div>
                    <div class="d-flex align-items-center text-xs text-gray-600">
                        {{ post.created_at_formatted }}
                        <i v-if="post.privacy === 'public'" class="mx-2 fas fa-globe"></i>
                        <i v-if="post.privacy === 'friends'" class="mx-2 fas fa-user-friends"></i>
                        <i v-if="post.privacy === 'only_me'" class="mx-2 fas fa-lock"></i>
                    </div>
                </div>
            </div>
            <!-- <div class="d-flex align-items-center">
                <a class="rounded-full p-1 custom-cursor-pointer">
                    <Delete :size="20" fillColor="#64676B" />
                </a>
            </div> -->
        </div>
        <div class="px-1 pb-2 text-cus-pos">
            {{ post.content }}
        </div>
        <div class="cus-post-media">
            <div v-for="medias in media" :key="media.id">
                <div class="" v-if="medias.type === 'image'">
                    <img @click="isFileDisplay = medias.path" :src="medias.path" alt="Image"
                        class="mx-auto custom-cursor-pointer w-100">
                </div>
                <div class="" v-else-if="medias.type === 'video'">
                    <video @click="isFileDisplay = medias.path" :src="medias.path"
                        class="mx-auto custom-cursor-pointer w-100">
                    </video>
                </div>
            </div>
        </div>
        <div @click="getAllComment" id="Likes" class="px-5">
            <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                <ThumbUp :size="16" fillColor="#1D72E2" />
                <div class="text-sm text-gray-600 font-semibold">5 bình luận</div>
            </div>
        </div>
        <div id="comments" class="px-3">
            <div id="CreateComment" class="">
                <form @submit.prevent="CreateComment" class="d-flex align-items-center justify-content-between w-100">
                    <a href="/" class="mr-2">
                        <img class="rounded-full ml-1 img-cus" :src="authUser.avatar" alt="">
                    </a>
                    <div class="d-flex align-items-center bg-EFF2F5 p-2 rounded-full w-100">
                        <input v-model="formMediaComment.content" type="text" placeholder="Bình luận ..."
                            class="w-100 border-0 mx-1 border-none p-0 text-sm bg-EFF2F5 placeholder-[#64676B] ring-0 focus:ring-0">

                        <label class="hover-200 rounded-full p-2 custom-cursor-pointer" for="image">
                            <Image :size="27" fillColor="#43BE62" />
                        </label>
                        <input ref="fieldMedia" type="file" id="image" accept="image/*,video/*"
                            @input="getUploadedImage($event)">
                        <button type="submit"
                            class="d-flex border-0 align-items-center text-sm px-3 rounded-full bg-blue-500 hover:bg-blue-600 text-white font-bold">
                            <Check />Gửi
                        </button>
                    </div>
                </form>

            </div>
            <div v-if="formMediaComment.url" class="p-2 position-relative cus-img-dis">
                <Close @click="clearImage()"
                    class="position-absolute bg-white p-1 m-2 right-2 z-1000 rounded-full border custom-cursor-pointer"
                    :size="22" fillColor="#5E6771" />
                <div v-if="formMediaComment.type === 'image'"><img class="rounded-lg mx-auto w-50"
                        :src="formMediaComment.url" alt=""></div>
                <div v-if="formMediaComment.type === 'video'"> <video class="rounded-lg mx-auto w-50" controls>
                        <source :src="formMediaComment.url" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
            <div v-if="comments" id="Comment">
                <div class="my-1" v-for="(comment, index) in comments " :key="index">
                    <div v-if="comment.post_id === post.id" class="box-comment-cus">
                        <div>
                            <div class="d-flex gap-2 align-items-center w-100 mb-1">
                                <a href="/" class="mr-2">
                                    <img class="rounded-full ml-1 img-cus" :src="comment.user.avatar" alt="">
                                </a>
                                <h5>{{ comment.user.user_name }}</h5>
                                <div class="d-flex align-items-center bg-EFF2F5 text-xs p-2 rounded-lg w-100">
                                    {{ comment.content }}
                                </div>
                                <a class="rounded-full p-1.5 ml-2 custom-cursor-pointer">
                                    <Delete fillColor="#64676B" size="20" />
                                </a>
                            </div>
                            <div class="w-100 position-relative" style="margin-left: 50px;">
                                <img width="150" v-if="comment.type != null && comment.type.includes('image')"
                                    :src="comment.path" alt="Image">
                                <video width="150" v-else-if="comment.type != null && comment.type.includes('video')"
                                    :src="comment.path" controls></video>
                                <div id="bottom-cus">
                                    <p class="custom-cursor-pointer">{{ comment.created_at_formatted }}</p>
                                    <p class="custom-cursor-pointer">like</p>
                                    <p @click="clickRepComment(comment.id)" class="custom-cursor-pointer">Phản hồi</p>
                                </div>
                            </div>
                            <div style="background: white;" id="repComment">
                                <form style="background: white;" @submit.prevent="CreateComment"
                                    class="d-flex align-items-center justify-content-between w-100">
                                    <a href="/" class="mr-2">
                                        <img class="rounded-full ml-1 img-cus" :src="authUser.avatar" alt="">
                                    </a>
                                    <div class="d-flex align-items-center rounded-full w-100">
                                        <input v-model="computedFormMediarepComment[comment.id].repcontent"
                                            value="computedFormMediarepComment[comment.id].repcontent" type="text"
                                            placeholder="Bình luận ..."
                                            class="w-100 border-0 mx-1 border-none p-0 text-sm  placeholder-[#64676B] ring-0 focus:ring-0">

                                        <label class="hover-200 rounded-full p-2 custom-cursor-pointer" for="image">
                                            <Image :size="27" fillColor="#43BE62" />
                                        </label>
                                        <input ref="fieldMediaRepCM" type="file" id="image" accept="image/*,video/*"
                                            @input="getUploadedImage($event)">
                                        <button type="submit"
                                            class="d-flex border-0 align-items-center text-sm px-3 rounded-full bg-blue-500 hover:bg-blue-600 text-white font-bold">
                                            <Check />Gửi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
import { mapState, mapActions } from 'vuex';
import { toRefs, reactive } from 'vue';
import { useGeneralStore } from '../../../store/general';
import { storeToRefs } from 'pinia';

export default {
    props: ['post', 'user', 'media'],
    data() {
        const useGeneral = useGeneralStore()
        const { isFileDisplay } = storeToRefs(useGeneral)
        return {
            isFileDisplay,
            comments: [],
            formMediaComment: {
                content: null
            },
            formMediarepComment: reactive({
                repcontent: null
            })
        }
    },

    computed: {
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
        computedFormMediarepComment() {
            const computedFormMediarepComment = {};
            this.comments.forEach(comment => {
                computedFormMediarepComment[comment.id] = {
                    repcontent: '',
                };
            });
            return computedFormMediarepComment;
        },
    },
    mounted() {
        // console.log(this.comments[0].user_id)
        this.getAllComment()
        console.log(this.computedFormMediarepComment)
    },
    methods: {

        getUploadedImage(e) {
            const file = e.target.files[0];
            let mediaType;
            if (file.type.startsWith('image/')) {
                mediaType = 'image';
            } else if (file.type.startsWith('video/')) {
                mediaType = 'video';
            }
            const url = URL.createObjectURL(file);
            // this.formMediaComment.push({ type: mediaType, file, url });
            this.formMediaComment.type = mediaType;
            this.formMediaComment.url = url;
            console.log(this.formMediaComment)
        },
        clearImage() {
            this.formMediaComment = {};
            this.$refs.fieldMedia.value = null
        },

        CreateComment() {
            const formData = new FormData();
            formData.append('content', this.formMediaComment.content);
            formData.append('file', this.$refs.fieldMedia.files[0]);
            // console.log(this.formMediaComment)

            axios.post(`api/user/create_comment/${this.post.id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            })
                .then(response => {

                    // console.log('Comment created successfully:', response.data);
                    this.$swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Comment thành công",
                        showConfirmButton: false,
                        timer: this.$config.notificationTimer ?? 3000,
                    });
                    this.formMediaComment = {};
                })
                .catch(error => {
                    // console.error('Error creating comment:', error);
                    this.$swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: error,
                        showConfirmButton: false,
                        timer: this.$config.notificationTimer ?? 3000,
                    });
                });

        },
        getAllComment() {
            axios.get('api/user/getAllComments')
                .then(response => {
                    this.comments = response.data.data;
                })
                .catch(error => {
                    console.error('Error fetching comments:', error);
                });
        },
        clickRepComment(commentIndex) {
            console.log(this.comments[2].user.user_name)
            this.computedFormMediarepComment.repcontent = this.comments[commentIndex].user.user_name
        }
    }
}
</script>