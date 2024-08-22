<script setup>
import Image from 'vue-material-design-icons/Image.vue'
import AccountMultiple from 'vue-material-design-icons/AccountMultiple.vue';
import ThumbUp from 'vue-material-design-icons/ThumbUp.vue';
import Check from 'vue-material-design-icons/Check.vue';
import Send from 'vue-material-design-icons/Send.vue';
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
        <p class="p-3 pb-2 border-bottom m-0 secondary-text " v-if="pinned === 1 || pinned === true"> Bài viết đã ghim
        </p>
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
                        <li v-if="status" @click="confirmTashPost(post.id)">Chuyển vào thùng rác</li>
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
            {{ post.content ? post.content : '' }}
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
        <div class="count-comment-like-share">
            <div class="count-like fs-5 mb-2">
                <span class="like-icon type-like" v-if="hasLike">
                    <i class="fas fa-thumbs-up blue-color"></i>
                    <div class="list-user-like-type-like">
                        <ul style="list-style: none;" class="m-0 p-2">
                            <li v-for="like in likes" :key="like.id" class="secondary-text type-like">
                                <router-link v-if="like.type === 'Like'"
                                    :to="{ name: 'Profile User', params: { id: like.user.user_id } }">
                                    {{ like.user.id !== authUser.id ? like.user.user_name : "Bạn" }}
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </span>
                <span class="like-icon type-heart" v-if="hasHeart">❤
                    <div class="list-user-like-type-heart">
                        <ul style="list-style: none;" class="m-0 p-2">
                            <li v-for="like in likes" :key="like.id" class="secondary-text type-like">
                                <router-link v-if="like.type === 'Heart'"
                                    :to="{ name: 'Profile User', params: { id: like.user.user_id } }">
                                    {{ like.user.id !== authUser.id ? like.user.user_name : "Bạn" }}
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </span>
                <span class="like-icon type-laugh" v-if="hasLaugh">😂
                    <div class="list-user-like-type-laugh">
                        <ul style="list-style: none;" class="m-0 p-2">
                            <li v-for="like in likes" :key="like.id" class="secondary-text type-like">
                                <router-link v-if="like.type === 'Laugh'"
                                    :to="{ name: 'Profile User', params: { id: like.user.user_id } }">
                                    {{ like.user.id !== authUser.id ? like.user.user_name : "Bạn" }}
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </span>
                <span class="like-icon type-sad" v-if="hasSad">😥
                    <div class="list-user-like-type-sad">
                        <ul style="list-style: none;" class="m-0 p-2">
                            <li v-for="like in likes" :key="like.id" class="secondary-text type-like">
                                <router-link v-if="like.type === 'Sad'"
                                    :to="{ name: 'Profile User', params: { id: like.user.user_id } }">
                                    {{ like.user.id !== authUser.id ? like.user.user_name : "Bạn" }}
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </span>
                <span class="like-icon type-infuriating" v-if="hasInfuriating">😡
                    <div class="list-user-like-type-infuriating">
                        <ul style="list-style: none;" class="m-0 p-2">
                            <li v-for="like in likes" :key="like.id" class="secondary-text type-like">
                                <router-link v-if="like.type === 'Infuriating'"
                                    :to="{ name: 'Profile User', params: { id: like.user.user_id } }">
                                    {{ like.user.id !== authUser.id ? like.user.user_name : "Bạn" }}
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </span>
                <span v-if="like_count != 0" class="count secondary-text">{{ like_count }}
                    <div class="list-user-like">
                        <ul style="list-style: none;" class="m-0 p-2">
                            <li v-for="like in likes" :key="like.id" class="secondary-text">
                                <router-link :to="{ name: 'Profile User', params: { id: like.user.user_id } }">
                                    {{ like.user.id !== authUser.id ? like.user.user_name : "Bạn" }}
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </span>
            </div>
            <div class="count-comment"></div>
            <div class="count-share"></div>
        </div>
        <div class="border-top-cus like_share_comment-tx secondary-text">
            <div class="d-flex align-items-center justify-content-around border-bottom-cus p-1">
                <div class="like-tx rounded d-flex gap-1 align-item-center custom-cursor-pointer">
                    <div v-if="!hasLiked" @click="createLike(typeLike, post.id)" :class="{ 'p-2': !hasLiked }"
                        class="rounded d-flex gap-1 align-item-center custom-cursor-pointer">
                        <span><i class="fas fa-thumbs-up secondary-text"></i></span>
                        <span class="secondary-text">Thích</span>
                    </div>
                    <div v-if="hasLiked" v-for="like in likes" :key="like.id">
                        <div v-if="like.user_id === authUser.id">
                            <span class="p-2 hover-span" style="color: rgb(8, 102, 255);" @click="unLike(post.id)"
                                v-if="like.type === 'Like'">
                                <i class="fas fa-thumbs-up"></i>
                                Thích
                            </span>
                            <span class="p-2 hover-span" style="color: rgb(243, 62, 88);" @click="unLike(post.id)"
                                v-if="like.type === 'Heart'">❤ Yêu thích</span>
                            <span class="p-2 hover-span" style="color: rgb(247, 177, 37);" @click="unLike(post.id)"
                                v-if="like.type === 'Laugh'">😂 Haha</span>
                            <span class="p-2 hover-span" style="color: rgb(247, 177, 37);" @click="unLike(post.id)"
                                v-if="like.type === 'Sad'">😥 Buồn</span>
                            <span class="p-2 hover-span" style="color: rgb(247, 177, 37);" @click="unLike(post.id)"
                                v-if="like.type === 'Infuriating'">😡 Phẫn nộ</span>
                        </div>
                    </div>
                    <div class="icon-like">
                        <span @click="createLike(typeLike, post.id)"><i class="fas fa-thumbs-up blue-color"></i></span>
                        <span @click="createLike(typeHeart, post.id)">❤</span>
                        <span @click="createLike(typeLaugh, post.id)">😂</span>
                        <span @click="createLike(typeSad, post.id)">😥</span>
                        <span @click="createLike(typeInfuriating, post.id)">😡</span>
                    </div>
                </div>
                <div @click="showModalpost(post.id)"
                    class="comment-tx rounded d-flex gap-1 align-item-center custom-cursor-pointer p-2">
                    <span><i class="fas fa-comment secondary-text"></i></span>
                    <span class="secondary-text">Bình luận</span>
                </div>
                <div class="share-tx rounded d-flex gap-1 align-item-center custom-cursor-pointer p-2">
                    <span><i class="fas fa-share secondary-text"></i></span>
                    <span class="secondary-text">Chia sẽ</span>
                </div>
            </div>
        </div>
        <div id="comments" class="">
            <div id="CreateComment" class="">
                <form @submit.prevent="CreateComment(post.id)"
                    class="d-flex align-items-center pt-2 justify-content-between w-100">
                    <a href="/" class="mx-2">
                        <img class="rounded-full ml-1 img-cus" :src="authUser.avatar" loading="lazy" alt="">
                    </a>
                    <div class="position-relative d-flex align-items-center bg-input rounded w-100  px-2">
                        <textarea @input="onInput(post.id, $event)" v-model="formComment.content" type="text"
                            :placeholder="`Viết bình luận với vai trò ${authUser.user_name} ...`"
                            :ref="'textAreaComment' + post.id"
                            class="primary-text custom-input w-100 focus-0 border-0 mx-1 border-none p-0 pt-2 text-sm bg-input placeholder-[#64676B] ring-0 focus:ring-0">
                        </textarea>
                        <ul v-show="showSuggestions && filteredFriends.length >= 1"
                            class="suggestions rounded  position-absolute">
                            <li v-for="friend in filteredFriends" :key="friend.id" class="rounded"
                                @click="selectFriend(friend.user, post.id)">
                                <div class="d-flex gap-2 align-items-center">
                                    <img class="rounded-full ml-1 img-cus" :src="friend.user.avatar" alt="">
                                    <p class="primary-text fw-bold mb-0">{{ friend.user.user_name }}</p>
                                </div>
                            </li>
                        </ul>
                        <label v-if="!isSendLoading" class="hover-200 rounded-full p-2 custom-cursor-pointer"
                            :for="'fileComment' + post.id">
                            <Image :size="27" fillColor="#43BE62" />
                        </label>
                        <input :ref="'fieldMedia' + post.id" type="file" class="hidden" :id="'fileComment' + post.id"
                            accept="image/*,video/*" @input="getUploadedCommentFile($event)">
                        <button v-if="!isSendLoading" type="submit" :disabled="isSubmitDisabled"
                            :class="{ 'no-click': isSubmitDisabled }"
                            class="d-flex border-0 bg-transparent align-items-center text-sm px-3 rounded-full hover:bg-blue-600 text-white font-bold">
                            <Send :size="27" fillColor="#4299e1" />
                        </button>
                        <button v-if="isSendLoading" class="btn btn-sm btn-secondary" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                            <span class="visually-hidden" role="status">Loading...</span>
                        </button>
                    </div>
                </form>
            </div>
            <div v-if="formMediaComment.url" style="margin-left:60px ;" class="p-2 position-relative cus-img-dis">
                <Close @click="clearFile(post.id)"
                    class="position-absolute bg-white p-1 m-2 right-2 z-1000 rounded-full border custom-cursor-pointer"
                    :size="22" fillColor="#5E6771" />
                <div v-if="formMediaComment.type === 'image'"><img width="200" class="rounded-lg mx-auto"
                        :src="formMediaComment.url" loading="lazy" alt=""></div>
                <div v-else-if="formMediaComment.type === 'video'">
                    <video width="200" class="rounded-lg mx-auto" controls>
                        <source :src="formMediaComment.url" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div v-else>
                    <a href="formMediaComment.url">{{ formMediaComment.url }}</a>
                </div>
            </div>
            <div v-if="comments.length > 0 && !isPostOverLay" id="Comment" class="comment_array">
                <div class="my-1 comment_list" v-if="!isPostOverLay">
                    <Comment :comment="comments[0]" :repcomment_count="comments[0].repcomment_count"
                        @comment-updated="handleCommentUpdated"
                        @repcomment-created="handleRepCommentCreated(comments[0].id)"
                        @comment-deleted="handleCommentDeleted"
                        @repcomment-deleted="handleRepCommentDeleted(comments[0].id)" />
                    <button v-if="comments.length > 1" class="px-2 bg-transparent primary-text text-underline"
                        @click="showModalpost(post.id)">Xem tất cả {{ comment_count
                        }}
                        bình luận</button>
                </div>
                <div v-if="isPostOverLay">
                    <div class="my-1 comment_list" v-for="(comment, index) in comments" :key="comment.id">
                        <Comment :comment="comment" :repcomment_count="comment.repcomment_count"
                            @comment-updated="handleCommentUpdated"
                            @repcomment-created="handleRepCommentCreated(comment.id)"
                            @comment-deleted="handleCommentDeleted"
                            @repcomment-deleted="handleRepCommentDeleted(comment.id)" />
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div>
        <EditPostOverlay :postEdit="post" :medias="post.media" v-if="isEditPostOverlay"
            @close-modalEditPost="closeEditModalEditPost" />
        <ModalPostOverLay :isPost="post" :medias="post.media" :comments="post.comments" v-if="isPostOverLay"
            :likes="post.likes" :like_count="post.like_count" @close-modal-post="closeModalPost"
            @comment_overlay-created="handleCommentOverLayCreated"
            @repcomment-created="handleRepCommentCreated(comment.id)"
            @comment_overlay-deleted="handleCommentoVerLayDeleted" @updated_like="handleUpdatedLike(post.id)"
            @deleted_like="handleLikedeleted(post.id)" />
    </div>
</template>
<script>
import diacritics from 'diacritics';
import { mapState, mapActions, mapGetters } from 'vuex';
import { toRefs, reactive, ref } from 'vue';

import { useGeneralStore } from '../../../store/general';
import { storeToRefs } from 'pinia';
import Comment from '../Components/Comment.vue'
import EditPostOverlay from '../Components/EditPostOverlay.vue'
import ModalPostOverLay from './ModalPostOverLay.vue';

export default {
    components: {
        Comment,
        EditPostOverlay,
        ModalPostOverLay
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
            type: Array,
            required: true,
        },
        likes: {
            type: Array,
            required: true,
        },
        like_count: {
            type: Number,
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
            isPostOverLay: false,
            isEditPostOverlay: false,
            formComment: ref({
                content: ''
            }),
            formMediaComment: ref({
            }),
            showAllComments: false,
            showSuggestions: false,
            filteredFriends: [],
            friends: [],
            selectedFriend: null,
            isSendLoading: false,
            // likes: [],
            typeLiked: null,
            typeLike: "Like",
            typeHeart: "Heart",
            typeLaugh: "Laugh",
            typeSad: "Sad",
            typeInfuriating: "Infuriating",
        }
    },

    computed: {
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
        ...mapGetters('friends', ['getFriendsWithUsers']),
        friendsWithUsers() {
            return this.getFriendsWithUsers;
        },
        ...mapState({
            accounts: state => state.users.accounts
        }),
        isSubmitDisabled() {
            return this.formComment.content === '' && Object.keys(this.formMediaComment).length === 0;
        },
        hasLiked() {
            return this.likes && this.authUser
                ? this.likes.some(like => like.user_id === this.authUser.id)
                : false;
        },
        hasLike() {
            return this.likes.some(like => like.type === 'Like');
        },
        hasHeart() {
            return this.likes.some(like => like.type === 'Heart');
        },
        hasLaugh() {
            return this.likes.some(like => like.type === 'Laugh');
        },
        hasSad() {
            return this.likes.some(like => like.type === 'Sad');
        },
        hasInfuriating() {
            return this.likes.some(like => like.type === 'Infuriating');
        }
        
    },
    mounted() {

    },
    methods: {
        ...mapActions('post', ['fetchPosts']),
        ...mapActions('post', ['addNewComment']),
        showModalpost(postId) {
            if (postId === this.post.id) {
                this.isPostOverLay = true
            }
        },
        showBoxPostEdit(postId) {
            this.showMorePost = false
            if (postId === this.post.id) {
                this.isEditPostOverlay = true
            }
        },
        closeModalPost() {
            this.isPostOverLay = false;
        },
        closeEditModalEditPost() {
            this.isEditPostOverlay = false;
        },
        getUploadedCommentFile(e) {
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

        clearFile(postId) {
            this.formMediaComment = {};
            this.$refs['fieldMedia' + postId].value = null
        },
        CreateComment(postId) {
            this.isSendLoading = true
            const fieldMediaCMRef = this.$refs['fieldMedia' + postId]
            let content = this.formComment.content
            if (this.selectedFriend) {
                content = content.replace('@' + this.selectedFriend.user_name,
                    "<a href='/profile/"
                    + this.selectedFriend.user_id + "' class='custom-span'>" + this.selectedFriend.user_id + "</a>"
                )
            }
            const formData = new FormData();
            formData.append('content', content);
            if (fieldMediaCMRef.value) {
                formData.append('file', fieldMediaCMRef.files[0]);
            }
            formData.append('postId', postId);
            this.$store.dispatch('post/createComment', formData)
                .then(response => {
                    this.isSendLoading = false
                    if (response.status === 200 && response.data.data.success === true) {
                        this.selectedFriend = null
                        this.comments.unshift(response.data.data.comment)
                        this.$emit('comment-created')
                        this.$swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Comment thành công !",
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                        this.formComment.content = ""
                        this.formMediaComment = {};
                        if (fieldMediaCMRef.value) {
                            fieldMediaCMRef.value = ''
                        }
                        this.isPostOverLay = true
                    } else {
                        this.selectedFriend = null
                        this.isPostOverLay = true
                        this.$swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "Comment không thành công !",
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                        this.formComment.content = ""
                        this.formMediaComment = {};
                        if (fieldMediaCMRef.value) {
                            fieldMediaCMRef.value = ''
                        }
                    }
                })
                .catch(error => {
                    this.isSendLoading = false
                    this.isPostOverLay = true
                    this.selectedFriend = null
                    this.$swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "comment không thành công",
                        showConfirmButton: false,
                        timer: this.$config.notificationTimer ?? 3000,
                    });
                    this.formComment.content = ""
                    this.formMediaComment = {};
                    if (fieldMediaCMRef.value) {
                        fieldMediaCMRef.value = ''
                    }
                });
        },
        handleCommentOverLayCreated(postId) {
            this.$emit('comment_overlay-created', postId)
        },
        handleCommentoVerLayDeleted(postId) {
            this.$emit('comment_overlay-deleted', postId)
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
            const index = this.comments.findIndex(comment => comment.id === deletedCommentId);
            if (index !== -1) {
                this.comments.splice(index, 1);
                this.$emit('comment-deleted');
            }
        },
        handleRepCommentDeleted(deletedCommentId) {
            const comment = this.comments.find(c => c.id === deletedCommentId);
            if (comment) {
                comment.repcomment_count -= 1;
            }
        },
        toggleComments() {
            this.showAllComments = !this.showAllComments;  // Đảo trạng thái hiển thị khi click
        },
        confirmTashPost(postId) {
            this.showMorePost = false
            this.$swal.fire({
                title: 'Bạn chắc chắn muốn chuyển bài viết này vào thùng rác ?',
                text: 'Hành động này sẽ không thể hoàn tác!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xoá',
                cancelButtonText: 'Hủy',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.trashPost(postId);
                }
            });
        },
        trashPost(postId) {
            this.showMorePost = false
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
        },
        onInput(id, event) {
            const textAreaComment = this.$refs['textAreaComment' + id]
            textAreaComment.style.height = 'auto';
            textAreaComment.style.height = `${textAreaComment.scrollHeight}px`;
            const text = event.target.value;
            const position = event.target.selectionStart;
            const match = text.substring(0, position).match(/@(\S*)$/);
            if (match) {
                const query = diacritics.remove(match[1].toLowerCase());
                this.filteredFriends = this.friends.filter(
                    friend =>
                        diacritics.remove(friend.user.user_name.toLowerCase()).includes(query)
                );
                this.showSuggestions = true;
            } else {
                this.showSuggestions = false;
                console.log('lỗi nè');
            }
        },
        selectFriend(friend, id) {
            this.selectedFriend = friend;
            const textArea = this.$refs['textAreaComment' + id];
            const position = textArea.selectionStart;

            const text = textArea.value.substring(0, position);
            const mentionStart = text.lastIndexOf('@');
            const textBefore = textArea.value.substring(0, mentionStart);
            const textAfter = textArea.value.substring(position);

            this.formComment.content = `@${friend.user_name} `;
            this.showSuggestions = false;

            textArea.focus();
        },

        createLike(typeLiked, postId) {
            this.typeLiked = typeLiked;
            axios.post('/api/user/like', {
                post_id: postId,
                user_id: this.authUser.id,
                type: this.typeLiked ? this.typeLiked : 'unlike'
            })
                .then(response => {
                    const likeIndex = this.likes.findIndex(like => (like.post_id === postId && like.user_id === this.authUser.id));
                    if (likeIndex !== -1) {
                        this.likes.splice(likeIndex, 1);
                    }
                    else {
                        this.$emit('updated_like')
                    }
                    this.likes.push(response.data.like);
                })
                .catch(error => {
                    console.error(error);
                });
        },
        unLike(postId) {
            axios.delete('/api/user/unlike', { data: { post_id: postId, user_id: this.authUser.id } })
                .then(response => {
                    const likeIndex = this.likes.findIndex(like => (like.post_id === postId && like.user_id === this.authUser.id));
                    if (likeIndex !== -1) {
                        this.likes.splice(likeIndex, 1);
                        this.$emit('deleted_like')
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        },
        handleUpdatedLike(postId) {
            this.$emit('updated-like-overlay', postId)
        },
        handleLikedeleted(postId) {
            this.$emit('deleted-like-overlay', postId)
        },
    },
    created() {
        this.friends = this.friendsWithUsers
    }
}
</script>