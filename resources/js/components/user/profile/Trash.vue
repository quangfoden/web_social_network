<template>
    <div class="col-12 col-lg-8 col-xl-6 order-1 order-lg-2">
        <div v-show="postsDeleted.length === 0">
            <p class="primary-text fs-4">Không có bài viết nào</p>
        </div>
        <div id="posts" v-for="post in postsDeleted" :key="post.id">
            <Post :post="post" :status="post.status" :user="post.user" :media="post.media"
                :comments="post.comments" />
        </div>
    </div>
</template>
<script>
import { mapState, mapActions, mapGetters } from 'vuex';
import Post from '../Components/Post.vue';
export default {
    components: {
        Post
    },
    computed: {
        ...mapState('post', ['postsDeleted']),
    },
    methods: {
        loadData() {
            this.$store.dispatch('post/fetchPostsDeleted')
        },
        handleScroll() {
            const scrollPosition = window.innerHeight + window.scrollY;
            const bodyHeight = document.body.offsetHeight;

            // Khoảng cách còn lại đến cuối trang
            const distanceToBottom = bodyHeight - scrollPosition;
            if (distanceToBottom < 100) {
                this.loading = true
                setTimeout(() => {
                    this.$store.dispatch('post/fetchPostsDeleted')
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
        resetData() {
            this.$store.commit('post/RESET_POSTS_BY_USER_DELETED');
        }
    },

    created() {
        this.resetData();
        this.loadData();
    },
    mounted() {
        window.addEventListener('scroll', this.handleScroll);
    },
    destroyed() {
        window.removeEventListener('scroll', this.handleScroll);
        this.resetData();
    }
}
</script>