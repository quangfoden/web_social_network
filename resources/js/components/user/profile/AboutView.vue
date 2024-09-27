<template>
    <div class="col-lg-4 col-md-4">
        <aside class="sidebar">
            <div class="central-meta stick-widget">
                <span class="create-post">Thông tin cá nhân</span>
                <div class="personal-head">
                    <span class="f-title"><i class="fa fa-user"></i> Giới thiệu:</span>
                    <p>
                        {{ user.bio ? user.bio : 'Chưa cập nhật' }}
                    </p>
                    <span class="f-title"><i class="fa fa-birthday-cake"></i> Sinh nhật:</span>
                    <p>
                        {{ user.birthdate ? user.birthdate : 'Chưa cập nhật' }}
                    </p>
                    <span class="f-title"><i class="fa fa-phone"></i> Số điện thoại:</span>
                    <p>
                        {{ user.phone_number ? user.phone_number : 'Chưa cập nhật' }}
                    </p>
                    <span class="f-title"><i class="fa fa-male"></i> Giới tính:</span>
                    <p>
                        {{ user.gender ? user.gender : 'Chưa cập nhật' }}
                    </p>
                    <span class="f-title"><i class="fa fa-globe"></i> Địa chỉ:</span>
                    <p>
                        {{ user.address ? user.address : 'Chưa cập nhật' }}
                    </p>
                    <span class="f-title"><i class="fa fa-briefcase"></i> Nghề nghiệp:</span>
                    <p>
                        {{ user.occupation ? user.occupation : 'Chưa cập nhật' }}
                    </p>
                    <span class="f-title"><i class="fa fa-handshake-o"></i> Đã tham gia:</span>
                    <p>
                        {{ user.created_at ? formatDate(user.created_at) : 'Chưa cập nhật' }}
                    </p>
                </div>
            </div>
        </aside>
    </div>
    <div class="col-lg-8 col-md-8">
        <div class="central-meta">
            <span class="create-post">Bạn bè ({{ acceptedFriendsCount }}) <a href="#"
                    @click.prevent="showAllFriend = !showAllFriend" title="">
                    {{ showAllFriend ? 'Ẩn bớt' : 'Xem tất cả' }}
                </a></span>
            <ul style="flex-wrap: wrap;display: flex;" v-if="showAllFriend" class="frndz-list">
                <li style="text-align: center;  width: calc(20% - 10px);margin: 5px;" v-for="(friend, index) in friends"
                    :key="index">
                    <img width="100px" :src="friend.avatar" alt="friend avatar">
                    <div class="sugtd-frnd-meta">
                        <a href="#" title="">{{ friend.user_name }}</a>
                        <span>{{ friend.mutual_friends }} bạn chung</span>
                        <ul class="add-remove-frnd">
                            <li class="add-tofrndlist">
                                <a class="send-mesg" href="#" title="Nhắn tin">
                                    <i class="fa fa-commenting"></i>
                                </a>
                            </li>
                            <li class="add-tofrndlist">
                                <router-link :to="{ name: 'Profile User', params: { id: friend.user_id } }" class=""
                                    href="#" title="Xem trang cá nhân">
                                    <i class="fa fa-arrow-right"></i>
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <div v-else>
                <swiper :slides-per-view="4" :autoplay="{ delay: 2000, disableOnInteraction: false }"
                    :space-between="10" :modules="modules" :loop="true" class="mySwiper">
                    <swiper-slide style="text-align: center;" v-for="(friend, index) in friends" :key="index">
                        <img width="100px" :src="friend.avatar" alt="friend avatar">
                        <div class="sugtd-frnd-meta">
                            <a href="#" title="">{{ friend.user_name }}</a>
                            <span>{{ friend.mutual_friends }} mutual friends</span>
                            <ul class="add-remove-frnd">
                                <li class="add-tofrndlist">
                                    <a class="send-mesg" href="#" title="Send Message">
                                        <i class="fa fa-commenting"></i>
                                    </a>
                                </li>
                                <li class="add-tofrndlist">
                                    <router-link :to="{ name: 'Profile User', params: { id: friend.user_id } }" class=""
                                        href="#" title="Xem trang cá nhân">
                                        <i class="fa fa-arrow-right"></i>
                                    </router-link>
                                </li>
                            </ul>
                        </div>
                    </swiper-slide>
                </swiper>
            </div>
        </div><!-- friends list -->
        <div class="central-meta">
            <span class="create-post">Ảnh ({{ allImagesCount }})<a href="" @click.prevent="showAllImage = !showAllImage"
                    title="">{{
                        showAllImage ? 'Ẩn bớt' : 'Xem tất cả' }}</a></span>
            <div v-if="!showAllImage">
                <swiper :slides-per-view="4" :autoplay="{ delay: 2000, disableOnInteraction: false }"
                    :space-between="10" :modules="modules" :loop="true" class="mySwiper">
                    <swiper-slide v-for="image in allImages" :key="image.id">
                        <div class="item-box">
                            <a target="_blank" class="strip" :href="image.url" title="">
                                <img :src="image.url" alt="">
                            </a>
                        </div>
                    </swiper-slide>
                </swiper>
            </div>
            <div class="showAllImage" v-else>
                <span v-for="image in allImages" :key="image.id">
                    <a target="_blank" class="strip" :href="image.url" title="">
                        <img :src="image.url" alt="">
                    </a>
                </span>
            </div>
        </div>
        <div class="central-meta">
            <span class="create-post">Videos ({{ allIVideosCount }})<a href=""
                    @click.prevent="showAllVideo = !showAllVideo" title="">{{
                        showAllVideo ? 'Ẩn bớt' : 'Xem tất cả' }}</a></span>
            <div v-if="!showAllVideo">
                <swiper :slides-per-view="4" :autoplay="{ delay: 2000, disableOnInteraction: false }"
                    :space-between="10" :modules="modules" :loop="true" class="mySwiper">
                    <swiper-slide v-for="video in allVideos" :key="video.id">
                        <div class="video-box">
                            <a target="_blank" :href="video.url" title="">
                                <video :src="video.url" controls preload="metadata"></video>
                            </a>
                        </div>
                    </swiper-slide>
                </swiper>
            </div>
            <div class="showAllVideo" v-else>
                <span v-for="video in allVideos" :key="video.id">
                    <a target="_blank" :href="video.url" title="">
                        <video :src="video.url" controls preload="metadata"></video>
                    </a>
                </span>
            </div>
        </div>
    </div>
</template>

<style scoped>
.item-box {
    overflow: hidden;
}

.item-box img {
    width: 100%;
    height: auto;
}

.video-box {
    width: 100%;
    height: auto;
}

.video-box video {
    width: 100%;
    height: auto;
}

.showAllVideo {
    width: 100%;
    height: auto;
}

.showAllVideo video {
    width: 100%;
    height: auto;
}

.showAllImage,
.showAllVideo {
    display: flex;
    flex-wrap: wrap;
}

.showAllImage span,
.showAllVideo span {
    width: calc(50% - 10px);
    margin: 5px;
    box-sizing: border-box;
}
</style>
<script>
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';


// import required modules
import { Autoplay, Pagination, Navigation } from 'swiper/modules';

import dayjs from 'dayjs';
import { mapState, mapActions, mapGetters } from 'vuex';
export default {
    data() {
        return {
            userId: null,
            showAllImage: false,
            showAllVideo: false,
            showAllFriend: false,
            friends: []
        }
    },
    components: {
        Swiper,
        SwiperSlide
    },
    setup() {
        return {
            modules: [Autoplay, Pagination, Navigation],
        }
    },
    computed: {
        ...mapState('post', ['user']),
        ...mapState('post', ['postsInAboutProfile']),

        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
        // isAuthUser() {
        //     return this.userId == this.authUser.user_id;
        // },
        acceptedFriendsCount() {
            return this.friends.length;
        },
        allImagesCount() {
            return this.postsInAboutProfile.flatMap(post =>
                post.media.filter(item => item.type.startsWith('image'))
            ).length;
        },
        allIVideosCount() {
            return this.postsInAboutProfile.flatMap(post =>
                post.media.filter(item => item.type.startsWith('video'))
            ).length;
        },
        allImages() {
            return this.postsInAboutProfile.flatMap(post =>
                post.media.filter(item => item.type.startsWith('image'))
            );
        },
        allVideos() {
            return this.postsInAboutProfile.flatMap(post =>
                post.media.filter(item => item.type.startsWith('video'))
            );
        }
    },
    methods: {
        formatDate(date) {
            return dayjs(date).format('DD/MM/YYYY');
        },
        fetchAllPostAbout() {
            this.$store.dispatch('post/fetchPostsInAboutProfile', this.userId)
                .then(() => {
                    this.myProfile = false
                })
                .catch(error => {
                    console.error('Error:', error);
                })
        },
        getAllFriendById() {
            axios.get(`/api/user/friends/${this.userId}`)
                .then(response => {
                    this.friends = response.data;
                })
                .catch(error => {
                    console.error('Error fetching friends:', error);
                });

        },
    },

    watch: {
        '$route.params.id': {
            immediate: true,
            handler(newId, oldId) {
                this.userId = parseInt(newId)
                if (this.isFirstLoad || newId !== oldId) {
                    this.isFirstLoad = false;
                    this.getAllFriendById()
                    this.fetchAllPostAbout()
                }
            }
        }
    },
}
</script>