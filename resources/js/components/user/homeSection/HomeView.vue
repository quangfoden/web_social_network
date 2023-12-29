<template>
    <div id="PostsSection" class="">
        <CreatePostBox image="https://picsum.photos/id/120/300/320" placeholder="Bạn đang nghĩ gì vậy Quang?"  />
        <Post />
    </div>
</template>
<script>
import CreatePostBox from '../Components/CreatePostBox.vue'
import Post from '../Components/Post.vue'
export default {
    components: {
        CreatePostBox,
        Post
    }
}
</script>