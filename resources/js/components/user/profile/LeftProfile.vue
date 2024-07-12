<template>
    <div class="col-12 col-lg-4 col-xl-3 order-2 order-lg-1">
        <div class="card mb-3 mt-3">
            <div id="CoverImage">
                <img src="../../../../assets/images/bgaccount2.jpg" alt="">
            </div>
            <div class="card-body border-bottom-cus text-center" style="margin-top: -100px;">
                <img :src="authUser.avatar" alt="Jassa Jas" class="img-fluid rounded-circle mb-2" width="128"
                    height="128">
                <h4 class="card-title mb-2">{{ inUser.user_name }}</h4>
                <div v-if="!isAuthUser" class="dropdown show">
                    <button v-if="loading" class="btn btn-primary btn-sm" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        <span role="status">Loading...</span>
                    </button>
                    <a v-else-if="isRequestSent" @click="cancelFriendship(inUser.id)" class="btn btn-primary btn-sm">Đã
                        gửi lời mời kết bạn
                    </a>
                    <a v-else-if="isRequestReceived" data-bs-toggle="dropdown" data-bs-display="static"
                        class="btn btn-primary btn-sm">Phản hồi
                        <div class="dropdown-menu dropdown-menu-right">
                            <a @click="acceptRequest(inUser.id)" class="dropdown-item">Chấp nhận</a>
                            <a @click="declineRequest(inUser.id)" class="dropdown-item">Từ chối</a>
                        </div>
                    </a>
                    <a v-else-if="isFriend" data-bs-toggle="dropdown" data-bs-display="static"
                        class="btn btn-primary btn-sm">Bạn bè
                        <div class="dropdown-menu dropdown-menu-right">
                            <a @click="cancelFriendship(inUser.id)" class="dropdown-item">Huỷ kết bạn</a>
                        </div>
                    </a>
                    <a v-else @click.prevent="sendRequest(inUser.id)" class="btn btn-primary btn-sm">Thêm bạn
                        bè</a>
                    <a class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-message mx-1"></i>Nhắn tin</a>
                </div>
                <div v-if="isAuthUser">
                    <a class="btn btn-primary btn-sm"><i class="fa-solid fa-plus mx-1"></i>Thêm vào tin</a>
                    <a style="" class="btn btn-secondary btn-sm dropdown show" data-bs-toggle="dropdown"
                        data-bs-display="static">
                        <i class="fa-solid fa-pen-to-square mx-1"></i>xem thêm</a>
                    <div class="dropdown-menu position-absolute dropdown-menu-right">
                        <!-- <a class="dropdown-item" href="#">Tuỳ chỉnh</a>
                        <a class="dropdown-item" href="#">Thêm tiểu sử</a> -->
                        <router-link class="dropdown-item"
                            :to="{ name: 'Repository User', params: { id: authUser.user_id } }">Kho lưu
                            trữ</router-link>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header border-bottom-cus">
                <div class="card-actions float-right">
                    <div class="dropdown show">
                        <a href="#" data-bs-toggle="dropdown" data-bs-display="static">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="19" cy="12" r="1"></circle>
                                <circle cx="5" cy="12" r="1"></circle>
                            </svg>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Tuỳ chỉnh</a>
                            <a class="dropdown-item" href="#">Thêm tiểu sử</a>
                            <a class="dropdown-item" href="#">Chỉnh sửa thông tin cá nhân</a>
                        </div>
                    </div>
                </div>
                <h5 class="card-title mb-0">Giới thiệu</h5>
            </div>
            <div class="card-body border-bottom-cus">
                <ul class="list-unstyled mb-0">
                    <li class="mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-home feather-sm mr-1">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg> Sống tại huế
                    </li>
                    <li class="mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-briefcase feather-sm mr-1">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg> Đang học tại DH Pxu
                    </li>
                    <li class="mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-map-pin feather-sm mr-1">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg> Huế
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header border-bottom-cus">
                <div class="card-actions float-right">
                    <div class="dropdown show">
                        <a href="#" data-bs-toggle="dropdown" data-display="static">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="19" cy="12" r="1"></circle>
                                <circle cx="5" cy="12" r="1"></circle>
                            </svg>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Hành Động</a>
                            <a class="dropdown-item" href="#">...</a>
                            <a class="dropdown-item" href="#">More..</a>
                        </div>
                    </div>
                </div>
                <h5 class="card-title mb-0">Ảnh</h5>
            </div>
            <div class="card-body border-bottom-cus d-flex flex-wrap align-items-center">
                <span class="" v-for="postsByUser in postsByUsers">
                    <span v-if="postsByUser.media" v-for="_media in postsByUser.media">
                        <span v-if="_media.type === 'image/gif' || _media.type === 'image/jpeg'"
                            class="media text-center">
                            <img :src="_media.url" width="60" height="60" class="border border-2 rounded-md mr-2"
                                alt="Andrew Jones">
                        </span>
                    </span>
                </span>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                <div class="card-actions float-right">
                    <div class="dropdown show">
                        <a href="#" data-bs-toggle="dropdown" data-display="static">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="19" cy="12" r="1"></circle>
                                <circle cx="5" cy="12" r="1"></circle>
                            </svg>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Hành Động</a>
                            <a class="dropdown-item" href="#">...</a>
                            <a class="dropdown-item" href="#">More..</a>
                        </div>
                    </div>
                </div>
                <h5 class="card-title mb-0">Bạn bè</h5>
            </div>
            <div class="card-body d-flex align-item-center flex-wrap gap-2">
                <div class="media text-center">
                    <img src="https://therichpost.com/wp-content/uploads/2021/03/avatar3.png" width="56" height="56"
                        class="rounded-circle mr-2 mb-2" alt="Andrew Jones">
                    <div class="media-body">
                        <!-- <p class="my-1"><strong>Nguyen Van Quang</strong></p> -->
                        <a class="btn btn-sm btn-outline-primary" href="#">Xem thêm</a>
                    </div>
                </div>

                <hr class="my-2">

                <div class="media text-center">
                    <img src="https://therichpost.com/wp-content/uploads/2021/03/avatar3.png" width="56" height="56"
                        class="rounded-circle mr-2 mb-2" alt="John Smit">
                    <div class="media-body">
                        <!-- <p class="my-1"><strong>Quốc Lê</strong></p> -->
                        <a class="btn btn-sm btn-outline-primary" href="#">Xem thêm</a>
                    </div>
                </div>

                <hr class="my-2">
            </div>
        </div>
    </div>
</template>
<script>
import { param } from 'jquery';
import { mapState, mapActions, mapGetters } from 'vuex';

export default {
    data() {
        return {

        }
    },
    computed: {
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
        isAuthUser() {
            return this.inUser.id == this.authUser.id;
        },
        isRequestSent() {
            return this.sentRequests.some(request => request.receiver_id === this.inUser.id);
        },
        isRequestReceived() {
            return this.receivedRequests.some(request => request.sender_id === this.inUser.id);
        },
        isFriend() {
            return this.friends.some(friend => friend.friend_id === this.inUser.id);

        },
        ...mapState('friends', {
            receivedRequests: state => state.receivedRequests,
            sentRequests: state => state.sentRequests,
            friends: state => state.friends,
            loading: state => state.loading
        }),
    },
    props: {
        postsByUsers: {
            type: Object,
            required: true,
        },
        inUser: {
            type: Object,
            required: true,
        },
    },
    methods: {
        ...mapActions('friends', ['sendFriendRequest']),
        ...mapActions('friends', ['getFriends']),
        ...mapActions('friends', ['getFriendRequests']),
        ...mapActions('friends', ['acceptRequests']),
        ...mapActions('friends', ['declineRequests']),
        ...mapActions('friends', ['cancelFriendships']),
        sendRequest(receiverId) {
            this.sendFriendRequest(receiverId);
        },
        getFriend() {
            this.getFriends(this.authUser.id)
        },
        getFriendRequest() {
            this.getFriendRequests(this.authUser.id)
        },
        acceptRequest(id) {
            this.acceptRequests(id)
        },
        declineRequest(id) {
            this.declineRequests(id)
        },
        cancelFriendship(id) {
            this.cancelFriendships(id)
        }
    },
    mounted() {
        this.getFriendRequest()
        this.getFriend()
    }
}
</script>