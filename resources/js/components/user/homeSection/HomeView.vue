<template>
    <div id="PostsSection" class="">
        <CreatePostBox :image="authUser.avatar" :placeholder="'Bạn đang nghĩ gì vậy ' + authUser.user_name" />
        <div v-for="post in posts" :key="post.id">
            <Post :post="post" :user="post.user" :media="post.media" />
        </div>
    </div>
</template>
<script>
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
            posts: ref([]),
        };
    },
    mounted() {
        this.fetchData();
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
        fetchData() {
            axios
                .get("/api/user/allposts")
                .then(response => {
                    if (Array.isArray(response.data.posts)) {
                        this.posts = response.data.posts;
                    } else {
                        console.error('Invalid data format:', response.data.posts);
                    }
                })
                .catch(error => {
                    console.log("Error fetching posts:", error);
                });
        }
    }
}
</script>