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
                    <div v-show="postsByUser.length === 0">
                        <p class="primary-text fs-4">Không có bài viết nào</p>
                    </div>
                    <div id="posts" v-for="post in postsByUser" :key="post.id">
                        <Post v-if="checkPrivacy(post) && isProfile(post.user.user_id)" :post="post"
                            :pinned="post.pinned" :status="post.status" :user="post.user" :media="post.media"
                            :comments="post.comments" :comment_count="post.comment_count"
                            @comment-created="handleCommentCreated(post.id)" />
                    </div>
                    <div v-if="loading" class="spinner-border text-primary z-1000"
                        style="position: absolute;bottom:0;left: 50%;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <RightProfile v-if="isAuthUser" id="RightProfile" />
            <SidebarRight v-else class="col-12 col-lg-12 col-xl-3 order-3 order-lg-3 bg-item" />
        </div>
    </div>
</template>
<script>
import { mapState, mapActions, mapGetters } from 'vuex';

import Post from '../Components/Post.vue';
import CreatePostBox from '../Components/CreatePostBox.vue';
import LeftProfile from './LeftProfile.vue'
import RightProfile from './RightProfile.vue';
import SidebarRight from '../layouts/SidebarRight.vue';
import { ref } from 'vue';
import { data } from 'jquery';
export default {
    data() {
        return {
            loading: false,
            userId: null,
            InUser: ref({}),
            myProfile: ref(false),
            isFirstLoad: true
        }
    },
    components: {
        CreatePostBox,
        Post,
        LeftProfile,
        RightProfile,
        SidebarRight
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
            return this.userId == this.authUser.user_id;
        },
    },

    methods: {
        ...mapActions('post', ['fetchPostsByUser']),
        loadData() {
            this.myProfile = false
            this.$store.dispatch('post/fetchPostsByUser', this.userId)
                .then(() => {
                    this.myProfile = false
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        },
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
        handleScroll() {
            const scrollPosition = window.innerHeight + window.scrollY;
            const bodyHeight = document.body.offsetHeight;

            // Khoảng cách còn lại đến cuối trang
            const distanceToBottom = bodyHeight - scrollPosition;
            if (distanceToBottom < 100) {
                if (!this.loading) {
                    this.loading = true
                    setTimeout(() => {
                        this.$store.dispatch('post/fetchPostsByUser', this.userId)
                            .then(() => {
                                this.loading = false
                            })
                    }, 3000);
                }

            }
            else {
                let scrollTop = window.scrollY;
                if (scrollTop === 0) {
                    return;
                }
            }
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
        loadUserProfile() {
            this.resetData();
            this.loadData();
            this.loadUserbyId();
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
                }
            }
        }
    },
    mounted() {
        window.addEventListener('scroll', this.handleScroll);
    },
    destroyed() {
        window.removeEventListener('scroll', this.handleScroll);
    }

}

</script>