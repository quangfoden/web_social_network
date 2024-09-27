<template>
	<section>
		<div class="gap2 gray-bg">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="row merged20" id="page-contents">
							<div class="col-lg-6 m-auto">
								<CreatePostBox :image="authUser.avatar"
									:placeholder="authUser.user_name + ' ơi, Bạn đang nghĩ gì thế?'" />
								<StoriesView :image="authUser.avatar" />
								<div v-if="!posts || posts.length < 1">
									<p>Không có bài viết nào</p>
								</div>

								<div v-else id="posts" v-for="post in posts " :key="post.id">
									<Post v-if="post.privacy === 'public' || post.privacy === 'friends'"
										:status="post.status" :post="post" :user="post.user" :media="post.media"
										:comments="post.comments" :comment_count="post.comment_count"
										:likes="post.likes" :like_count="post.like_count"
										@comment-created="handleCommentCreated(post.id)"
										@comment-deleted="handleCommentdeleted(post.id)"
										@comment_overlay-created="handleCommentCreated(post.id)"
										@comment_overlay-deleted="handleCommentdeleted(post.id)"
										@updated_like="handleUpdatedLike(post.id)"
										@deleted_like="handleLikedeleted(post.id)"
										@updated-like-overlay="handleUpdatedLike(post.id)"
										@deleted-like-overlay="handleLikedeleted(post.id)" />
								</div>
								<button @click="loadMorePost" class="btn-view btn-load-more">Load More</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</template>
<script>
import { debounce } from 'lodash';
import { mapState, mapActions, mapGetters } from 'vuex';
import { ref } from 'vue';
import CreatePostBox from '../Components/CreatePostBox.vue'
import StoriesView from '../Components/StoriesView.vue'
import Post from '../Components/Post.vue'
export default {
	components: {
		CreatePostBox,
		StoriesView,
		Post,
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
		loadMorePost: debounce(function () {
			if (this.loading) return;
			this.loading = true;
			this.$store.dispatch('post/fetchPosts')
				.then(() => {
					this.loading = false;
				})
				.catch(error => {
					console.error("Error fetching posts:", error);
					this.loading = false;
				});
		}, 1000),

		handleCommentCreated(postId) {
			const post = this.posts.find(p => p.id === postId);
			if (post) {
				post.comment_count += 1;
			}
		},
		handleCommentdeleted(postId) {
			const post = this.posts.find(p => p.id === postId);
			if (post) {
				post.comment_count -= 1;
			}
		},
		handleUpdatedLike(postId) {
			const post = this.posts.find(p => p.id === postId);
			if (post) {
				post.like_count += 1;
			}
		},
		handleLikedeleted(postId) {
			const post = this.posts.find(p => p.id === postId);
			if (post) {
				post.like_count -= 1;
			}
		},

	},
	created() {
		this.fetchPosts();
	},
}
</script>