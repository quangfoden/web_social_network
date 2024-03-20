<template>

    <div v-if="!myProfile" class="spinner-border custom-loading text-primary z-1000" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <div v-if="myProfile" id="ProfileUser" class="container-fluid">
        <div class="row">
            <LeftProfile :postsByUsers="postsByUser" :inUser="user" id="LeftProfile" />
            <div id="Centerprofile" class="col-12 col-lg-8 col-xl-6 order-1 order-lg-2">
                <div>
                    <CreatePostBox v-if="isAuthUser" :image="authUser.avatar"
                        :placeholder="'Bạn đang nghĩ gì vậy ' + authUser.user_name" />
                    <div id="posts" v-for="post in postsByUser" :key="post.id">
                        <Post v-if="isUser(post)" :post="post" :user="post.user" :media="post.media"
                            :comments="post.comments" />
                    </div>
                    <!-- <div v-if="loading">Đang tải ...</div> -->
                    <div v-if="loading" class="spinner-border text-primary z-1000"
                        style="position: absolute;bottom:0;left: 50%;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <RightProfile id="RightProfile" />
        </div>
    </div>
</template>
<script>
import { mapState, mapActions, mapGetters } from 'vuex';

import CreatePostBox from '../Components/CreatePostBox.vue';
import Post from '../Components/Post.vue';
import LeftProfile from './LeftProfile.vue'
import { ref } from 'vue';
import RightProfile from './RightProfile.vue';
import { data } from 'jquery';
export default {
    data() {

        return {
            loading: false,
            userId: null,
            InUser: ref({}),
            myProfile: ref(false),
        }
    },
    components: {
        CreatePostBox,
        Post,
        LeftProfile,
        RightProfile
    },
    computed: {
        ...mapState('post', ['postsByUser']),
        ...mapState('post', ['user']),
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
        isAuthUser() {
            return this.userId == this.authUser.id;
        },
    },

    methods: {
        ...mapActions('post', ['fetchPostsByUser']),
        loadData() {
            this.$store.dispatch('post/fetchPostsByUser', this.userId)
        },
        isUser(post) {
            return post.user_id === this.userId;
        },
        handleScroll() {
            const scrollPosition = window.innerHeight + window.scrollY;
            const bodyHeight = document.body.offsetHeight;

            // Khoảng cách còn lại đến cuối trang
            const distanceToBottom = bodyHeight - scrollPosition;
            if (distanceToBottom < 100) {
                this.loading = true
                setTimeout(() => {
                    this.$store.dispatch('post/fetchPostsByUser', this.userId)
                        .then(() => {
                            this.loading = false
                        })
                }, 3000);
            }
            else {
                let scrollTop = window.scrollY;
                if (scrollTop === 0) {
                    return;
                }
            }
        },
        loadUserbyId() {
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
        }
    },

    created() {
        this.userId = parseInt(this.$route.params.id);
        this.resetData();
        this.loadData();
        this.loadUserbyId()
        console.log(this.postsByUser);
    },
    mounted() {
        window.addEventListener('scroll', this.handleScroll);
    },

}

</script>