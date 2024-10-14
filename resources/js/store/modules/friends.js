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
    receivedRequests: [],
    sentRequests: [],
    friends: [],
    loading: false,
    isfriends: [],
}
const mutations = {
    SET_ISFRIENDS(state, isfriends) {
        state.isfriends = isfriends;
    },
    setFriends: (state, friends) => {
        state.friends = friends;  
    },
    SET_RECEIVED_REQUESTS(state, requests) {
        state.receivedRequests = requests;
    },
    SET_SENT_REQUESTS(state, requests) {
        state.sentRequests = requests;
    },
    SET_FRIENDS(state, friends) {
        state.friends = friends;
    },
    REMOVE_FRIEND(state, friendId) {
        state.friends = state.friends.filter(friend => friend.id !== friendId);
    },
    SET_LOADING(state, status) {
        state.loading = status;
    },
    CANCEL_SENT_REQUEST(state, requestId) {
        state.sentRequests = state.sentRequests.filter(request => request.receiver_id !== requestId);
    },
}
const actions = {
    async fetchIsFriends({ commit }) {
        try {
            const response = await axios.get('/api/user/allfriends');
            commit('SET_ISFRIENDS', response.data);
        } catch (error) {
            console.error('Error fetching friends:', error);
        }
    },
    async sendFriendRequest({ commit, dispatch }, receiverId) {
        try {
            commit('SET_LOADING', true);
            await axios.post('api/user/friend-request', { receiver_id: receiverId });
        } catch (error) {
            console.error(error);
        }
        finally {
            commit('SET_LOADING', false);
        }
    },
    async fetchFriendRequests({ commit }) {
        try {
            commit('SET_LOADING', true);

            const response = await axios.get('/api/user/friend-requests');
            commit('SET_RECEIVED_REQUESTS', response.data.received_requests);
        } catch (error) {
            console.error('Error fetching friend requests:', error);
        } finally {
            commit('SET_LOADING', false);
        }
    },
    async getFriendRequestsFrbase({ commit }, userId) {
        try {
            commit('SET_LOADING', true);

            const friendRequestsRef = ref(database, 'friendRequests');
            onValue(friendRequestsRef, (snapshot) => {
                const receivedRequests = [];
                const sentRequests = [];

                snapshot.forEach((childSnapshot) => {
                    const request = childSnapshot.val();
                    if (request.receiver_id === userId && request.status === 'pending') {
                        receivedRequests.push(request);
                    }

                    if (request.sender_id === userId && request.status === 'pending') {
                        sentRequests.push(request);
                    }
                });

                commit('SET_RECEIVED_REQUESTS', receivedRequests);
                commit('SET_SENT_REQUESTS', sentRequests);
            });

        } catch (error) {
            console.error('Error fetching friend requests from Firebase:', error.message);
        } finally {
            commit('SET_LOADING', false);
        }

    },
    async getFriends({ commit }, authId) {
        try {
            const friendsRef = ref(database, 'friends/' + authId);
            onValue(friendsRef, (snapshot) => {
                if (snapshot.exists()) {
                    const friendsData = snapshot.val();
                    const friendsArray = Object.values(friendsData);
                    commit('SET_FRIENDS', friendsArray);
                } else {
                    const _friendsArray = []
                    commit('SET_FRIENDS', _friendsArray);
                    console.error('No friends found');
                }
            });
        } catch (error) {
            console.log('Error fetching friends:', error);
        }
    },
    async acceptRequests({ commit, dispatch }, id) {
        try {
            commit('SET_LOADING', true);
            await axios.post(`api/user/friend-request/${id}/accept`);
            await dispatch('fetchFriendRequests');
            await dispatch('fetchIsFriends'); 
        } catch (error) {
            console.error(error);
        }
        finally {
            commit('SET_LOADING', false);
        }
    },
    async declineRequests({ commit, dispatch }, id) {
        try {
            commit('SET_LOADING', true);
            await axios.post(`api/user/friend-request/${id}/decline`);
        } catch (error) {
            console.error(error);
        }
        finally {
            commit('SET_LOADING', false);
        }
    },
    async cancelFriendships({ commit, dispatch }, friendId) {
        try {
            commit('SET_LOADING', true);
            const response = await axios.delete(`/api/user/friends/${friendId}`);
            await dispatch('fetchFriendRequests');
            return response.data;
        } catch (error) {
            console.error('Error cancelling friendship:', error);
            throw error;
        } finally {
            commit('SET_LOADING', false);
        }
    },

    async fetchFriendsByUserId({ commit }, userId) {
        try {
            const response = await axios.get(`/api/user/friends/${userId}`);
            commit('setFriends', response.data);  // Gán dữ liệu vào state
        } catch (error) {
            console.error('Error fetching friends:', error);
        }
    },

}

const getters = {
    allFriends: (state) => state.friends,
    getFriendsWithUsers: state => {
        return state.isfriends.map(friend => {
            return {
                ...friend,
                user: friend.user || null,
            };
        });
    }, 
    receivedRequests: state => state.receivedRequests,
    sentRequests: state => state.sentRequests,
    friends: state => state.friends,
    loading: state => state.loading,
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
};