// store/modules/users.js

const state = {
    users: [],
};
const getters = {
    allUsers: state => state.users,
};
const mutations = {
    setUsers(state, users) {
        state.users = users;
    },
};

const actions = {
    fetchUsers({ commit }) {
        axios.get('api/users/allusers')
            .then(response => {
                commit('setUsers', response.data.data);
            })
            .catch(error => {
                console.error('Error fetching users:', error);
            });
    },
};

export default {
    state,
    mutations,
    actions,
    getters
};
