import axios from 'axios';
import Swal from 'sweetalert2';


const state = {
    user: [],
    posts: [],
    postsByUser: [],
    page: 1,
    page2: 1,
    loading: false
};
const getters = {
    getUserStatus: state => state.user.status
};

const mutations = {
    RESET_POSTS_BY_USER(state) {
        state.postsByUser = [];
        state.page2 = 1;
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
    SET_INCREMENT_PAGE(state) {
        state.page = 1;
    },
    SET_INCREMENT_PAGE2(state) {
        state.page2 = 1;
    },
    allPosts(state, posts) {
        // state.posts = posts;
        state.posts.push(...posts)
    },
    allPostsByUser(state, posts) {
        state.postsByUser.push(...posts)
        // state.postsByUser[userId] = [...state.postsByUser[userId], ...posts];
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
    },
    updatePost2(state, { postId, newData }) {
        const updatedPostIndex = state.postsByUser.findIndex(post => post.id === postId);
        if (updatedPostIndex !== -1) {
            state.postsByUser[updatedPostIndex] = newData;
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
                console.log(response);
                commit('SET_USER', response.data);
                console.log(state.user);
            })
            .catch(error => {
                console.log("Error fetching getUserbyId:", error);

            });
    },
    fetchPosts({ commit }) {
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
    getters,
    mutations,
    actions,
};