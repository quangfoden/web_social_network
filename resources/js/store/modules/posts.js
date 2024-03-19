import axios from 'axios';
import Swal from 'sweetalert2';

const state = {
    posts: [],
    postsByUser: [],
    page: 1,
    page2: 1,
    loading: false
};
const mutations = {

    SET_LOADING(state, value) {
        state.loading = value;
    },
    INCREMENT_PAGE(state) {
        state.page++;
    },
    INCREMENT_PAGE2(state) {
        state.page2++;
    },
    allPosts(state, posts) {
        // state.posts = posts;
        state.posts.push(...posts)
    },
    allPostsByUser(state, posts) {
        state.postsByUser.push(...posts)
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
            state.posts[updatedPostIndex] = newData;
        }
    }
};
const actions = {
    fetchPosts({ commit }) {
        axios.get(`/api/user/allposts?page=${state.page}`)
            .then(({ data }) => {
                console.log(data);
                commit('allPosts', data.data.data);
                commit('INCREMENT_PAGE');
                commit('SET_LOADING', false);
            })
            .catch(error => {
                console.log("Error fetching posts:", error);
                commit('SET_LOADING', false);
            });
    },
    fetchPostsByUser({ commit }) {
        axios.get(`/api/user/allposts_byuser?page=${state.page2}`)
            .then(({ data }) => {
                console.log(data);
                commit('allPostsByUser', data.data.data);
                commit('INCREMENT_PAGE2');
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
        axios.post(`/api/user/post/${postId}/editPost`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
            .then(response => {
                if (response.status === 200 && response.data.data.success === true) {
                    setTimeout(() => {
                        commit('updatePost', { postId, newData: response.data.data.data });
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
                    title: ' bài viết cập nhật thành công',
                    text: `Error ${error}`,
                    showConfirmButton: false,
                    timer: 3000
                })
            });
    },
};


export default {
    namespaced: true,
    state,
    mutations,
    actions,
};