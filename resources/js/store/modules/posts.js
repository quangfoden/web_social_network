import axios from 'axios';
import Swal from 'sweetalert2';

const state = {
    posts: [],
};
const mutations = {
    allPosts(state, allPosts) {
        state.posts = allPosts;
    },
    setPosts(state, newData) {
        state.posts.unshift(newData);
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
        axios.get("/api/user/allposts")
            .then(response => {
                if (Array.isArray(response.data.data.posts)) {
                    commit('allPosts', response.data.data.posts);
                } else {
                    console.error('Invalid data format:', response.data.data.posts);
                }
            })
            .catch(error => {
                console.log("Error fetching posts:", error);
            });
    },

    addNewPost({ commit }, formData) {
        axios.post('/api/user/create-post', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
            .then(response => {
                if (response.status === 200 && response.data.data.success === true) {
                    const newData = response.data.data.data
                    commit('setPosts', newData);
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
                Swal.fire({
                    icon: 'error',
                    title: ' bài viết không thành công',
                    text: `Error ${error}`,
                    showConfirmButton: false,
                    timer: 3000
                })
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