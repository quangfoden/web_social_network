<template>
    <div id="ProfileUser" class="container-fluid">
        <div class="row">
            <LeftProfile :postsByUsers="postsByUser" id="LeftProfile" />
            <div id="Centerprofile" class="col-12 col-lg-8 col-xl-6 order-1 order-lg-2">
                <div>
                    <CreatePostBox :image="authUser.avatar"
                        :placeholder="'Bạn đang nghĩ gì vậy ' + authUser.user_name" />
                    <div id="posts" v-for="post in postsByUser" :key="post.id">
                        <Post :post="post" :user="post.user" :media="post.media" :comments="post.comments" />
                    </div>
                    <div v-if="loading">Đang tải ...</div>
                </div>
            </div>
            <RightProfile id="RightProfile" />
        </div>
    </div>
</template>
<script>
import { mapState, mapActions } from 'vuex';
import CreatePostBox from '../Components/CreatePostBox.vue';
import Post from '../Components/Post.vue';
import LeftProfile from './LeftProfile.vue'
import { ref } from 'vue';
import RightProfile from './RightProfile.vue';
export default {
    data() {
        return {
            loading: false,
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
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
    },
    mounted() {
        // this.$store.dispatch('post/fetchPosts');
        // console.log(this.posts);
    },
    methods: {
        ...mapActions('post', ['fetchPostsByUser']),
        loadData() {
            this.fetchPostsByUser().then(() => {
                console.log(this.postsByUser);
            });
        },
        isUser(post) {
            return post.user_id === this.authUser.id;
        },
        handleScroll() {
            const scrollPosition = window.innerHeight + window.scrollY;
            const bodyHeight = document.body.offsetHeight;

            // Khoảng cách còn lại đến cuối trang
            const distanceToBottom = bodyHeight - scrollPosition;
            if (distanceToBottom < 100) {
                this.loading = true
                setTimeout(() => {
                    this.$store.dispatch('post/fetchPostsByUser')
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
    created() {
        this.loadData();
    },
    mounted() {
        window.addEventListener('scroll', this.handleScroll);
    },
    destroyed() {
        window.removeEventListener('scroll', this.handleScroll);
    },
}

</script>