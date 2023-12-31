<template>
    <div id="PostsSection" class="">
        <CreatePostBox image="https://picsum.photos/id/120/300/320" placeholder="Bạn đang nghĩ gì vậy Quang?" />
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