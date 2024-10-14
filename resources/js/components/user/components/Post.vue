<template>

    <div @click="closeEditModalEditPost" v-show="isEditPostOverlay" class="posteditoverlay"></div>
    <div class="loadMore">
        <p class="d-inline pb-2 border-bottom m-0 secondary-text " v-if="pinned === 1 || pinned === true"> Bài viết đã
            ghim
        </p>
        <i v-if="pinned === 1 || pinned === true" class="fas fa-thumbtack"></i>
        <div class="central-meta item">
            <div class="user-post">
                <div class="friend-info">
                    <figure>
                        <img :src="user.avatar" alt="">
                    </figure>
                    <div class="friend-name">
                        <div class="more">
                            <div class="more-post-optns"><i class="fa-solid fa-ellipsis"></i>
                                <ul v-if="post.user_id === authUser.id">
                                    <li v-if="pinned === 0 || pinned === false" @click="togglePin(post.id)">
                                        <i class="fas fa-thumbtack"></i> Ghim bài viết
                                    </li>
                                    <li v-if="pinned === 1 || pinned === true" @click="togglePin(post.id)">
                                        <i class="fas fa-times"></i> Bỏ ghim
                                    </li>
                                    <li @click="showBoxPostEdit(post)"><i class="fa fa-pencil-square-o"></i>Chỉnh sửa
                                        bài viết
                                    </li>
                                    <li @click="confirmTashPost(post.id)"><i class="fa fa-trash-o"></i>Chuyển vào thùng
                                        rác</li>
                                    <li v-if="!status" @click="trashPost(post.id)"><i class="fa fa-undo"></i>Khôi phục
                                    </li>

                                    <!-- <li class="bad-report"><i class="fa fa-flag"></i>Report Post</li>
                                    <li><i class="fa fa-address-card-o"></i>Boost This Post</li>
                                    <li><i class="fa fa-clock-o"></i>Schedule Post</li>
                                    <li><i class="fa fa-wpexplorer"></i>Select as featured</li>
                                    <li><i class="fa fa-bell-slash-o"></i>Turn off Notifications</li> -->
                                </ul>
                                <ul v-else>
                                    <li><i class="fa fa-save"></i>Lưu bài viết
                                    </li>
                                    <li><i class="fa fa-flag"></i>Báo cáo bài viết</li>
                                    <li><i class="fa fa-ban"></i>Chặn {{ post.user.user_name }} </li>
                                    <!-- <li class="bad-report"><i class="fa fa-flag"></i>Report Post</li>
                                    <li><i class="fa fa-address-card-o"></i>Boost This Post</li>
                                    <li><i class="fa fa-clock-o"></i>Schedule Post</li>
                                    <li><i class="fa fa-wpexplorer"></i>Select as featured</li>
                                    <li><i class="fa fa-bell-slash-o"></i>Turn off Notifications</li> -->
                                </ul>
                            </div>
                        </div>
                        <ins><router-link :to="{ name: 'Profile User', params: { id: user.user_id } }" title="">{{
                            user.user_name }}</router-link></ins>
                        <span>
                            <i v-if="post.privacy === 'public'" class="fas fa-globe"></i>
                            <i v-if="post.privacy === 'friends'" class="fas fa-user-friends"></i>
                            <i v-if="post.privacy === 'only_me'" class="fas fa-lock"></i>
                            {{ post.created_at_formatted }}
                        </span>
                    </div>
                    <div class="post-meta">
                        <p>
                            {{ post.content ? post.content : '' }}
                        </p>
                        <figure>
                            <div class="img-bunch">
                                <div class="row">
                                    <div class="col-12">
                                        <div
                                            :class="{ 'one-media': media.length === 1, 'multiple-medias': media.length > 1 }">
                                            <figure v-for=" medias in media.slice(0, 3)" :key="media.id">
                                                <a target="_blank" v-if="medias.type.includes('image')" class="strip"
                                                    :href="medias.url" title="" data-strip-group="mygroup"
                                                    data-strip-group-options="loop: false">
                                                    <img :src="medias.url" alt="">
                                                    <i @click="handleIconClick($event, medias)"
                                                        style="color:#fff; transform:translatey(-200px);padding:10px;"
                                                        class="fa-regular fa-eye"></i>
                                                </a>
                                                <a target="_blank" v-else-if="medias.type.includes('video')"
                                                    class="strip" :href="medias.url" title="" data-strip-group="mygroup"
                                                    data-strip-group-options="loop: false">
                                                    <video :src="medias.url" class="" autoplay controls muted>
                                                    </video>
                                                </a>
                                                <div style="width: 500px;padding-inline: 40px;"
                                                    v-else-if="medias.type.includes('audio')">
                                                    <audio class="rounded-lg mx-auto w-100" controls>
                                                        <source :src="medias.url" type="audio/mpeg">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                </div>
                                            </figure>

                                            <figure style="overflow: hidden;" v-if="media.length >= 5">
                                                <a target="_blank" v-if="media[3].type.includes('image')" class="strip"
                                                    :href="media[3].url" title="" data-strip-group="mygroup"
                                                    data-strip-group-options="loop: false">
                                                    <img :src="media[3].url" alt="">
                                                </a>
                                                <a target="_blank" v-else-if="media[3].type.includes('video')"
                                                    class="strip" :href="media[3].url" title=""
                                                    data-strip-group="mygroup" data-strip-group-options="loop: false">
                                                    <video :src="media[3].url" class="" autoplay controls muted>
                                                    </video>
                                                </a>
                                                <div class="more-photos">
                                                    <span>+{{ media.length - 3 }}</span>
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
                                    <li v-if="!hasLiked" @click="createLike(typeLike, post.id)"
                                        :class="{ 'p-2': !hasLiked }" class="custom-cursor-pointer ">
                                        <span><i class="fas fa-thumbs-up secondary-text"></i></span>
                                    </li>
                                    <li class="p-2 custom-cursor-pointer k" v-if="hasLiked">
                                        <div v-for="like in likes" :key="like.id">
                                            <div v-if="like.user_id === authUser.id">
                                                <span class="hover-span" style="color: #fa6342;"
                                                    @click="unLike(post.id)" v-if="like.type === 'Like'">
                                                    <i class="fas fa-thumbs-up"></i>

                                                </span>
                                                <span class="hover-span" style="color: rgb(243, 62, 88);"
                                                    @click="unLike(post.id)" v-if="like.type === 'Heart'"><i
                                                        class="em em-heart"></i></span>
                                                <span class="hover-span" style="color: rgb(247, 177, 37);"
                                                    @click="unLike(post.id)" v-if="like.type === 'Laugh'"><i
                                                        class="em em-laughing"></i></span>
                                                <span class="hover-span" style="color: rgb(247, 177, 37);"
                                                    @click="unLike(post.id)" v-if="like.type === 'Sad'"><i
                                                        class="em em-cry"></i></span>
                                                <span class="hover-span" style="color: rgb(247, 177, 37);"
                                                    @click="unLike(post.id)" v-if="like.type === 'Infuriating'"><i
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
                                            @click="createLike(typeLike, post.id)"><i
                                                class="fas fa-thumbs-up blue-color"></i></span>
                                        <span class="custom-cursor-pointer" @click="createLike(typeHeart, post.id)"><i
                                                class="em em-heart"></i></span>
                                        <span class="custom-cursor-pointer" @click="createLike(typeLaugh, post.id)">
                                            <i class="em em-laughing"></i>
                                        </span>
                                        <span class="custom-cursor-pointer" @click="createLike(typeSad, post.id)">
                                            <i class="em em-cry"></i>
                                        </span>
                                        <span class="custom-cursor-pointer"
                                            @click="createLike(typeInfuriating, post.id)">
                                            <i class="em em-rage"></i> </span>
                                    </div>
                                </span>
                                <li @click="showModalpost(post.id)">
                                    <span class="comment" title="Comments">
                                        <i class="fa fa-commenting"></i>
                                        <ins v-if="comment_count && comment_count != 0">{{ comment_count }}</ins>
                                    </span>
                                </li>

                                <li @click.prevent="sharePost(post.id)">
                                    <span>
                                        <a class="share-pst" href="#" title="Share">
                                            <i class="fa fa-share-alt"></i>
                                        </a>
                                        <ins></ins>
                                    </span>
                                    <div style="position: absolute;
    right: 200px;
    top: 5px;
" v-if="isSharing" class="share-options">
                                        <select @click="handleIconClick2($event)" v-model="privacy">
                                            <option value="public">Công khai</option>
                                            <option value="friends">Bạn bè</option>
                                            <option value="only_me">Chỉ mình tôi</option>
                                        </select>
                                        <button style="border: none;
    margin-left: 10px;
    border-radius: 10px;" @click="confirmShare">Chia sẽ</button>
                                    </div>
                                </li>
                            </ul>
                            <!-- <div class="users-thumb-list">
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
                            </div> -->
                        </div>
                    </div>
                    <div class="coment-area" style="display: block;">
                        <ul class="we-comet">
                            <Comment v-if="comments.length > 0 && !isPostOverLay" :comment="comments[0]"
                                :repcomment_count="comments[0].repcomment_count" @comment-updated="handleCommentUpdated"
                                @repcomment-created="handleRepCommentCreated(comments[0].id)"
                                @comment-deleted="handleCommentDeleted"
                                @repcomment-deleted="handleRepCommentDeleted(comments[0].id)" />
                            <li>
                                <a v-if="comments.length > 1" @click.prevent="showModalpost(post.id)" href="#" title=""
                                    class="showmore underline" style="margin: 0">Xem tất cả {{ comment_count }} bình
                                    luận</a>
                            </li>
                            <li class="post-comment">
                                <div class="comet-avatar">
                                    <img :src="authUser.avatar" alt="">
                                </div>
                                <div class="post-comt-box">
                                    <div v-if="formMediaComment.url" class="mb-2 cus-img-dis">
                                        <span
                                            class="position-absolute bg-white p-1 m-2 right-2 z-1000 rounded-full border custom-cursor-pointer"
                                            @click="clearFile(post.id)"><i class="fas fa-close"></i></span>
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
                                    <form style="position: relative;" @submit.prevent="CreateComment(post.id)"
                                        method="post">
                                        <textarea style="resize: none;"
                                            :placeholder="`Viết bình luận với vai trò ${authUser.user_name} ...`"
                                            :ref="'textAreaComment' + post.id" @input="onInput(post.id, $event)"
                                            v-model="formComment.content"></textarea>
                                        <ul v-show="showSuggestions && filteredFriends.length >= 1"
                                            class="suggestions rounded  position-absolute">
                                            <li v-for="friend in filteredFriends" :key="friend.id" class="rounded"
                                                @click="selectFriend(friend, post.id)">
                                                <div class="d-flex gap-2 align-items-center">
                                                    <img width="40px" class="rounded-full ml-1 img-cus"
                                                        :src="friend.avatar" alt="">
                                                    <p class="primary-text fw-bold mb-0">{{ friend.user_name }}</p>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="add-smiles">
                                            <div class="uploadimage">
                                                <i class="fa fa-image"></i>
                                                <label class="fileContainer">
                                                    <input :ref="'fieldMedia' + post.id" type="file" class="hidden"
                                                        :id="'fileComment' + post.id" accept="image/*,video/*"
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
    <!-- <div v-else-if="post.type === 'share'"> -->
    <!-- Hiển thị bài chia sẻ -->
    <!-- <h3>{{ post.user.user_name }} đã chia sẻ một bài viết:</h3>
        <p>{{ post.content }}</p>
        <small>{{ post.created_at_formatted }}</small>
      </div> -->
    <div  v-if="post.type === 'share'">

        <div class="loadMore">
            <p class="d-inline pb-2 border-bottom m-0 secondary-text " v-if="pinned === 1 || pinned === true"> Bài viết
                đã
                ghim
            </p>
            <i v-if="pinned === 1 || pinned === true" class="fas fa-thumbtack"></i>
            <div class="central-meta item">
                <div class="share-post" style="margin: 0;">
                    <figure style="display: inline; margin-right: 10px;">
                        <img width="40" style="border-radius: 100%;" :src="post.user_share.avatar" alt="">
                    </figure>
                    <router-link :to="{ name: 'Profile User', params: { id: post.user_share.user_id } }" title="">{{
                        post.user_share.user_name }}</router-link> đã chia sẽ bài viết của
                    <router-link :to="{ name: 'Profile User', params: { id: user.user_id } }" title="">{{
                        post.user.user_name }}</router-link>
                </div>
                <div class="user-post" style="padding:10px 20px;border-top: 1px solid #fa6342;">
                    <div class="friend-info">
                        <figure>
                            <img :src="user.avatar" alt="">
                        </figure>
                        <div class="friend-name">
                            <ins><router-link :to="{ name: 'Profile User', params: { id: user.user_id } }" title="">{{
                                user.user_name }}</router-link></ins>
                            <span>
                                <i v-if="post.privacy === 'public'" class="fas fa-globe"></i>
                                <i v-if="post.privacy === 'friends'" class="fas fa-user-friends"></i>
                                <i v-if="post.privacy === 'only_me'" class="fas fa-lock"></i>
                                {{ post.created_at_formatted }}
                            </span>
                        </div>
                        <div class="post-meta">
                            <p>
                                {{ post.content ? post.content : '' }}
                            </p>
                            <figure>
                                <div class="img-bunch">
                                    <div class="row">
                                        <div class="col-12">
                                            <div
                                                :class="{ 'one-media': media.length === 1, 'multiple-medias': media.length > 1 }">
                                                <figure v-for=" medias in media.slice(0, 3)" :key="media.id">
                                                    <a target="_blank" v-if="medias.type.includes('image')"
                                                        class="strip" :href="medias.url" title=""
                                                        data-strip-group="mygroup"
                                                        data-strip-group-options="loop: false">
                                                        <img :src="medias.url" alt="">
                                                        <i @click="handleIconClick($event, medias)"
                                                            style="color:#fff; transform:translatey(-200px);padding:10px;"
                                                            class="fa-regular fa-eye"></i>
                                                    </a>
                                                    <a target="_blank" v-else-if="medias.type.includes('video')"
                                                        class="strip" :href="medias.url" title=""
                                                        data-strip-group="mygroup"
                                                        data-strip-group-options="loop: false">
                                                        <video :src="medias.url" class="" autoplay controls muted>
                                                        </video>
                                                    </a>
                                                    <div style="width: 500px;padding-inline: 40px;"
                                                        v-else-if="medias.type.includes('audio')">
                                                        <audio class="rounded-lg mx-auto w-100" controls>
                                                            <source :src="medias.url" type="audio/mpeg">
                                                            Your browser does not support the audio element.
                                                        </audio>
                                                    </div>
                                                </figure>

                                                <figure style="overflow: hidden;" v-if="media.length >= 5">
                                                    <a target="_blank" v-if="media[3].type.includes('image')"
                                                        class="strip" :href="media[3].url" title=""
                                                        data-strip-group="mygroup"
                                                        data-strip-group-options="loop: false">
                                                        <img :src="media[3].url" alt="">
                                                    </a>
                                                    <a target="_blank" v-else-if="media[3].type.includes('video')"
                                                        class="strip" :href="media[3].url" title=""
                                                        data-strip-group="mygroup"
                                                        data-strip-group-options="loop: false">
                                                        <video :src="media[3].url" class="" autoplay controls muted>
                                                        </video>
                                                    </a>
                                                    <div class="more-photos">
                                                        <span>+{{ media.length - 3 }}</span>
                                                    </div>
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </figure>
                        </div>
                    </div>

                </div>
                <p style="text-align: center;
    text-decoration: underline; margin: 0;cursor:pointer;">Xem bài viết</p>
            </div><!-- album post -->
        </div>
    </div>
    <EditPostOverlay :postEdit="postBeingEdited" :medias="postBeingEdited.media"
        v-if="isEditPostOverlay && postBeingEdited && postBeingEdited.id == post.id"
        @close-modalEditPost="closeEditModalEditPost" />

    <ModalPostOverLay :isPost="post" :medias="post.media" :comments="post.comments" :comment_count="comment_count"
        v-if="isPostOverLay" :likes="post.likes" :like_count="post.like_count" @close-modal-post="closeModalPost"
        @comment_overlay-created="handleCommentOverLayCreated" @repcomment-created="handleRepCommentCreated(comment.id)"
        @comment_overlay-deleted="handleCommentoVerLayDeleted" @updated_like="handleUpdatedLike(post.id)"
        @deleted_like="handleLikedeleted(post.id)" />
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
import { post } from 'jquery';
import axios from 'axios';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';
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
            postBeingEdited: null,
            isEditPostOverlay: false,
            formComment: ref({
                content: ''
            }),
            formMediaComment: ref({
            }),
            showListUserLike: false,
            showAllComments: false,
            showSuggestions: false,
            filteredFriends: [],
            selectedFriend: null,
            isSendLoading: false,
            // likes: [],
            typeLiked: null,
            typeLike: "Like",
            typeHeart: "Heart",
            typeLaugh: "Laugh",
            typeSad: "Sad",
            typeInfuriating: "Infuriating",
            isSharing: false,
            privacy: 'public',
        }
    },
    computed: {
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
        friendsWithUsers() {
            return this.getFriendsWithUsers;
        },
        ...mapGetters('friends', ['allFriends']),
        friends() {
            return this.allFriends;
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
        sharePost(postId) {
            this.isSharing = !this.isSharing;
        },
        async confirmShare() {
            try {
                const response = await axios.post('/api/user/share', {
                    post_id: this.post.id,
                    privacy: this.privacy,
                });
                this.$store.commit('post/RESET_ALL_POSTS');
                this.$store.dispatch('post/fetchPosts')
                window.scrollTo(0, 0);
                toastr.success(response.data.message, 'Thông báo', {
                    positionClass: 'toast-bottom-left',
                    backgroundColor: '#4CAF50',
                    progressBar: true,
                    closeButton: true,
                    timeOut: 10000,
                });
                this.isSharing = false;
            } catch (error) {
                console.error(error);
                alert('Error sharing the post');
            }
        },
        showBoxPostEdit(post) {
            this.postBeingEdited = post
            this.isEditPostOverlay = true
        },
        closeModalPost() {
            this.isPostOverLay = false;
        },
        closeEditModalEditPost() {
            this.postBeingEdited = null
            console.log(this.postBeingEdited);
            this.isEditPostOverlay = false
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
                        if (fieldMediaCMRef.value) {
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
                // text: 'Hành động này sẽ không thể hoàn tác!',
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

        handleIconClick(event, medias) {
            event.stopPropagation();  // Ngăn chặn sự kiện click lan truyền
            event.preventDefault();   // Ngăn chặn hành động mặc định (điều hướng)
            this.convertImageUrl(medias);  // Gọi hàm xử lý của bạn
        },
        handleIconClick2(event) {
            event.stopPropagation();  // Ngăn chặn sự kiện click lan truyền
            event.preventDefault();   // Ngăn chặn hành động mặc định (điều hướng)
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
                    friend => diacritics.remove(friend.user_name.toLowerCase()).includes(query)
                );
                this.showSuggestions = true;
                console.log(this.friends);

            } else {
                this.showSuggestions = false;
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