<template>
    <!-- topbar -->
    <div class="topbar stick">
        <div class="logo">
            <router-link :to="{ name: 'Home Section' }" title="home">
                <h2 style="color: #fff;">Imnotify</h2>
            </router-link>
        </div>
        <div class="top-area">
            <div class="top-search">
                <form @submit.prevent="searchUsers">
                    <input type="text" v-model="searchQuery" placeholder="TÌm kiếm người dùng..." @input="onInput">
                    <button type="submit" data-ripple><i class="fas fa-search"></i></button>
                </form>
                <ul class="list_user_search" style="list-style: none; position: absolute;" v-if="results.length">
                    <li style="display: flex; gap: 5px; align-items: center;" v-for="user in results" :key="user.id">
                        <span><img width="40" :src="user.avatar" alt=""></span>
                        <router-link :to="{ name: 'Profile User', params: { id: user.user_id } }">{{ user.user_name
                            }}</router-link>
                    </li>
                </ul>
            </div>
            <ul class="setting-area">
                <li @click="clickHome"><a @clcik.prevent :class="{ active: $route.path === '/' && !showNot && !showKb }"
                        title="Home" v-ripple><i class="fa fa-home"></i></a></li>
                <li @click="showKb = !showKb">
                    <a :class="{ 'active': showKb }" href="#" title="Friend Requests" v-ripple>
                        <i class="fa fa-user"></i><em v-if="notificationCount > 0" class="bg-purple">{{
                            notificationCount }}</em>
                    </a>
                    <div class="dropdowns" :class="{ 'active': showKb }">
                        <span>{{ notificationCount }} yêu cầu</span>
                        <ul class="drops-menu">
                            <li  v-for="(notification, index) in notifications" :key="index">
                                <div>
                                    <figure>
                                        <img width="40" :src="notification.avatar" alt="">
                                    </figure>
                                    <div style="margin-top: 10px;" class="mesg-meta">
                                        <h6><a href="#" title="">{{notification.user_name}}</a></h6>
                                    </div>
                                    <div class="add-del-friends">
                                        <a href="#" title=""><i class="fa fa-heart"></i></a>
                                        <a href="#" title=""><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <a href="friend-requests.html" title="" class="more-mesg">View All</a>
                    </div>
                </li>
                <li @click="showNot = !showNot" class=''>
                    <a :class="{ 'active': showNot }" href="#" title="Notification" v-ripple>
                        <i class="fa fa-bell"></i><em v-if="notificationCount > 0" class="bg-purple">{{
                            notificationCount }}</em>
                    </a>
                    <div class="dropdowns" :class="{ 'active': showNot }">
                        <span>{{ notificationCount }} thông báo mới</span>
                        <ul class="drops-menu">
                            <li v-for="(notification, index) in notifications" :key="index">
                                <router-link :to="{ name: 'Profile User', params: { id: notification.friendId2 } }"
                                    href="#" title="">
                                    <figure>
                                        <img width="35" :src="notification.avatar" alt="">
                                        <span class="status f-online"></span>
                                    </figure>
                                    <div class="mesg-meta">
                                        <h6>{{ notification.user_name }}</h6>
                                        <span>{{ notification.type }}</span>
                                        <i>{{ notification.time }}</i>
                                    </div>
                                </router-link>
                            </li>

                        </ul>
                        <a href="#" title="" class="more-mesg">View All</a>
                    </div>
                </li>

            </ul>
            <div @click="showMenu = !showMenu" class="user-img">
                <h5>{{ authUser.user_name }}</h5>
                <img width="40" height="40" :src="authUser.avatar" alt="">
                <span class="status f-online"></span>
                <div class="user-setting" :class="{ 'active': showMenu }">
                    <ul class="log-out">
                        <li @click="lll" v-ripple><router-link
                                :to="{ name: 'Profile User', params: { id: authUser.user_id } }" href="#" title=""><i
                                    class="fas fa-user"></i>Trang cá nhân</router-link></li>
                        <li v-ripple><a href="#" title=""><i class="fas fa-pencil-alt"></i>Chỉnh sửa trang cá nhân</a>
                        </li>
                        <li v-ripple><router-link v-if="!authUser.user_face_regs.length > 0"
                                :to="{ name: 'RgFaceIF User' }" href="#" title=""><i class="fas fa-id-badge"></i>Đăng ký
                                khuôn mặt</router-link></li>
                        <li @click="deleteFaceId" v-ripple><a v-if="authUser.user_face_regs.length > 0"
                                :to="{ name: 'RgFaceIF User' }" href="#" title=""><i class="fas fa-close"></i>Xoá khuôn
                                mặt đã đăng ký</a></li>
                        <!-- <li><a href="#" title=""><i class="fas fa-user-slash"></i>Xoá xác thực khuôn mặt</a></li> -->
                        <li v-ripple><a href="#" title=""><i class="fas fa-cog"></i>Tài khoản</a></li>
                        <li v-ripple><a @click.prevent="logoutSubmit" title="đăng xuất">
                                <i class="fas fa-sign-out-alt"></i>Đăng
                                xuất</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div><!-- topbar -->
    <MediaDisplay v-if="isFileDisplay.length > 0" />
</template>
<script>
import Router from '../../../router';
import Swal from 'sweetalert2';
import { mapGetters, mapMutations, mapActions } from "vuex"
import { useGeneralStore } from '../../../store/general';
import { ref } from 'vue';
import { storeToRefs } from 'pinia';
import CropperModal from '../components/CropperModal.vue'
import MediaDisplay from '../Components/mediaDisplay.vue'
import EventBus from '../../../eventBus';
import Echo from 'laravel-echo';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';
export default {
    components: {
        CropperModal,
        MediaDisplay,
    },
    data() {
        const useGeneral = useGeneralStore();
        const { isPostOverlay, isCropperModal, isFileDisplay } = storeToRefs(useGeneral)
        return {
            showMenu: false,
            isPostOverlay,
            isCropperModal,
            isFileDisplay,
            isLoading: false,
            activeIndex: null,
            notifications: [],
            notificationCount: 0,
            userId: null,
            showNot: false,
            showKb: false,
            searchQuery: '',
            results: [],
            debounceTimeout: null,
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
        // EventBus.on('friend-request-sent', (payload) => {
        //     this.userId = payload.userId;
        // });
        toastr.options = {};
        window.Echo.private('user.' + this.authUser.id)
            .listen('FriendRequestSent', (event) => {
                console.log('Received event:', event);
                const notification = {
                    user_name: event.user_name,
                    type: event.message,
                    avatar: event.avatar || '',
                    friendId2: event.friendId2,
                    time: new Date().toLocaleTimeString(),
                };
                this.notifications.unshift(notification);
                this.notificationCount++;
                toastr.success(event.message, 'Thông báo', {
                    positionClass: 'toast-bottom-left',
                    backgroundColor: '#4CAF50',
                    progressBar: true,
                    closeButton: true,
                    timeOut: 10000,
                });
            });
    },

    methods: {
        ...mapActions(["logout"]),
        toggleActive(index) {
            this.activeIndex = this.activeIndex === index ? null : index;
        },
        onInput() {
            clearTimeout(this.debounceTimeout); // Xóa timeout cũ nếu có
            this.debounceTimeout = setTimeout(() => {
                if (this.searchQuery.length > 0) {
                    this.searchUsers(); // Gọi tìm kiếm sau thời gian chờ debounce
                } else {
                    this.results = []; // Xóa kết quả nếu không có giá trị nhập
                }
            }, 500);
        },
        searchUsers() {
            axios.get(`/api/user/search`, { params: { query: this.searchQuery } })
                .then(response => {
                    this.results = response.data;
                })
                .catch(error => {
                    console.error("Error during search:", error);
                });
        },

        clickHome() {
            if (this.$route.path === '/') {
                this.isLoading = true;
                this.$store.commit('post/RESET_ALL_POSTS');
                this.$store.dispatch('post/fetchPosts')
                    .then(response => {
                        this.isLoading = false;
                        window.scrollTo(0, 0);
                    })
                    .catch(error => {
                        this.isLoading = false;
                        console.error('Error:', error);
                    });
            } else {
                console.log("Bạn không ở trang Home, không thực hiện tải lại bài viết.");
                this.$router.push('/');
            }
        }
        ,
        logoutSubmit() {
            this.logout();
        },
        loadUserbyId() {
            this.showMenu = false
            this.$store.dispatch('post/getUserbyId', this.authUser.user_id)
                .then(response => {
                    this.myprofile = true
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        },
        deleteFaceId() {
            const data = { user_id: this.authUser.id, username: this.authUser.user_name };
            axios.post('http://127.0.0.1:5000/delete_face', data)
                .then(response => {
                    console.log(response);
                    Swal.fire({
                        icon: 'success',
                        text: `Success ${response.data.message}`,
                        showConfirmButton: false,
                        timer: 3000
                    })
                    this.$store.dispatch('setAuthuser')
                    Router.push('/');
                })
                .catch(error => {
                    console.error('Lỗi khi gửi yêu cầu:', error);
                    console.error('Lỗi khi gửi yêu cầu:', error);
                    let errorMessage = 'Có lỗi xảy ra khi gửi yêu cầu';
                    if (error.response && error.response.data && error.response.data.message) {
                        errorMessage = error.response.data.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        text: errorMessage,
                        showConfirmButton: false,
                        timer: 3000
                    });

                })
        }
    }
}
</script>
<style scoped>
.list_user_search {
    font-size: 16px;
    list-style: none;
    position: absolute;
    top: 60px;
    margin: 0;
    padding: 0;
    color: #fa6342;
    width: 400px;
    min-height: 300px;
    background: #fff none repeat scroll 0 0;
    border-radius: 10px;
    z-index: 1000;
    font-weight: 500;
    z-index: 1000;
    padding: 15px;
}

.list_user_search li {
    padding: 5px;
    margin-bottom: 10px;
    border-bottom: 1px solid;
}

.list_user_search img {
    border-radius: 50%;
}
</style>