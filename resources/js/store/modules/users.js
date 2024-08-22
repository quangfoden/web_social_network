// store/modules/users.js

import axios from 'axios';
const state = {
    users: [],
    accounts:[],
};
const getters = {
    allUsers: state => state.users,
    allAccount: state => state.accounts,
};
const mutations = {
    setUsers(state, users) {
        state.users = users;
    },
    setAccount(state, accounts) {
        state.accounts = accounts;
    },
};

const actions = {
    fetchUsers({ commit }) {
        axios.get('/api/user/allusers')
            .then(response => {
                commit('setUsers', response.data.data);
            })
            .catch(error => {
                console.error('Error fetching users:', error);
            });
    },
    fetchAccounts({ commit }) {
        axios.get('/api/user/allaccount')
            .then(response => {
                commit('setAccount', response.data.data.users);
            })
            .catch(error => {
                console.error('Error fetching Account:', error);
            });
    },
};

export default {
    state,
    mutations,
    actions,
    getters
};
