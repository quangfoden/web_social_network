<template>
    <div class="col-lg-6 m-auto">
        <CreatePostBox v-if="isAuthUser" :image="authUser.avatar"
            :placeholder="authUser.user_name + ' ơi, Bạn đang nghĩ gì thế?'" />
        <div id="posts" v-for="post in postsByUser " :key="post.id">
            <Post v-if="checkPrivacy(post) && isProfile(post.user.user_id)" :post="post" :pinned="post.pinned"
                :status="post.status" :user="post.user" :media="post.media" :comments="post.comments"
                :comment_count="post.comment_count" :likes="post.likes" :like_count="post.like_count"
                @comment-created="handleCommentCreated(post.id)" @comment-deleted="handleCommentdeleted(post.id)"
                @comment_overlay-created="handleCommentCreated(post.id)"
                @comment_overlay-deleted="handleCommentdeleted(post.id)" @updated_like="handleUpdatedLike(post.id)"
                @deleted_like="handleLikedeleted(post.id)" @updated-like-overlay="handleUpdatedLike(post.id)"
                @deleted-like-overlay="handleLikedeleted(post.id)" />
        </div>
        <button @click="loadMorePostByUser" class="btn-view btn-load-more">Load More</button>
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
import { debounce } from 'lodash';
export default {
    data() {
        return {
            loading: false,
            userId: null,
            InUser: ref({}),
            myProfile: ref(false),
            isFirstLoad: true,
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
        loadMorePostByUser: debounce(function () {
            if (this.loading) return;
            this.loading = true;
            this.$store.dispatch('post/fetchPostsByUser', this.userId)
                .then(() => {
                    this.loading = false
                })
                .catch(error => {
					console.error("Error fetching posts:", error);
					this.loading = false;
				});
        }, 1000),
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
            this.loadData();
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
}

</script>