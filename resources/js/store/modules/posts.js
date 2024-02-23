import Swal from 'sweetalert2';

const state = {
    posts: []
};
const mutations = {
    allPosts(state, allPosts) {
        state.posts = allPosts;
    },
    setPosts(state, newData) {
        state.posts.unshift(newData);
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
 
};


export default {
    namespaced: true,
    state,
    mutations,
    actions,
};