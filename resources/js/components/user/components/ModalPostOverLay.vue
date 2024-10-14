<script setup>
import Close from 'vue-material-design-icons/Close.vue'
import Image from 'vue-material-design-icons/Image.vue'
import Send from 'vue-material-design-icons/Send.vue';

</script>
<template>
    <div @click="closeModaPostOverlay" id="ModalPostOverLay">
        <div class="central-meta item"@click.stop>
            <div class="user-post">
                <div class="friend-info">
                    <figure>
                        <img :src="isPost.user.avatar" alt="">
                    </figure>
                    <div class="friend-name">
                        <ins><a href="time-line.html" title="">{{ isPost.user.user_name }}</a></ins>
                        <span>
                            <i v-if="isPost.privacy === 'public'" class="mx-2 fas fa-globe"></i>
                            <i v-if="isPost.privacy === 'friends'" class="mx-2 fas fa-user-friends"></i>
                            <i v-if="isPost.privacy === 'only_me'" class="mx-2 fas fa-lock"></i>
                            {{ isPost.created_at_formatted }}
                        </span>
                    </div>
                    <div class="post-meta">
                        <p>
                            {{ isPost.content }}
                        </p>
                        <figure>
                            <div class="img-bunch">
                                <div class="row">
                                    <div class="col-12">
                                        <div
                                            :class="{ 'one-media': medias.length === 1, 'multiple-medias': medias.length > 1 }">
                                            <figure v-for=" media in medias.slice(0, 3)" :key="media.id">
                                                <a target="_blank" v-if="media.type.includes('image')" class="strip"
                                                    :href="media.url" title="" data-strip-group="mygroup"
                                                    data-strip-group-options="loop: false">
                                                    <img :src="media.url" alt="">
                                                </a>
                                                <a target="_blank" v-else-if="media.type.includes('video')"
                                                    class="strip" :href="media.url" title="" data-strip-group="mygroup"
                                                    data-strip-group-options="loop: false">
                                                    <video :src="media.url" class="" autoplay controls muted>
                                                    </video>
                                                </a>
                                            </figure>

                                            <figure style="overflow: hidden;" v-if="medias.length >= 5">
                                                <a target="_blank" v-if="medias[3].type.includes('image')" class="strip"
                                                    :href="medias[3].url" title="" data-strip-group="mygroup"
                                                    data-strip-group-options="loop: false">
                                                    <img :src="medias[3].url" alt="">
                                                </a>
                                                <a target="_blank" v-else-if="medias[3].type.includes('video')"
                                                    class="strip" :href="medias[3].url" title=""
                                                    data-strip-group="mygroup" data-strip-group-options="loop: false">
                                                    <video :src="medias[3].url" class="" autoplay controls muted>
                                                    </video>
                                                </a>
                                                <div class="more-photos">
                                                    <span>+{{ medias.length - 3 }}</span>
                                                </div>
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </figure>
                        <div class="we-video-info">
                            <ul>
                                <span class="likes-items">
                                    <li v-if="!hasLiked" @click="createLike(typeLike, isPost.id)"
                                        :class="{ 'p-2': !hasLiked }" class="custom-cursor-pointer ">
                                        <span><i class="fas fa-thumbs-up secondary-text"></i></span>
                                    </li>
                                    <li class="p-2 custom-cursor-pointer k" v-if="hasLiked">
                                        <div v-for="like in likes" :key="like.id">
                                            <div v-if="like.user_id === authUser.id">
                                                <span class="hover-span" style="color: #fa6342;"
                                                    @click="unLike(isPost.id)" v-if="like.type === 'Like'">
                                                    <i class="fas fa-thumbs-up"></i>

                                                </span>
                                                <span class="hover-span" style="color: rgb(243, 62, 88);"
                                                    @click="unLike(isPost.id)" v-if="like.type === 'Heart'"><i
                                                        class="em em-heart"></i></span>
                                                <span class="hover-span" style="color: rgb(247, 177, 37);"
                                                    @click="unLike(isPost.id)" v-if="like.type === 'Laugh'"><i
                                                        class="em em-laughing"></i></span>
                                                <span class="hover-span" style="color: rgb(247, 177, 37);"
                                                    @click="unLike(isPost.id)" v-if="like.type === 'Sad'"><i
                                                        class="em em-cry"></i></span>
                                                <span class="hover-span" style="color: rgb(247, 177, 37);"
                                                    @click="unLike(isPost.id)" v-if="like.type === 'Infuriating'"><i
                                                        class="em em-rage"></i></span>
                                            </div>
                                        </div>
                                    </li>
                                    <ins @mouseover="showListUserLike = true" @mouseleave="showListUserLike = false"
                                        style="position: absolute; top: 30px;left: 35px;cursor: pointer;"
                                        v-if="like_count != 0">{{ like_count }}</ins>
                                    <div @mouseover="showListUserLike = true" @mouseleave="showListUserLike = false"
                                        v-if="like_count != 0" :class="{ 'active': showListUserLike }"
                                        class="list-user-like">
                                        <ul style="list-style: none;" class="m-0 p-2">
                                            <li v-for="like in likes" :key="like.id" class="secondary-text">
                                                <router-link
                                                    :to="{ name: 'Profile User', params: { id: like.user.user_id } }">
                                                    {{ like.user.id !== authUser.id ?
                                                        like.user.user_name : "Bạn" }}
                                                </router-link>
                                            </li>
                                        </ul>
                                    </div>
                                    <div v-show="!showListUserLike" class="icon-like" style="position: absolute;">
                                        <span class="custom-cursor-pointer" style="color: rgb(250, 99, 66);"
                                            @click="createLike(typeLike, isPost.id)"><i
                                                class="fas fa-thumbs-up blue-color"></i></span>
                                        <span class="custom-cursor-pointer" @click="createLike(typeHeart, isPost.id)"><i
                                                class="em em-heart"></i></span>
                                        <span class="custom-cursor-pointer" @click="createLike(typeLaugh, isPost.id)">
                                            <i class="em em-laughing"></i>
                                        </span>
                                        <span class="custom-cursor-pointer" @click="createLike(typeSad, isPost.id)">
                                            <i class="em em-cry"></i>
                                        </span>
                                        <span class="custom-cursor-pointer"
                                            @click="createLike(typeInfuriating, isPost.id)">
                                            <i class="em em-rage"></i> </span>
                                    </div>
                                </span>
                                <li>
                                    <span class="comment" title="Comments">
                                        <i class="fa fa-commenting"></i>
                                        <ins v-if="comment_count && comment_count != 0">{{ comment_count }}</ins>
                                    </span>
                                </li>

                                <li>
                                    <span>
                                        <a class="share-pst" href="#" title="Share">
                                            <i class="fa fa-share-alt"></i>
                                        </a>
                                        <ins>20</ins>
                                    </span>
                                </li>
                            </ul>
                            <div class="users-thumb-list">
                                <a data-toggle="tooltip" title="Anderw" href="#">
                                    <img alt="" src="images/resources/userlist-1.jpg">
                                </a>
                                <a data-toggle="tooltip" title="frank" href="#">
                                    <img alt="" src="images/resources/userlist-2.jpg">
                                </a>
                                <a data-toggle="tooltip" title="Sara" href="#">
                                    <img alt="" src="images/resources/userlist-3.jpg">
                                </a>
                                <a data-toggle="tooltip" title="Amy" href="#">
                                    <img alt="" src="images/resources/userlist-4.jpg">
                                </a>
                                <a data-toggle="tooltip" title="Ema" href="#">
                                    <img alt="" src="images/resources/userlist-5.jpg">
                                </a>
                                <span><strong>You</strong>, <b>Sarah</b> and <a href="#" title="">24+ more</a>
                                    liked</span>
                            </div>
                        </div>
                    </div>
                    <div class="coment-area" style="display: block;">
                        <ul class="we-comet">
                            <div class="my-1 comment_list" v-for="(comment, index) in comments" :key="comment.id">
                                <Comment :comment="comment" :repcomment_count="comment.repcomment_count"
                                    @comment-updated="handleCommentOverlayUpdated"
                                    @comment-deleted="handleCommentoVerLayDeleted"
                                    @repcomment-created="handleRepCommentCreated(comment.id)" />
                            </div>
                            <li class="post-comment">
                                <div class="comet-avatar">
                                    <img :src="authUser.avatar" alt="">
                                </div>
                                <div class="post-comt-box">
                                    <div v-if="formMediaComment.url" class="mb-2 cus-img-dis">
                                        <span
                                            class="position-absolute bg-white p-1 m-2 right-2 z-1000 rounded-full border custom-cursor-pointer"
                                            @click="clearFile(isPost.id)"><i class="fas fa-close"></i></span>
                                        <div v-if="formMediaComment.type === 'image'"><img width="150"
                                                class="rounded-lg mx-auto" :src="formMediaComment.url" loading="lazy"
                                                alt=""></div>
                                        <div v-else-if="formMediaComment.type === 'video'">
                                            <video width="150" class="rounded-lg mx-auto" controls>
                                                <source :src="formMediaComment.url" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                        <div v-else>
                                            <a href="formMediaComment.url">{{ formMediaComment.url }}</a>
                                        </div>
                                    </div>
                                    <form style="position: relative;" @submit.prevent="CreateOverlayComment(isPost.id)"
                                        method="post">
                                        <textarea style="resize: none;"
                                            :placeholder="`Viết bình luận với vai trò ${authUser.user_name} ...`"
                                            :ref="'textAreaComment' + isPost.id" @input="onInput(isPost.id, $event)"
                                            v-model="formComment.content"></textarea>
                                        <ul v-show="showSuggestions && filteredFriends.length >= 1"
                                            class="suggestions rounded  position-absolute">
                                            <li v-for="friend in filteredFriends" :key="friend.id" class="rounded"
                                                @click="selectFriend(friend, isPost.id)">
                                                <div class="d-flex gap-2 align-items-center">
                                                    <img width="40" class="rounded-full ml-1 img-cus" :src="friend.avatar"
                                                        alt="">
                                                    <p class="primary-text fw-bold mb-0">{{ friend.user_name }}</p>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="add-smiles">
                                            <div class="uploadimage">
                                                <i class="fa fa-image"></i>
                                                <label :for="'fileCommentOverlay' + isPost.id" class="fileContainer">
                                                    <input :ref="'fieldMedia' + isPost.id" type="file" class="hidden"
                                                        :id="'fileCommentOverlay' + isPost.id" accept="image/*,video/*"
                                                        @input="getUploadedCommentFile($event)">
                                                </label>
                                            </div>
                                            <span class="em em-expressionless" title="add icon"></span>
                                            <div class="smiles-bunch">
                                                <i class="em em---1"></i>
                                                <i class="em em-smiley"></i>
                                                <i class="em em-anguished"></i>
                                                <i class="em em-laughing"></i>
                                                <i class="em em-angry"></i>
                                                <i class="em em-astonished"></i>
                                                <i class="em em-blush"></i>
                                                <i class="em em-disappointed"></i>
                                                <i class="em em-worried"></i>
                                                <i class="em em-kissing_heart"></i>
                                                <i class="em em-rage"></i>
                                                <i class="em em-stuck_out_tongue"></i>
                                            </div>
                                        </div>
                                        <button style="padding: 5px 20px;" type="submit">
                                            <i style="color: #535758;" class="fas fa-paper-plane"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div><!-- album post -->
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
        },
        comment_count: {
            type: Number,
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
        return {
            formComment: {
                content: ''
            },
            formMediaComment: ref({
            }),
            showSuggestions: false,
            selectedFriend: null,
            filteredFriends: [],
            isSendLoading: false,
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
        ...mapGetters('friends', ['allFriends']),
        friends() {
            return this.allFriends;
        },
        ...mapState({
            accounts: state => state.users.accounts
        }),
        ...mapGetters('friends', ['getFriendsWithUsers']),
        friendsWithUsers() {
            return this.getFriendsWithUsers;
        },
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
        }
    },
    created() {
    }
}
</script>
