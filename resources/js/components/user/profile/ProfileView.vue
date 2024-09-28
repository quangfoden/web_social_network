<template>
    <div @click="isPostOverlay = false" v-show="isPostOverlay" class="postoverlay"></div>
    <div v-if="showModal2" class="modal">
        <div class="modal-content">
            <h3>Ảnh đã chọn</h3>
            <img :src="imageSrc" alt="Selected Image" class="selected-image" />
            <div class="modal-buttons">
                <button @click="saverImageCover">Lưu</button>
                <button @click="closeModal2">Hủy</button>
            </div>
        </div>
    </div>
    <div v-if="showModal" class="modal">
        <div class="modal-content">
            <h3>Ảnh đã chọn</h3>
            <img :src="imageSrc" alt="Selected Image" class="selected-image" />
            <div class="modal-buttons">
                <button @click="saveImage">Lưu</button>
                <button @click="closeModal">Hủy</button>
            </div>
        </div>
    </div>
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
                                        <input @change="onFileChange2" type="file">
                                    </label>
                                </div>

                                <img v-if="user.cover_photo" :src="user.cover_photo" alt="">
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
                                                        <input @change="onFileChange" type="file">
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="author-content">
                                                <a style="text-decoration: none" class="h4 author-name" href="#">{{
                                                    user.user_name }}</a>
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
                                                <router-link :to="{ name: 'Edit Profile' }" exact-active-class="active"
                                                    class="" href="#">Chỉnh sửa trang cá nhân</router-link>
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
            showUnfriend: false,
            showModal: false,
            imageSrc: '',
            selectedFile: null,
            selectedFileCover: null,
            showModal2: false,
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

        onFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.selectedFile = file;
                this.showModal = true; // Mở modal
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imageSrc = e.target.result; // Set hình ảnh để hiển thị trong modal
                };
                reader.readAsDataURL(file);
            }
        },
        onFileChange2(event) {
            const file = event.target.files[0];
            if (file) {
                this.selectedFileCover = file;
                this.showModal2 = true; // Mở modal
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imageSrc = e.target.result; // Set hình ảnh để hiển thị trong modal
                };
                reader.readAsDataURL(file);
            }
        },
        saveImage() {
            if (this.selectedFile) {
                const formData = new FormData();
                formData.append('avatar', this.selectedFile);
                // Gọi API để cập nhật thông tin người dùng
                axios.post(`/api/user/update-profile/photo/${this.userId}`, formData)
                    .then(response => {
                        toastr.success('Cập nhật ảnh đại diện thành công !', 'Thông báo', {
                            positionClass: 'toast-bottom-left',
                            backgroundColor: '#4CAF50',
                            progressBar: true,
                            closeButton: true,
                            timeOut: 10000,
                        });
                        localStorage.setItem('authUser', JSON.stringify(response.data.user));
                        window.location.reload()
                        console.log('Profile updated successfully:', response.data.user);
                    })
                    .catch(error => {
                        toastr.error('Đã xảy ra lỗi vui lòng thử lại!', 'Thông báo', {
                            positionClass: 'toast-bottom-left',
                            backgroundColor: '#F44336',
                            progressBar: true,
                            closeButton: true,
                            timeOut: 10000,
                        });
                        console.error('Error updating profile:', error.response.data);
                    });
            }
            this.closeModal();
        },

        saverImageCover(){
            if (this.selectedFileCover) {
                const formData = new FormData();
                formData.append('cover_photo', this.selectedFileCover);
                // Gọi API để cập nhật thông tin người dùng
                axios.post(`/api/user/update-profile/photo_cover/${this.userId}`, formData)
                    .then(response => {
                        toastr.success('Cập nhật ảnh bìa thành công !', 'Thông báo', {
                            positionClass: 'toast-bottom-left',
                            backgroundColor: '#4CAF50',
                            progressBar: true,
                            closeButton: true,
                            timeOut: 10000,
                        });
                        localStorage.setItem('authUser', JSON.stringify(response.data.user));
                        window.location.reload()
                        console.log('Profile updated successfully:', response.data.user);
                    })
                    .catch(error => {
                        toastr.error('Đã xảy ra lỗi vui lòng thử lại!', 'Thông báo', {
                            positionClass: 'toast-bottom-left',
                            backgroundColor: '#F44336',
                            progressBar: true,
                            closeButton: true,
                            timeOut: 10000,
                        });
                        console.error('Error updating profile:', error.response.data);
                    });
            }
            this.closeModal2();
        },
        closeModal() {
            this.showModal = false;
            this.imageSrc = '';
        },

        closeModal2() {
            this.showModal2 = false;
            this.imageSrc = '';
        }

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
<style scoped>
.modal {
    display: flex;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 300px;
    height: 300px;
}

.selected-image {
    max-width: 150px;
    max-height: 150px;
    margin: auto;
    border-radius: 100%;
}

.modal-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.modal-buttons button {
    border: none
}
</style>
