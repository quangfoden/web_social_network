<script setup>
import Image from 'vue-material-design-icons/Image.vue'
import AccountMultiple from 'vue-material-design-icons/AccountMultiple.vue';
import ThumbUp from 'vue-material-design-icons/ThumbUp.vue';
import Check from 'vue-material-design-icons/Check.vue';
import Delete from 'vue-material-design-icons/Delete.vue';
import Close from 'vue-material-design-icons/Close.vue'
import Pin from 'vue-material-design-icons/Pin.vue'

</script>
<template>
    <div style="position: relative;" id="post" class="pb-2" :class="{ 'px-5': pinned }">
        <div style="position: absolute; top: 50%;left: 50%;" class="spinner-border text-primary" v-if="isLoading1"
            ole="status">
            <span class="sr-only">Loading...</span>
        </div>
        <p class="pt-2 m-0" v-if="pinned === 1 || pinned === true"> Bài viết đã ghim</p>
        <hr>
        <div class="d-flex align-items-center px-0">
            <router-link :to="{ name: 'Profile User', params: { id: user.user_id } }" class="mr-2">
                <img class="img-cus custom-cursor-pointer" :src="user.avatar" loading="lazy" alt="">
            </router-link>
            <div class="d-flex align-items-center justify-content-between p-2 rounded-full w-100">
                <div>
                    <router-link :to="{ name: 'Profile User', params: { id: user.user_id } }"
                        class="text-pr custom-cursor-pointer">{{ user.user_name }}</router-link>
                    <Pin v-if="pinned === 1 || pinned === true"
                        class="bg-white p-1 m-2 right-2 z-1000 rounded-full border custom-cursor-pointer" :size="22"
                        fillColor="#5E6771" />
                    <div class="d-flex align-items-center text-xs text-gray-600">
                        {{ post.created_at_formatted }}
                        <i v-if="post.privacy === 'public'" class="mx-2 fas fa-globe"></i>
                        <i v-if="post.privacy === 'friends'" class="mx-2 fas fa-user-friends"></i>
                        <i v-if="post.privacy === 'only_me'" class="mx-2 fas fa-lock"></i>
                    </div>
                </div>
            </div>
            <div id="Edit-posts" class="">
                <span @click="showMorePost = !showMorePost" class="ellipsis"><i class="fa-solid fa-ellipsis"></i></span>
                <div v-show="isEditPostOverlay === false" v-if="showMorePost && post.user_id === authUser.id"
                    class="edit-post">
                    <ul>
                        <li v-if="pinned === 0 || pinned === false" @click="togglePin(post.id)">Ghim bài viết</li>
                        <li v-if="pinned === 1 || pinned === true" @click="togglePin(post.id)">Bỏ ghim</li>
                        <li>Lưu bài viết</li>
                        <li @click="showBoxPostEdit(post.id)">Chỉnh sửa bài viết</li>
                        <li v-if="status" @click="trashPost(post.id)">Chuyển vào thùng rác</li>
                        <li v-if="!status" @click="trashPost(post.id)">Khôi phục</li>
                    </ul>
                </div>
                <div v-if="showMorePost && post.user_id !== authUser.id" class="edit-post friend">
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
            <div class="list_item_media" v-for=" medias in media " :key="media.id">
                <div v-if="medias.type.includes('image')">
                    <img @click="convertImageUrl(medias)" :src="medias.url" loading="lazy" alt="Image"
                        class="mx-auto custom-cursor-pointer w-100">
                </div>
                <div class="" v-else-if="medias.type.includes('video')">
                    <video @click="isFileDisplay = medias.url" :src="medias.url"
                        class="mx-auto custom-cursor-pointer w-100" autoplay controls muted>
                    </video>
                </div>
                <div v-else>
                    <a :href="medias.url" target="_blank">
                        {{ medias.url }}</a>
                    <div class="iframePost">
                        <iframe :src="medias.url" width="100%" height="200px"></iframe>
                    </div>
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
                        <img class="rounded-full ml-1 img-cus" :src="authUser.avatar" loading="lazy" alt="">
                    </a>
                    <div class="d-flex align-items-center bg-EFF2F5 rounded-full w-100  px-2">
                        <textarea v-model="formComment.content" type="text"
                            :placeholder="`Viết bình luận với vai trò ${authUser.user_name} ...`"
                            class="custom-input w-100 focus-0 border-0 mx-1 border-none p-0 pt-2 text-sm bg-EFF2F5 placeholder-[#64676B] ring-0 focus:ring-0">
                        </textarea>
                        <label class="hover-200 rounded-full p-2 custom-cursor-pointer" :for="'fileComment' + post.id">
                            <Image :size="27" fillColor="#43BE62" />
                        </label>
                        <input :ref="'fieldMedia' + post.id" type="file" class="hidden" :id="'fileComment' + post.id"
                            accept="image/*,video/*" @input="getUploadedCommentImage($event)">
                        <button type="submit"
                            class="d-flex border-0 align-items-center text-sm px-3 rounded-full bg-blue-500 hover:bg-blue-600 text-white font-bold">
                            <Check />Gửi
                        </button>
                    </div>
                </form>
            </div>
            <div v-if="formMediaComment.url" class="p-2 position-relative cus-img-dis">
                <Close @click="clearImage(post.id)"
                    class="position-absolute bg-white p-1 m-2 right-2 z-1000 rounded-full border custom-cursor-pointer"
                    :size="22" fillColor="#5E6771" />
                <div v-if="formMediaComment.type === 'image'"><img class="rounded-lg mx-auto w-50"
                        :src="formMediaComment.url" loading="lazy" alt=""></div>
                <div v-else-if="formMediaComment.type === 'video'">
                    <video class="rounded-lg mx-auto w-50" controls>
                        <source :src="formMediaComment.url" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div v-else>
                    <a href="formMediaComment.url">{{ formMediaComment.url }}</a>
                </div>
            </div>
            <div v-if="comments.length > 0" id="Comment" class="comment_array">
                <div class="my-1 comment_list" v-if="!showAllComments">
                    <Comment :comment="comments[0]" :repcomment_count="comments[0].repcomment_count"
                        @comment-updated="handleCommentUpdated"
                        @repcomment-created="handleRepCommentCreated(comments[0].id)"
                        @comment-deleted="handleCommentDeleted" />
                    <button class="px-2" @click="toggleComments">Xem tất cả {{ comment_count }} bình luận</button>
                </div>
                <div v-if="showAllComments">
                    <div class="my-1 comment_list" v-for="(comment, index) in comments" :key="comment.id">
                        <Comment :comment="comment" :repcomment_count="comment.repcomment_count"
                            @comment-updated="handleCommentUpdated"
                            @repcomment-created="handleRepCommentCreated(comment.id)"
                            @comment-deleted="handleCommentDeleted" />
                    </div>
                    <button class="px-2" @click="toggleComments">Ẩn bớt</button>
                </div>
            </div>
        </div>
    </div>
    <div>
        <EditPostOverlay :postEdit="post" :medias="post.media" v-if="isEditPostOverlay"
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
        comment_count: {
            type: Number,
            required: true,
        },
        post: {
            type: Object,
            required: true,
        },
        pinned: {
            type: Object,
            required: true
        },
        status: {
            type: Object,
            required: true
        },
        user: {
            type: Object,
            required: true,
        },
        media: {
            type: Object,
            required: true,
        },
    },

    data() {
        const useGeneral = useGeneralStore()
        const { isFileDisplay } = storeToRefs(useGeneral)
        return {
            showMorePost: false,
            isFileDisplay,
            isLoading1: false,
            isEditPostOverlay: false,
            formComment: ref({
                content: ''
            }),

            formMediaComment: ref({

            }),
            showAllComments: false,
        }
    },

    computed: {
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
    },
    mounted() {
    },
    methods: {
        ...mapActions('post', ['fetchPosts']),
        ...mapActions('post', ['addNewComment']),
        showBoxPostEdit(postId) {
            this.showMorePost = false
            if (postId === this.post.id) {
                this.isEditPostOverlay = true
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
            this.formMediaComment.type = mediaType;
            this.formMediaComment.url = urlComment;
        },

        clearImage(postId) {
            this.formMediaComment = {};
            this.$refs['fieldMedia' + postId].value = null
        },
        CreateComment(postId) {
            const fieldMediaCMRef = this.$refs['fieldMedia' + postId]
            const formData = new FormData();
            formData.append('content', this.formComment.content);
            formData.append('file', fieldMediaCMRef.files[0]);
            formData.append('postId', postId);
            this.$store.dispatch('post/createComment', formData)
                .then(response => {
                    if (response.status === 200 && response.data.data.success === true) {
                        this.comments.unshift(response.data.data.comment)
                        this.$emit('comment-created');
                        this.$swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Comment thành công !",
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                        this.formComment.content = ""
                        this.formMediaComment = {};
                        if (fieldMediaCMRef) {
                            fieldMediaCMRef.value = ''
                        }

                    } else {
                        this.$swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "Comment không thành công !",
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                        this.formComment.content = ""
                        this.formMediaComment = {};
                        if (fieldMediaCMRef) {
                            fieldMediaCMRef.value = ''
                        }
                    }
                })
                .catch(error => {
                    this.$swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "comment không thành công",
                        text: `Lỗi: ${error.response.data.message}`,
                        showConfirmButton: false,
                        timer: this.$config.notificationTimer ?? 3000,
                    });
                });
        },
        handleCommentUpdated(updatedComment) {
            const index = this.comments.findIndex(c => c.id === updatedComment.id);
            if (index !== -1) {
                this.comments[index].content = updatedComment.content;
                this.comments[index].url = updatedComment.url;
                this.comments[index].path = updatedComment.path;
                this.comments[index].type = updatedComment.type;
                this.comments[index].created_at = updatedComment.created_at;
                this.comments[index].updated_at = updatedComment.updated_at;
            }
        },
        handleRepCommentCreated(commentId) {
            const comment = this.comments.find(c => c.id === commentId);
            if (comment) {
                comment.repcomment_count += 1;
            }
        },
        handleCommentDeleted(deletedCommentId) {
            // this.comments = this.comments.filter(comment => comment.id !== deletedCommentId);
            // const comment = this.comments.find(c => c.id === deletedCommentId);
            // console.log(comment);
            const index = this.comments.findIndex(comment => comment.id === deletedCommentId);
            if (index !== -1) {
                this.comments.splice(index, 1);
            }
        },
        toggleComments() {
            this.showAllComments = !this.showAllComments;  // Đảo trạng thái hiển thị khi click
        },
        trashPost(postId) {
            this.$store.dispatch('post/trashPost', postId)
                .then(message => {

                })
                .catch(error => {
                    console.error("Error moving post to trash:", error);
                });
        },
        togglePin(postId) {
            this.$store.dispatch('post/togglePin', postId);
            this.showMorePost = false
        },
        isFileDisplayer(media) {
            this.isFileDisplay = media.url
            this.imageData = media.url
            this.showMorePost = false
        },
        convertImageUrl(media) {
            let imageUrl = media.url;
            this.isLoading1 = true
            this.showMorePost = false
            fetch(imageUrl)
                .then(response => response.blob())
                .then(blob => {
                    const formData = new FormData();
                    formData.append('image', blob, 'image.jpg');
                    fetch('http://127.0.0.1:5000/checkimage', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => {
                            if (response.ok) {
                                return response.blob();
                            } else {
                                throw new Error('Failed to send image to server');
                            }
                        })
                        .then(blob => {
                            this.isLoading1 = false
                            const imageUrl = URL.createObjectURL(blob);
                            this.isFileDisplay = imageUrl;
                        })
                        .catch(error => {
                            this.isLoading1 = false
                            console.error('Error sending image to server:', error);
                            this.$swal.fire({
                                position: "top-end",
                                icon: "error",
                                text: 'Có lỗi xảy ra khi gửi yêu càu',
                                showConfirmButton: false,
                                timer: this.$config.notificationTimer ?? 3000,
                                with: '200px'
                            });
                        });
                })
                .catch(error => {
                    console.error('Error loading image:', error);
                    this.isLoading1 = false
                    this.$swal.fire({
                        position: "top-end",
                        icon: "error",
                        text: 'Có lỗi xảy ra khi gửi yêu càu',
                        showConfirmButton: false,
                        timer: this.$config.notificationTimer ?? 3000,
                        with: '200px'
                    });
                });
        }


    },
}
</script>