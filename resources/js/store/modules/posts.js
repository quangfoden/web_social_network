import Swal from 'sweetalert2';

const state = {
    posts: []
};
const mutations = {
    setPosts(state, posts) {
        state.posts = posts;
    },
};
const actions = {
    fetchPosts({ commit }) {
        axios.get("/api/user/allposts")
            .then(response => {
                if (Array.isArray(response.data.data.posts)) {
                    commit('setPosts', response.data.data.posts);
                } else {
                    console.error('Invalid data format:', response.data.data.posts);
                }
            })
            .catch(error => {
                console.log("Error fetching posts:", error);
            });
    },
    addNewPost({ commit, dispatch }, formData) {
        axios.post('/api/user/create-post', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
            .then(response => {
                if (response.status === 200 && response.data.data.success === true) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Bài viết được thêm thành công",
                        showConfirmButton: false,
                        timer:3000,
                    });
                    setTimeout(() => {
                        dispatch('fetchPosts');
                    }, 2000);
                } else {
                    // Giải quyết Promise với lỗi
                    Swal.fire({
                        icon: 'error',
                        title: 'Đăng bài viết không thành công',
                        text: `Error`,
                        showConfirmButton: false,
                        timer:3000
                    })
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: ' bài viết không thành công',
                    text: `Error ${error}`,
                    showConfirmButton: false,
                    timer:3000
                })
            });
    },
};

export default {
    namespaced: true,
    state,
    mutations,
    actions
};