<template>
    <!-- topbar -->
    <div class="topbar stick">
        <div class="logo">
            <a title="" href="newsfeed.html"><img src="/images/logo/logo2.png" alt=""></a>
        </div>
        <div class="top-area">
            <div class="top-search">
                <form method="post" class="">
                    <input type="text" placeholder="Search People, Pages, Groups etc">
                    <button data-ripple><i class="fas fa-search"></i></button>
                </form>
            </div>
            <ul class="setting-area">
                <li><a @click.prevent="clickHome" href="newsfeed.html" title="Home" v-ripple><i
                            class="fa fa-home"></i></a></li>
                <li>
                    <a href="#" title="Friend Requests" v-ripple>
                        <i class="fa fa-user"></i><em class="bg-red">5</em>
                    </a>
                    <div class="dropdowns">
                        <span>5 New Requests <a href="#" title="">View all Requests</a></span>
                        <ul class="drops-menu">
                            <li>
                                <div>
                                    <figure>
                                        <img src="/images/resources/thumb-2.jpg" alt="">
                                    </figure>
                                    <div class="mesg-meta">
                                        <h6><a href="#" title="">Loren</a></h6>
                                        <span><b>Amy</b> is mutule friend</span>
                                        <i>yesterday</i>
                                    </div>
                                    <div class="add-del-friends">
                                        <a href="#" title=""><i class="fa fa-heart"></i></a>
                                        <a href="#" title=""><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <figure>
                                        <img src="/images/resources/thumb-3.jpg" alt="">
                                    </figure>
                                    <div class="mesg-meta">
                                        <h6><a href="#" title="">Tina Trump</a></h6>
                                        <span><b>Simson</b> is mutule friend</span>
                                        <i>2 days ago</i>
                                    </div>
                                    <div class="add-del-friends">
                                        <a href="#" title=""><i class="fa fa-heart"></i></a>
                                        <a href="#" title=""><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <figure>
                                        <img src="/images/resources/thumb-4.jpg" alt="">
                                    </figure>
                                    <div class="mesg-meta">
                                        <h6><a href="#" title="">Andrew</a></h6>
                                        <span><b>Bikra</b> is mutule friend</span>
                                        <i>4 hours ago</i>
                                    </div>
                                    <div class="add-del-friends">
                                        <a href="#" title=""><i class="fa fa-heart"></i></a>
                                        <a href="#" title=""><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <figure>
                                        <img src="/images/resources/thumb-5.jpg" alt="">
                                    </figure>
                                    <div class="mesg-meta">
                                        <h6><a href="#" title="">Dasha</a></h6>
                                        <span><b>Sarah</b> is mutule friend</span>
                                        <i>9 hours ago</i>
                                    </div>
                                    <div class="add-del-friends">
                                        <a href="#" title=""><i class="fa fa-heart"></i></a>
                                        <a href="#" title=""><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <figure>
                                        <img src="/images/resources/thumb-1.jpg" alt="">
                                    </figure>
                                    <div class="mesg-meta">
                                        <h6><a href="#" title="">Emily</a></h6>
                                        <span><b>Amy</b> is mutule friend</span>
                                        <i>4 hours ago</i>
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
                <li>
                    <a href="#" title="Notification" v-ripple>
                        <i class="fa fa-bell"></i><em class="bg-purple">7</em>
                    </a>
                    <div class="dropdowns">
                        <span>4 New Notifications <a href="#" title="">Mark all as read</a></span>
                        <ul class="drops-menu">
                            <li>
                                <a href="notifications.html" title="">
                                    <figure>
                                        <img src="/images/resources/thumb-1.jpg" alt="">
                                        <span class="status f-online"></span>
                                    </figure>
                                    <div class="mesg-meta">
                                        <h6>sarah Loren</h6>
                                        <span>commented on your new profile status</span>
                                        <i>2 min ago</i>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="notifications.html" title="">
                                    <figure>
                                        <img src="/images/resources/thumb-2.jpg" alt="">
                                        <span class="status f-online"></span>
                                    </figure>
                                    <div class="mesg-meta">
                                        <h6>Jhon doe</h6>
                                        <span>Nicholas Grissom just became friends. Write on his wall.</span>
                                        <i>4 hours ago</i>
                                        <figure>
                                            <span>Today is Marina Valentine’s Birthday! wish for celebrating</span>
                                            <img src="images/birthday.png" alt="">
                                        </figure>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="notifications.html" title="">
                                    <figure>
                                        <img src="/images/resources/thumb-3.jpg" alt="">
                                        <span class="status f-online"></span>
                                    </figure>
                                    <div class="mesg-meta">
                                        <h6>Andrew</h6>
                                        <span>commented on your photo.</span>
                                        <i>Sunday</i>
                                        <figure>
                                            <span>"Celebrity looks Beautiful in that outfit! We should see each"</span>
                                            <img src="/images/resources/admin.jpg" alt="">
                                        </figure>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="notifications.html" title="">
                                    <figure>
                                        <img src="/images/resources/thumb-4.jpg" alt="">
                                        <span class="status f-online"></span>
                                    </figure>
                                    <div class="mesg-meta">
                                        <h6>Tom cruse</h6>
                                        <span>nvited you to attend to his event Goo in</span>
                                        <i>May 19</i>
                                    </div>
                                </a>
                                <span class="tag">New</span>
                            </li>
                            <li>
                                <a href="notifications.html" title="">
                                    <figure>
                                        <img src="/images/resources/thumb-5.jpg" alt="">
                                        <span class="status f-online"></span>
                                    </figure>
                                    <div class="mesg-meta">
                                        <h6>Amy</h6>
                                        <span>Andrew Changed his profile picture. </span>
                                        <i>dec 18</i>
                                    </div>
                                </a>
                                <span class="tag">New</span>
                            </li>
                        </ul>
                        <a href="notifications.html" title="" class="more-mesg">View All</a>
                    </div>
                </li>
                <li>
                    <a href="#" title="Messages" v-ripple><i class="fa fa-commenting"></i><em class="bg-blue">9</em></a>
                    <div class="dropdowns">
                        <span>5 New Messages <a href="#" title="">Mark all as read</a></span>
                        <ul class="drops-menu">
                            <li>
                                <a class="show-mesg" href="#" title="">
                                    <figure>
                                        <img src="/images/resources/thumb-1.jpg" alt="">
                                        <span class="status f-online"></span>
                                    </figure>
                                    <div class="mesg-meta">
                                        <h6>sarah Loren</h6>
                                        <span><i class="ti-check"></i> Hi, how r u dear ...?</span>
                                        <i>2 min ago</i>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="show-mesg" href="#" title="">
                                    <figure>
                                        <img src="/images/resources/thumb-2.jpg" alt="">
                                        <span class="status f-offline"></span>
                                    </figure>
                                    <div class="mesg-meta">
                                        <h6>Jhon doe</h6>
                                        <span><i class="ti-check"></i> We’ll have to check that at the office and see if
                                            the client is on board with</span>
                                        <i>2 min ago</i>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="show-mesg" href="#" title="">
                                    <figure>
                                        <img src="/images/resources/thumb-3.jpg" alt="">
                                        <span class="status f-online"></span>
                                    </figure>
                                    <div class="mesg-meta">
                                        <h6>Andrew</h6>
                                        <span> <i class="fa fa-paperclip"></i>Hi Jack's! It’s Diana, I just wanted to
                                            let you know that we have to reschedule..</span>
                                        <i>2 min ago</i>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="show-mesg" href="#" title="">
                                    <figure>
                                        <img src="/images/resources/thumb-4.jpg" alt="">
                                        <span class="status f-offline"></span>
                                    </figure>
                                    <div class="mesg-meta">
                                        <h6>Tom cruse</h6>
                                        <span><i class="ti-check"></i> Great, I’ll see you tomorrow!.</span>
                                        <i>2 min ago</i>
                                    </div>
                                </a>
                                <span class="tag">New</span>
                            </li>
                            <li>
                                <a class="show-mesg" href="#" title="">
                                    <figure>
                                        <img src="/images/resources/thumb-5.jpg" alt="">
                                        <span class="status f-away"></span>
                                    </figure>
                                    <div class="mesg-meta">
                                        <h6>Amy</h6>
                                        <span><i class="fa fa-paperclip"></i> Sed ut perspiciatis unde omnis iste natus
                                            error sit </span>
                                        <i>2 min ago</i>
                                    </div>
                                </a>
                                <span class="tag">New</span>
                            </li>
                        </ul>
                        <a href="chat-messenger.html" title="" class="more-mesg">View All</a>
                    </div>
                </li>
            </ul>
            <div @click="showMenu = !showMenu" class="user-img">
                <h5>{{ authUser.user_name }}</h5>
                <img width="40" height="40" :src="authUser.avatar" alt="">
                <span class="status f-online"></span>
                <div class="user-setting" :class="{ 'active': showMenu }">
                    <ul class="log-out">
                        <li v-ripple><a href="#" title=""><i class="fas fa-user"></i>Trang cá nhân</a></li>
                        <li v-ripple><a href="#" title=""><i class="fas fa-pencil-alt"></i>Chỉnh sửa trang cá nhân</a>
                        </li>
                        <li v-ripple><a href="#" title=""><i class="fas fa-id-badge"></i>Đăng ký khuôn mặt</a></li>
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
    methods: {
        ...mapActions(["logout"]),
        toggleActive(index) {
            this.activeIndex = this.activeIndex === index ? null : index;
        },
        clickHome() {
            if (this.$route.path === '/') {
                this.isLoading = true;
                this.$store.commit('post/RESET_ALL_POSTS');
                this.$store.dispatch('post/fetchPosts')
                    .then(response => {
                        this.isLoading = false;
                    })
                    .catch(error => {
                        this.isLoading = false;
                        console.error('Error:', error);
                    });
            } else {
                console.log("Bạn không ở trang Home, không thực hiện tải lại bài viết.");
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