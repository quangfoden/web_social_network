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
                if (Array.isArray(response.data.posts)) {
                    commit('setPosts', response.data.posts);
                } else {
                    console.error('Invalid data format:', response.data.posts);
                }
            })
            .catch(error => {
                console.log("Error fetching posts:", error);
            });
    },
    addNewPost({ commit }, formData) {
        return new Promise((resolve, reject) => {
            axios.post('/api/user/create-post', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            })
            .then(response => {
                if (response.status === 200 && response.data.data.success === true) {
                    // Giải quyết Promise với dữ liệu bài viết được thêm
                    resolve(response.data.data.post);
                } else {
                    // Giải quyết Promise với lỗi
                    reject(response.data.data.message[0]);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                reject(error);
            });
        });
    },
};

export default {
    namespaced: true,
    state,
    mutations,
    actions
};