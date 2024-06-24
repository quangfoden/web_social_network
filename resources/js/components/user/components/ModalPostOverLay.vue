<script setup>
import Close from 'vue-material-design-icons/Close.vue'
import Image from 'vue-material-design-icons/Image.vue'
import Send from 'vue-material-design-icons/Send.vue';

</script>
<template>
    <div id="ModalPostOverLay">
        <div class="wrapper-post-overlay">
            <div class="cus_postoverlay">
                <div class="border-bottom-cus d-flex align-items-center position-relative py-2 px-3">
                    <div class="w-100 text-center primary-text fw-bold fs-5">Bài viết của {{ isPost.user.user_name }}
                    </div>
                    <div class="close p-2 rounded-full bg-input custom-cursor-pointer" @click="closeModaPostOverlay">
                        <Close :size="20" fillColor="#5E6771" />
                    </div>
                </div>
                <div id="postOVerLay">
                    <div class="p-2">
                        <div class="d-flex justify-content-between py-2">
                            <div class="d-flex gap-2 align-items-center">
                                <a href="">
                                    <img class="custom" :src="isPost.user.avatar" alt="">
                                </a>
                                <div>
                                    <a href="" class="primary-text fw-bold custom-cursor-pointer">{{
                                        isPost.user.user_name }}</a>
                                    <div class="d-flex align-items-center text-xs text-gray-600">
                                        {{ isPost.created_at_formatted }}
                                        <i v-if="isPost.privacy === 'public'" class="mx-2 fas fa-globe"></i>
                                        <i v-if="isPost.privacy === 'friends'" class="mx-2 fas fa-user-friends"></i>
                                        <i v-if="isPost.privacy === 'only_me'" class="mx-2 fas fa-lock"></i>
                                    </div>
                                </div>
                            </div>
                            <!-- <div id="" class=""><span class="fs-4 custom-cursor-pointer ellipsis secondary-text"><i
                                        class="fa-solid fa-ellipsis"></i></span>
                            </div> -->
                        </div>
                        <div class="px-1 p-2  primary-text text-cus-pos">{{ isPost.content }}</div>
                    </div>
                    <div class="cus-post-media">
                        <div class="list_item_media" v-for=" media in medias " :key="media.id">
                            <div v-if="media.type.includes('image')">
                                <img @click="convertImageUrl(media)" :src="media.url" loading="lazy" alt="Image"
                                    class="mx-auto custom-cursor-pointer w-100">
                            </div>
                            <div class="" v-else-if="media.type.includes('video')">
                                <video @click="isFileDisplay = media.url" :src="media.url"
                                    class="mx-auto custom-cursor-pointer w-100" autoplay controls muted>
                                </video>
                            </div>
                            <div v-else>
                                <a :href="media.url" target="_blank">
                                    {{ media.url }}</a>
                                <div class="iframePost">
                                    <iframe :src="media.url" width="100%" height="200px"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="like_share_comment-tx secondary-text">
                        <div class="d-flex align-items-center justify-content-around border-bottom-cus p-1">
                            <div class="like-tx rounded d-flex gap-1 align-item-center custom-cursor-pointer p-2">
                                <span><i class="fas fa-thumbs-up secondary-text"></i></span><span
                                    class="secondary-text">Thích</span>
                            </div>
                            <div @click="FocusEvent(isPost.id)"
                                class="comment-tx rounded d-flex gap-1 align-item-center custom-cursor-pointer p-2">
                                <span><i class="fas fa-comment secondary-text"></i></span><span
                                    class="secondary-text">Bình
                                    luận</span>
                            </div>
                            <div class="share-tx rounded d-flex gap-1 align-item-center custom-cursor-pointer p-2">
                                <span><i class="fas fa-share secondary-text"></i></span><span
                                    class="secondary-text">Chia
                                    sẽ</span>
                            </div>
                        </div>
                    </div>
                    <div class="comment-post-ovelay p-3">

                        <div>
                            <div class="list-comments">
                                <div class="my-1 comment_list" v-for="(comment, index) in comments" :key="comment.id">
                                    <Comment :comment="comment" :repcomment_count="comment.repcomment_count"
                                        @comment-updated="handleCommentOverlayUpdated"
                                        @comment-deleted="handleCommentoVerLayDeleted"
                                        @repcomment-created="handleRepCommentCreated(comment.id)" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <form @submit.prevent="CreateOverlayComment(isPost.id)"
                        class="p-3 d-flex align-items-center gap-2 pt-2 justify-content-between w-100">
                        <a href="/" class="">
                            <img class="rounded-full ml-1 custom" src="/images/avatar.gif" loading="lazy" alt="">
                        </a>
                        <div class="position-relative d-flex align-items-center bg-input rounded w-100  px-2">
                            <textarea @input="onInput(isPost.id, $event)" v-model="formComment.content" type="text"
                                :placeholder="`Viết bình luận với vai trò ${authUser.user_name} ...`"
                                :ref="'textAreaComment' + isPost.id"
                                class="primary-text custom-input w-100 focus-0 border-0 mx-1 border-none p-0 pt-2 text-sm bg-input placeholder-[#64676B] ring-0 focus:ring-0">
                                </textarea>
                            <ul v-show="showSuggestions && filteredFriends.length >= 1"
                                class="suggestions rounded  position-absolute" style="top: -150px;">
                                <li v-for="friend in filteredFriends" :key="friend.id" class="rounded"
                                    @click="selectFriend(friend, isPost.id)">
                                    <div class="d-flex gap-2 align-items-center">
                                        <img class="rounded-full ml-1 custom" :src="friend.avatar" alt="">
                                        <p class="primary-text fw-bold mb-0">{{ friend.user_name }}</p>
                                    </div>
                                </li>
                            </ul>
                            <label v-if="!isSendLoading" class="hover-200 rounded-full p-2 custom-cursor-pointer"
                                :for="'fileCommentOverlay' + isPost.id">
                                <Image :size="27" fillColor="#43BE62" />
                            </label>
                            <input :ref="'fieldMedia' + isPost.id" type="file" class="hidden"
                                :id="'fileCommentOverlay' + isPost.id" accept="image/*,video/*"
                                @input="getUploadedCommentFile($event)">
                            <button v-if="!isSendLoading" :disabled="isSubmitDisabled"
                                :class="{ 'no-click': isSubmitDisabled }" type="submit"
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
                    <Close @click="clearFile(isPost.id)"
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
            </div>

        </div>
    </div>
</template>
<script>
import { mapState, mapActions, mapGetters } from 'vuex';
import diacritics from 'diacritics';
import { toRefs, reactive, ref } from 'vue';

import Comment from '../Components/Comment.vue'
export default {
    components: {
        Comment
    },
    props: {
        isPost: {
            type: Object,
            required: true,
        },
        medias: {
            type: Object,
            required: true,
        },
        comments: {
            type: Object,
            required: true,
        }
    },
    data() {
        return {
            formComment: {
                content: ''
            },
            formMediaComment: ref({
            }),
            showSuggestions: false,
            friends: [],
            selectedFriend: null,
            filteredFriends: [],
            isSendLoading: false
        }
    },
    computed: {
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
        ...mapState({
            accounts: state => state.users.accounts
        }),
        isSubmitDisabled() {
            return this.formComment.content === '' && Object.keys(this.formMediaComment).length === 0;
        }
    },
    mounted() {
        this.FocusEvent(this.isPost.id)
    },
    methods: {
        closeModaPostOverlay() {
            this.$emit('close-modal-post')
        },
        FocusEvent(id) {
            const textArea = this.$refs['textAreaComment' + id];
            textArea.focus()
        },
        handleRepCommentCreated(commentId) {
            const comment = this.comments.find(c => c.id === commentId);
            if (comment) {
                comment.repcomment_count += 1;
            }
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
        CreateOverlayComment(postId) {
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
                        this.$emit('comment_overlay-created', postId)
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
                        this.selectedFriend = null
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
                    this.isSendLoading = false
                    this.selectedFriend = null
                    this.$swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "comment không thành công",
                        showConfirmButton: false,
                        timer: this.$config.notificationTimer ?? 3000,
                    });
                    console.log(error.message);
                });
        },
        handleCommentOverlayUpdated(updatedComment) {
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
        handleCommentoVerLayDeleted(deletedCommentId) {
            const index = this.comments.findIndex(comment => comment.id === deletedCommentId);
            if (index !== -1) {
                this.comments.splice(index, 1);
                this.$emit('comment_overlay-deleted');
            }
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
                        diacritics.remove(friend.user_name.toLowerCase()).includes(query)
                );
                this.showSuggestions = true;
            } else {
                this.showSuggestions = false;
                console.log('lỗi nè');
            }
        },
        selectFriend(friend, id) {
            this.selectedFriend = friend;
            console.log(this.selectedFriend.user_name);
            console.log(this.selectedFriend);
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
    },
    created() {
        this.friends = this.accounts
    }
}
</script>
