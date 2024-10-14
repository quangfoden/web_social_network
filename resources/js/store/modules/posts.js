import axios from 'axios';
import Swal from 'sweetalert2';


const state = {
    user: [],
    posts: [],
    postsByUser: [],
    postsInAboutProfile: [],
    postsDeleted: [],
    page: 1,
    page2: 1,
    page3: 1,
    loading: false
};
const getters = {
    getUserStatus: state => state.user.status,
    isLoading: state => state.loading
};

const mutations = {
    RESET_ALL_POSTS(state) {
        state.posts = [];
        state.page = 1;
    },
    RESET_POSTS_BY_USER(state) {
        state.postsByUser = [];
        state.page2 = 1;
    },
    RESET_POSTS_BY_USER_DELETED(state) {
        state.postsDeleted = [];
        state.page3 = 1;
    },
    SET_USER(state, user) {
        state.user = user;
    },
    SET_LOADING(state, value) {
        state.loading = value;
    },
    INCREMENT_PAGE(state) {
        state.page++;
    },
    INCREMENT_PAGE2(state) {
        state.page2++;
    },
    INCREMENT_PAGE3(state) {
        state.page3++;
    },
    SET_INCREMENT_PAGE(state) {
        state.page = 1;
    },
    SET_INCREMENT_PAGE2(state) {
        state.page2 = 1;
    },
    allPosts(state, posts) {
        state.posts.push(...posts) 
    },
    allPostsByUser(state, posts) {
        state.postsByUser.push(...posts)
    },
    allpostsInAboutProfile(state, posts) {
        state.postsInAboutProfile = posts
    },
    allPostsDeleted(state, posts) {
        state.postsDeleted.push(...posts)
    },
    setPosts(state, newData) {
        state.posts.unshift(newData);
    },
    setPostsbyUser(state, newData) {
        state.postsByUser.unshift(newData);
    },
    updatePost(state, { postId, newData }) {
        const updatedPostIndex = state.posts.findIndex(post => post.id === postId);
        if (updatedPostIndex !== -1) {
            console.log(updatedPostIndex);
            
            state.posts[updatedPostIndex] = newData;
        }
    },
    updatePost2(state, { postId, newData }) {
        const updatedPostIndex = state.postsByUser.findIndex(post => post.id === postId);
        if (updatedPostIndex !== -1) {
            state.postsByUser[updatedPostIndex] = newData;
        }
    },
    MOVE_TO_TRASH(state, postId) {
        const index = state.posts.findIndex(post => post.id === postId);
        if (index !== -1) {
            state.posts.splice(index, 1);
        }

    },
    MOVE_TO_TRASH2(state, postId) {
        const index = state.postsByUser.findIndex(post => post.id === postId);
        if (index !== -1) {
            state.postsByUser.splice(index, 1);
        }

    },
    MOVE_TO_TRASH3(state, postId) {
        const index = state.postsDeleted.findIndex(post => post.id === postId);
        if (index !== -1) {
            state.postsDeleted[index].status = !state.postsDeleted[index].status;
            if (state.postsDeleted[index].status) {
                state.postsDeleted.splice(index, 1)[0];
            } else {
                const statusPost = state.postsDeleted.splice(index, 1)[0];
                state.postsDeleted.push(statusPost);
            }
        }

    },
    TOGGLE_PIN(state, postId) {
        const index = state.postsByUser.findIndex(post => post.id === postId);
        if (index !== -1) {
            const post = state.postsByUser[index];
            post.pinned = !post.pinned;
            if (post.pinned) {
                if (post.originalIndex === undefined) {
                    post.originalIndex = index;
                }
                const pinnedPost = state.postsByUser.splice(index, 1)[0];
                state.postsByUser.unshift(pinnedPost);
            } else {
                const originalIndex = post.originalIndex;
                if (originalIndex !== undefined) {
                    const unpinnedPost = state.postsByUser.splice(index, 1)[0];
                    state.postsByUser.splice(originalIndex, 0, unpinnedPost);
                }
            }
        }
    }


};
const actions = {
    setStateToOne({ commit }) {
        commit('SET_INCREMENT_PAGE');
    },
    setStateToOne2({ commit }) {
        commit('SET_INCREMENT_PAGE2');
    },
    getUserbyId({ commit }, id) {
        return axios.get(`/api/user/getUserById/${id}`)
            .then(response => {
                commit('SET_USER', response.data);
            })
            .catch(error => {
                console.log("Error fetching getUserbyId:", error);

            });
    },
    fetchPosts({ commit }) {
        commit('SET_LOADING', true);
        axios.get(`/api/user/allposts?page=${state.page}`)
            .then(({ data }) => {
                commit('allPosts', data.data.data);
                commit('INCREMENT_PAGE');
                commit('SET_LOADING', false);
            })
            .catch(error => {
                console.log("Error fetching posts:", error);
                commit('SET_LOADING', false);
            });
    },
    // fetchPosts({ commit, state }) {
    //     commit('SET_LOADING', true);
    //     axios.get(`/api/user/allposts?page=${state.page}`)
    //         .then(({ data }) => {
    //             const allPosts = [];
    //             console.log(data.data.data)
    //             data.data.data.forEach(post => {
    //                 // Thêm bài viết gốc
    //                 allPosts.push({ ...post, type: 'post' });

    //                 // Thêm các bài chia sẻ
    //                 if (post.shares.length > 0) {
    //                     post.shares.forEach(share => {
    //                         allPosts.push({
    //                             id: share.id,
    //                             user_id: share.user_id,
    //                             user_share: share.user,
    //                             content: post.content, // Nội dung từ bài viết gốc
    //                             user: post.user, // Thông tin người dùng từ bài viết gốc
    //                             privacy_share: share.privacy,
    //                             privacy: post.privacy,
    //                             created_at: share.created_at,
    //                             created_at_formatted: post.created_at_formatted,
    //                             media:post.media,
    //                             pinned:post.pinned,
    //                             type: 'share' // Đánh dấu loại là 'share'
    //                         });
    //                     });
    //                 }
    //             });

    //             // Sắp xếp theo thời gian
    //             allPosts.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

    //             // Commit các bài viết đã được xử lý
    //             commit('allPosts', allPosts);
    //             console.log(allPosts);

    //             commit('INCREMENT_PAGE');
    //             commit('SET_LOADING', false);
    //         })
    //         .catch(error => {
    //             console.log("Error fetching posts:", error);
    //             commit('SET_LOADING', false);
    //         });
    // },

    // fetchPostsByUser({ commit }, userId) {
    //     axios.get(`/api/user/post/${userId}/allposts_byuser/?page=${state.page2}`)
    //         .then(({ data }) => {
    //             const allPosts = [];
    //             data.data.data.forEach(post => {
    //                 // Thêm bài viết gốc
    //                 allPosts.push({ ...post, type: 'post' });

    //                 // Thêm các bài chia sẻ
    //                 if (post.shares.length > 0) {
    //                     post.shares.forEach(share => {
    //                         allPosts.push({
    //                             id: share.id,
    //                             user_id: share.user_id,
    //                             user_share: share.user,
    //                             content: post.content, // Nội dung từ bài viết gốc
    //                             user: post.user, // Thông tin người dùng từ bài viết gốc
    //                             privacy_share: share.privacy,
    //                             privacy: post.privacy,
    //                             created_at: share.created_at,
    //                             created_at_formatted: post.created_at_formatted,
    //                             media:post.media,
    //                             pinned:post.pinned,
    //                             type: 'share' // Đánh dấu loại là 'share'
    //                         });
    //                     });
    //                 }
    //             });
    //             allPosts.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    //             commit('allPostsByUser', allPosts);
    //             commit('INCREMENT_PAGE2');
    //         })
    //         .catch(error => {
    //             console.log("Error fetching posts:", error);
    //         });
    // },
    fetchPostsByUser({ commit }, userId) {
        axios.get(`/api/user/post/${userId}/allposts_byuser/?page=${state.page2}`)
            .then(({ data }) => {
                commit('allPostsByUser', data.data.data);
                commit('INCREMENT_PAGE2');
            })
            .catch(error => {
                console.log("Error fetching posts:", error);
            });
    },
    fetchPostsInAboutProfile({ commit }, userId) {
        commit('SET_LOADING', true);
        axios.get(`/api/user/post/${userId}/all_posts_about_profile`)
            .then(({ data }) => {
                commit('allpostsInAboutProfile', data.data);
                commit('SET_LOADING', false);
            })
            .catch(error => {
                console.log("Error fetching posts:", error);
                commit('SET_LOADING', false);
            });
    },
    fetchPostsDeleted({ commit }) {
        axios.get(`/api/user/post/allposts_deleted/?page=${state.page3}`)
            .then(({ data }) => {
                commit('allPostsDeleted', data.data.data);
                commit('INCREMENT_PAGE3');
                console.log(state.postsDeleted);
            })
            .catch(error => {
                console.log("Error fetching posts:", error);
            });
    },

     addNewPost({ commit }, formData) {
        return axios.post('/api/user/create-post', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
            .then(response => {
                if (response.status === 200 && response.data.data.success === true) {
                    const newData = response.data.data.data
                    const newData2 = response.data.data.data
                    console.log("Trước khi commit:", state.posts, state.postsByUser);
                    commit('setPosts', newData);
                    commit('setPostsbyUser', newData2);
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Bài viết được thêm thành công",
                        showConfirmButton: false,
                        timer: 3000,
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Đăng bài viết không thành công',
                        text: `Error`,
                        showConfirmButton: false,
                        timer: 3000
                    })
                }
            })
            .catch(error => {
                if (error.response && error.response.status === 422) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tạo bài viết không thành công',
                        text: `Lỗi: ${error.response.data.message}`,
                        showConfirmButton: false,
                        timer: 3000
                    })
                } else {
                    console.error(error);
                }
            });
    },
     editPost({ commit }, payload) {
        const { postId, formData } = payload;
        return  axios.post(`/api/user/post/${postId}/editPost`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
            .then(response => {
                if (response.status === 200 && response.data.data.success === true) {
                    setTimeout(() => {
                        commit('updatePost', { postId, newData: response.data.data.data });
                        commit('updatePost2', { postId, newData: response.data.data.data });
                    }, 2000);
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Bài viết được cập nhật thành công thành công",
                        showConfirmButton: false,
                        timer: 3000,
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Bài viết cập nhật không thành công',
                        text: `Error`,
                        showConfirmButton: false,
                        timer: 3000
                    })
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: ' bài viết cập nhật không thành công thành công',
                    showConfirmButton: false,
                    timer: 3000
                })
            });
    },
    trashPost({ commit }, postId) {
        return new Promise((resolve, reject) => {
            axios.put(`/api/user/post/${postId}/trash`)
                .then(response => {
                    console.log(response.data.message);
                    commit('MOVE_TO_TRASH', postId);
                    commit('MOVE_TO_TRASH2', postId);
                    commit('MOVE_TO_TRASH3', postId);
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: `${response.data.message}`,
                        showConfirmButton: false,
                        timer: 3000,
                    });
                    resolve(response.data.message);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    togglePin({ commit }, postId) {
        axios.put(`/api/user/post/${postId}/pin`)
            .then(response => {
                commit('TOGGLE_PIN', postId);
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: `${response.data.message}`,
                    showConfirmButton: false,
                    timer: 3000,
                });
            })
            .catch(error => {
                console.error("Error toggling pin:", error);
            });
    },

    createComment({ commit }, formData) {
        return axios.post('api/user/create_comment', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
    },
    editComment({ commit }, payload) {
        const { commentId, formData } = payload;
        return axios.post(`api/user/comments/${commentId}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
    },
    delete_comment({ commit }, commentId) {
        return axios.post(`api/user/deleteComment/${commentId}`)
    },
    createRepComment({ commit }, payload) {
        const { commentId, formData } = payload;
        return axios.post(`api/user/create_rep_comment/${commentId}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
    },
    editRepComment({ commit }, payload) {
        const { repCommentId, formData } = payload;
        return axios.post(`api/user/comments/repcomment/${repCommentId}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
    },
    delete_repcomment({ commit }, repcommentId) {
        return axios.post(`api/user/deleterepcomment/repcomment/${repcommentId}`)
    },
};


export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
};