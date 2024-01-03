<template>
    <div id="PostsSection" class="">
        <CreatePostBox :image="authUser.avatar" :placeholder="'Bạn đang nghĩ gì vậy ' + authUser.user_name" />
        <div v-for="post in posts" :key="post.id">
            <Post :post="post" :user="post.user" :media="post.media" />
        </div>
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
         
        };
    },
    mounted() {
        this.fetchData();
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
        fetchData() {
            this.fetchPosts();
        }
    }
}
</script>