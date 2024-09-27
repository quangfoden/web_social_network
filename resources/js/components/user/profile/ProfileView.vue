<template>
    <div @click="isPostOverlay = false" v-show="isPostOverlay" class="postoverlay"></div>
    <skeleton-loader v-if="!myProfile" :count="3" />
    <div v-else class="gap2 gray-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row merged20" id="page-contents">
                        <div class="user-profile">
                            <figure :class="{ 'no-photo_cover': !user.cover_photo }">
                                <div class="edit-pp">
                                    <label class="fileContainer">
                                        <i class="fa fa-camera"></i>
                                        <input type="file">
                                    </label>
                                </div>
                                <img v-if="user.cover_photo" src="/images/resources/profile-image.jpg" alt="">
                                <ul v-if="user.user_id !== authUser.user_id" class="profile-controls">
                                    <li v-if="!isFriend && !isRequested && !isRequestedByThem"
                                        @click="sendFriendRequest(user.id)"><a @click.prevent class="text-white"
                                            href="#" title="Thêm bạn bè"><i class="fa fa-user-plus"></i></a></li>
                                    <li v-if="isRequested" @click="cancelRequest(user.id)"><a class="text-white"
                                            href="#" title="Huỷ yêu cầu"><i class="fa fa-ban"></i></a></li>
                                    <li v-if="isRequestedByThem" @click="acceptRequest(user.id)"><a class="text-white"
                                            href="#" title="Chấp nhận"><i class="fa fa-user-check"></i></a></li>
                                    <li v-if="isRequestedByThem" @click="cancelRequestFr(user.id)"><a class="text-white"
                                            href="#" title="Từ chối"><i class="fas fa-times-circle"></i></a></li>
                                    <li @click="showUnfriend = !showUnfriend" v-if="isFriend"><a class="text-white"
                                            href="#" title="Bạn bè"><i class="fa fa-user"></i></a>
                                    </li>
                                    <li v-if="isFriend && showUnfriend" @click="unfriend(user.id)"><a class="text-white"
                                            href="#" title="Huỷ kết bạn"> <i class="fa fa-user-times"></i></a></li>
                                    <li><a class="text-white send-mesg" href="#" title="Nhắn tin"><i
                                                class="fa fa-comment"></i></a></li>
                                </ul>
                            </figure>

                            <div class="profile-section">
                                <div class="row">
                                    <div class="col-lg-2 col-md-3">
                                        <div class="profile-author">
                                            <div class="profile-author-thumb">
                                                <img alt="author" :src="user.avatar">
                                                <div class="edit-dp">
                                                    <label class="fileContainer">
                                                        <i class="fa fa-camera"></i>
                                                        <input type="file">
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="author-content">
                                                <a style="text-decoration: none" class="h4 author-name"
                                                    href="about.html">{{ user.user_name }}</a>
                                                <div class="country">Ontario, CA</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-md-9">
                                        <ul class="profile-menu">
                                            <li>
                                                <router-link :to="{ name: 'TimeLine User' }"
                                                    exact-active-class="active">Dòng thời gian</router-link>
                                            </li>
                                            <li>
                                                <router-link :to="{ name: 'About User' }"
                                                    exact-active-class="active">Giới
                                                    thiệu</router-link>
                                            </li>
                                            <li v-if="user.user_id === authUser.user_id">
                                                <router-link :to="{name:'Edit Profile'}" exact-active-class="active" class="" href="#">Chỉnh sửa trang cá nhân</router-link>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div><!-- user profile banner  -->
                        <router-view></router-view>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.no-photo_cover {
    min-height: 300px;
    background: black;
}
</style>
<script>
import { mapState, mapActions, mapGetters } from 'vuex';
import SkeletonLoader from 'vue-skeleton-loading';
import Post from '../Components/Post.vue';
import LeftProfile from './LeftProfile.vue'
import RightProfile from './RightProfile.vue';
import SidebarRight from '../layouts/SidebarRight.vue';
import { ref } from 'vue';
import { data } from 'jquery';
import CreatePostBox from '../Components/CreatePostBox.vue'
import { useGeneralStore } from '../../../store/general';
import { storeToRefs } from 'pinia';
import { post } from 'jquery';
import EventBus from '../../../eventBus';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';
export default {
    data() {
        const useGeneral = useGeneralStore();
        const { isPostOverlay } = storeToRefs(useGeneral)
        return {
            isPostOverlay,
            loading: false,
            userId: null,
            InUser: ref({}),
            myProfile: ref(false),
            isFirstLoad: true,
            isFriend: false,
            isRequested: false,
            isRequestedByThem: false,
            showUnfriend: false
        }
    },
    components: {
        SkeletonLoader,
        CreatePostBox,
        Post,
        LeftProfile,
        RightProfile,
        SidebarRight
    },
    computed: {
        ...mapState('post', ['user']),
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
        isAuthUser() {
            return this.userId == this.authUser.user_id;
        },
    },
    mounted() {
        window.scrollTo(0, 0);
        this.getStatusFriend()
    },
    methods: {
        checkPrivacy(post) {
            if (post.privacy == 'only_me') {
                if (this.isAuthUser) {
                    return true
                }
                return false
            }
            return true
        },
        isProfile(user_id) {
            if (user_id === this.userId) {
                return true
            }
            return false
        },

        loadUserbyId() {
            this.myProfile = false
            this.$store.dispatch('post/getUserbyId', this.userId)
                .then(response => {
                    this.myProfile = true
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        },
        resetData() {
            this.$store.commit('post/RESET_POSTS_BY_USER');
        },
        handleCommentCreated(postId) {
            const post = this.postsByUser.find(p => p.id === postId);
            if (post) {
                post.comment_count += 1;
            }
        },
        handleCommentdeleted(postId) {
            const post = this.postsByUser.find(p => p.id === postId);
            if (post) {
                post.comment_count -= 1;
            }
        },
        loadUserProfile() {
            this.resetData();
            this.loadUserbyId();
        },
        handleUpdatedLike(postId) {
            const post = this.postsByUser.find(p => p.id === postId);
            if (post) {
                post.like_count += 1;
            }
        },
        handleLikedeleted(postId) {
            const post = this.postsByUser.find(p => p.id === postId);
            if (post) {
                post.like_count -= 1;
            }
        },
        getStatusFriend() {
            axios.get(`/api/user/friends/status/${this.userId}`)
                .then(response => {
                    this.isFriend = response.data.isFriend;
                    this.isRequested = response.data.isRequested;
                    this.isRequestedByThem = response.data.isRequestedByThem;
                });
        },
        sendFriendRequest(userId) {
            axios.post('/api/user/friends/request', { friend_id: userId })
                .then(response => {
                    this.isRequested = true
                    toastr.success('Đã gửi yêu cầu kết bạn thành công!', 'Thông báo', {
                        positionClass: 'toast-bottom-left',
                        backgroundColor: '#4CAF50',
                        progressBar: true,
                        closeButton: true,
                        timeOut: 10000,
                    });
                    EventBus.emit('friend-request-sent', {
                        message: response.data.message,
                        userId: userId
                    });
                })
                .catch(error => {
                    this.getStatusFriend()
                    toastr.error('Đã xảy ra lỗi khi gửi yêu cầu kết bạn.', 'Thông báo', {
                        positionClass: 'toast-bottom-left',
                        backgroundColor: '#F44336',
                        progressBar: true,
                        closeButton: true,
                        timeOut: 10000,
                    });
                    if (error.response) {
                        console.error(error.response.data.message);
                    } else {
                        console.error('Error:', error.message);
                    }
                });
        },
        cancelRequest(friendId) {
            axios.post(`/api/user/friends/cancel-request`, {
                friend_id: friendId
            })
                .then(response => {
                    if (response.data.success) {
                        this.isRequested = false;
                        this.isFriend = false;
                        toastr.success('Đã hủy yêu cầu kết bạn!', 'Thông báo', {
                            positionClass: 'toast-bottom-left',
                            backgroundColor: '#4CAF50',
                            progressBar: true,
                            closeButton: true,
                            timeOut: 10000,
                        });
                    } else {
                        this.getStatusFriend()
                        toastr.error('Hủy yêu cầu kết bạn không thành công.', 'Thông báo', {
                            positionClass: 'toast-bottom-left',
                            backgroundColor: '#F44336',
                            progressBar: true,
                            closeButton: true,
                            timeOut: 10000,
                        });
                    }
                })
                .catch(error => {
                    this.getStatusFriend()
                    console.error('Error canceling friend request:', error);
                    toastr.error('Đã xảy ra lỗi khi hủy yêu cầu kết bạn.', 'Thông báo', {
                        positionClass: 'toast-bottom-left',
                        backgroundColor: '#F44336',
                        progressBar: true,
                        closeButton: true,
                        timeOut: 10000,
                    });
                });
        },
        acceptRequest(friendId) {
            axios.post(`/api/user/friends/accept/${friendId}`)
                .then(response => {
                    this.isFriend = true;
                    this.isRequestedByThem = false;
                    toastr.success('Đồng ý kết bạn thành công!', 'Thông báo', {
                        positionClass: 'toast-bottom-left',
                        backgroundColor: '#4CAF50',
                        progressBar: true,
                        closeButton: true,
                        timeOut: 10000,
                    });
                })
                .catch(error => {
                    this.getStatusFriend()
                    toastr.error('Đã xảy ra lỗi vui lòng thử lại sau!', 'Thông báo', {
                        positionClass: 'toast-bottom-left',
                        backgroundColor: '#F44336',
                        progressBar: true,
                        closeButton: true,
                        timeOut: 10000,
                    });
                });
        },

        cancelRequestFr(friendId) {
            axios.delete(`/api/user/friends/cancel/${friendId}`)
                .then(response => {
                    this.isRequestedByThem = false;
                    toastr.success(response.data.message, 'Thông báo', {
                        positionClass: 'toast-bottom-left',
                        progressBar: true,
                        closeButton: true,
                        timeOut: 5000,
                    });
                })
                .catch(error => {
                    this.getStatusFriend()
                    toastr.error('Có lỗi xảy ra khi huỷ yêu cầu kết bạn', 'Thông báo', {
                        positionClass: 'toast-bottom-left',
                        progressBar: true,
                        closeButton: true,
                        timeOut: 5000,
                    });
                });
        },

        unfriend(friendId) {
            if (confirm('Bạn có chắc chắn muốn huỷ kết bạn?')) {
                axios.delete(`/api/user/friends/unfriend/${friendId}`)
                    .then(response => {
                        this.isFriend = false;
                        toastr.success('Huỷ kết bạn thành công!', 'Thông báo', {
                            positionClass: 'toast-bottom-left',
                            backgroundColor: '#4CAF50',
                            progressBar: true,
                            closeButton: true,
                            timeOut: 10000,
                        });
                    })
                    .catch(error => {
                        this.getStatusFriend();
                        toastr.error('Đã xảy ra lỗi vui lòng thử lại sau!', 'Thông báo', {
                            positionClass: 'toast-bottom-left',
                            backgroundColor: '#F44336',
                            progressBar: true,
                            closeButton: true,
                            timeOut: 10000,
                        });
                    });
            } else {
                toastr.info('Bạn đã huỷ thao tác huỷ kết bạn.', 'Thông báo', {
                    positionClass: 'toast-bottom-left',
                    closeButton: true,
                    timeOut: 5000,
                });
            }
        },


    },
    watch: {
        '$route.params.id': {
            immediate: true,
            handler(newId, oldId) {
                this.userId = parseInt(newId)
                if (this.isFirstLoad || newId !== oldId) {
                    this.isFirstLoad = false;
                    this.loadUserProfile();
                    this.getStatusFriend()
                }
            }
        }
    },
}

</script>