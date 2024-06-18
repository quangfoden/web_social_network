<template>
    <div id="PostsSection" class="">
        <CreatePostBox :image="authUser.avatar" :placeholder="'Bạn đang nghĩ gì vậy ' + authUser.user_name" />
        <div v-if="!posts || posts.lenght < 1">
            <p>Không có bài viết nào</p>
        </div>
        <div v-else id="posts" v-for="post in posts " :key="post.id">
            <Post v-if="post.privacy === 'public' || post.privacy === 'friends'" :status="post.status" :post="post"
                :user="post.user" :media="post.media" :comments="post.comments" :comment_count="post.comment_count"
                @comment-created="handleCommentCreated(post.id)" />
        </div>
        <div v-if="isLoading" class="spinner-border custom-loading text-primary z-1000" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div v-if="loading">Đang tải ...</div>
    </div>
</template>
<script>
import { mapState, mapActions, mapGetters } from 'vuex';
import { ref } from 'vue';
import CreatePostBox from '../Components/CreatePostBox.vue'
import Post from '../Components/Post.vue'
export default {
    components: {
        CreatePostBox,
        Post
    },
    data() {
        return {
            loading: false,
        };
    },

    computed: {
        ...mapState('post', ['posts']),
        ...mapGetters('post', ['isLoading']),
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
    },
    methods: {
        ...mapActions('post', ['fetchPosts']),
        handleScroll() {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
                if (!this.loading) {
                    this.loading = true;
                    setTimeout(() => {
                        this.$store.dispatch('post/fetchPosts')
                            .then(() => {
                                this.loading = false;
                            })
                    }, 3000);
                }
            }
        },
        handleCommentCreated(postId) {
            const post = this.posts.find(p => p.id === postId);
            if (post) {
                post.comment_count += 1;
            }
        }

    },
    created() {
        this.fetchPosts();
    },
    mounted() {
        window.addEventListener('scroll', this.handleScroll);
    },
    destroyed() {
        window.removeEventListener('scroll', this.handleScroll);
    },
}
</script>