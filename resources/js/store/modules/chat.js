import axios from 'axios';
import {
    database,
    ref,
    push,
    onValue,
    query,
    orderByChild,
    equalTo,
    limitToLast,
    set,
} from "../../firebase";
const state = {
    friend: null
}

const getters = {
    getFriend: (state) => state.friend,
}
const mutations = {
    setFriend(state, friend) {
        state.friend = friend;
    },
}
const actions = {
    setFriend({ commit }, friend) {
        commit('setFriend', friend);
    },
    getFriend({ commit }, id) {
        return axios.get(`/api/user/getfriendchat/${id}`)
            .then(response => {
                commit('setFriend', response.data);
                return response.data;
            })
            .catch(error => {
                console.log("Error fetching getUserbyId:", error);

            });
    },
}
export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
};