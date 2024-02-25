<script setup>
import Image from 'vue-material-design-icons/Image.vue'
import AccountMultiple from 'vue-material-design-icons/AccountMultiple.vue';
import ThumbUp from 'vue-material-design-icons/ThumbUp.vue';
import Check from 'vue-material-design-icons/Check.vue';
import Delete from 'vue-material-design-icons/Delete.vue';
import Close from 'vue-material-design-icons/Close.vue'

</script>
<template>
    <div id="post" class="pb-2">
        <div class="d-flex align-items-center px-0">
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
            <div id="Edit-posts" class="">
                <span @click="showEditPost = !showEditPost" class="ellipsis"><i class="fa-solid fa-ellipsis"></i></span>
                <div v-show="isEditPostOverlay === false" v-if="showEditPost && post.user_id === authUser.id"
                    class="edit-post">
                    <ul>
                        <li>Ghim bài viết</li>
                        <li>Lưu bài viết</li>
                        <li @click="showBoxPostEdit(post.id)">Chỉnh sửa bài viết</li>
                        <li>Chuyển vào thùng rác</li>
                    </ul>
                </div>
                <div v-if="showEditPost && post.user_id !== authUser.id" class="edit-post friend">
                    <ul>
                        <li>Ẩn bài viết</li>
                        <li>Lưu bài viết</li>
                        <li>Chặn {{ post.user.user_name }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="px-1 pb-2 text-cus-pos">
            {{ post.content }}
        </div>
        <div class="cus-post-media">
            <div class="list_item_media" v-for="medias in media" :key="media.id">
                <div v-if="medias.type === 'image'">
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
        <div id="Likes" class="">
            <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                <ThumbUp :size="16" fillColor="#1D72E2" />
                <div class="text-sm text-gray-600 font-semibold">5 bình luận</div>
            </div>
        </div>
        <div id="comments" class="">
            <div id="CreateComment" class="">
                <form @submit.prevent="CreateComment(post.id)"
                    class="d-flex align-items-center pt-2 justify-content-between w-100">
                    <a href="/" class="mx-2">
                        <img class="rounded-full ml-1 img-cus" :src="authUser.avatar" alt="">
                    </a>
                    <div class="d-flex align-items-center bg-EFF2F5 rounded-full w-100  px-2">
                        <textarea v-model="formComment.content" type="text" placeholder="Viết bình luận ..."
                            class="custom-input w-100 focus-0 border-0 mx-1 border-none p-0 text-sm bg-EFF2F5 placeholder-[#64676B] ring-0 focus:ring-0">
                        </textarea>
                        <label class="hover-200 rounded-full p-2 custom-cursor-pointer" for="image">
                            <Image :size="27" fillColor="#43BE62" />
                        </label>
                        <input ref="fieldMedia" type="file" class="" id="image" accept="image/*,video/*"
                            @input="getUploadedCommentImage($event)">
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
                <div v-if="formMediaComment.type === 'video'">
                    <video class="rounded-lg mx-auto w-50" controls>
                        <source :src="formMediaComment.url" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
            <div v-if="comments.length > 0" id="Comment" class="comment_array">
                <div class="my-1 comment_list" v-for="(comment, index) in comments" :key="comment.id">
                    <Comment :comment="comment" />
                </div>
            </div>
        </div>
    </div>
    <div>
        <EditPostOverlay :postEdit="post" v-if="isEditPostOverlay"
            @close-modalEditPost="closeEditModalEditPost" />
    </div>
</template>
<script>
import { mapState, mapActions, mapGetters } from 'vuex';
import { toRefs, reactive, ref } from 'vue';

import { useGeneralStore } from '../../../store/general';
import { storeToRefs } from 'pinia';
import Comment from '../Components/Comment.vue'
import EditPostOverlay from '../Components/EditPostOverlay.vue'

export default {
    components: {
        Comment,
        EditPostOverlay,

    },
    props: {
        comments: {
            type: Object,
            required: true,
        },
        post: {
            type: Object,
            required: true,
        },
        user: {
            type: Object,
            required: true,
        },
        media: {
            type: Object,
            required: true,
        },
        // repcomments: {
        //     type: Object,
        //     required: true,
        // },
    },

    data() {
        const useGeneral = useGeneralStore()
        const { isFileDisplay } = storeToRefs(useGeneral)
        return {
            showEditPost: false,
            isFileDisplay,
            isEditPostOverlay: false,
            formComment: reactive({
                content: ''
            }),

            formMediaComment: reactive({

            }),
        }
    },

    computed: {
        // ...mapState('post', ['comments']),
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
    },
    // created() {
    //     const postId = this.post.id;
    //     this.fetchComments(postId);
    // },
    mounted() {

    },
    methods: {
        ...mapActions('post', ['fetchPosts']),
        // ...mapActions('post', ['fetchComments']),
        ...mapActions('post', ['addNewComment']),
        showBoxPostEdit(postId) {
            if (postId === this.post.id) {
                this.isEditPostOverlay = true
                console.log(this.isEditPostOverlay)
            }
        },
        closeEditModalEditPost() {
            this.isEditPostOverlay = false;
        },
        getUploadedCommentImage(e) {
            const file = e.target.files[0];
            let mediaType;
            if (file.type.startsWith('image/')) {
                mediaType = 'image';
            } else if (file.type.startsWith('video/')) {
                mediaType = 'video';
            }
            const urlComment = URL.createObjectURL(file);
            // this.formMediaComment.push({ type: mediaType, file, url });
            this.formMediaComment.type = mediaType;
            this.formMediaComment.url = urlComment;
        },

        clearImage() {
            this.formMediaComment = {};
            this.$refs.fieldMedia.value = null
        },
        CreateComment(postId) {
            const fieldMediaCMRef = this.$refs['fieldMedia']
            const formData = new FormData();
            formData.append('content', this.formComment.content);
            formData.append('file', fieldMediaCMRef.files[0]);
            formData.append('postId', postId);
            console.log(formData)
            axios.post('api/user/create_comment', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            })
                .then(response => {
                    if (response.status === 200 && response.data.data.success === true) {
                        console.log(this.post)
                        this.post.comments.unshift(response.data.data.comment)
                        this.$swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Comment thành công !",
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                        this.formMediaComment = {};
                        this.formComment.content = null
                        this.$refs['fieldMedia'].value = null

                    } else {
                        this.$swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "Comment không thành công !",
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                        this.formMediaComment = {};
                        this.formComment.content = null
                        this.$refs['fieldMedia'].value = null
                    }
                })
                .catch(error => {
                    this.$swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: error,
                        showConfirmButton: false,
                        timer: this.$config.notificationTimer ?? 3000,
                    });
                });
        },
    },

}
</script>