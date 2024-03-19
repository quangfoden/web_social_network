<template>
    <div id="PostsSection" class="">
        <CreatePostBox :image="authUser.avatar" :placeholder="'Bạn đang nghĩ gì vậy ' + authUser.user_name" />
        <div id="posts" v-for="post in  posts " :key="post.id">
            <Post v-if="post.privacy === 'public' || post.privacy === 'friends'" :post="post" :user="post.user"
                :media="post.media" :comments="post.comments" />
        </div>
        <div v-if="loading">Đang tải ...</div>
    </div>
</template>
<script>
import { mapState, mapActions } from 'vuex';
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
                this.loading = true
                setTimeout(() => {
                    this.$store.dispatch('post/fetchPosts')
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
        }
    },
    mounted() {
        this.fetchPosts();
        window.addEventListener('scroll', this.handleScroll);
    },
    destroyed() {
        window.removeEventListener('scroll', this.handleScroll);
    },
}
</script>