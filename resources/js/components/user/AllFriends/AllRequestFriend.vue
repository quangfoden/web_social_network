<template>
    <div id="allRequestFriends" class="col-12 col-lg-8 order-1 order-lg-2">
        <div v-if="friendRequestWithUsers.length === 0">
            <h4 class="text-white">Không có dữ liệu</h4>
        </div>
        <div v-else v-for="friend in friendRequestWithUsers" :key="friend.id" class="card"
            style="width: 200px; height: fit-content;">
            <img :src="friend.sender.avatar" class="card-img-top" alt="Placeholder Image">
            <div class="card-body">
                <router-link style="font-size: 1.75rem;"
                    :to="{ name: 'Profile User', params: { id: friend.sender.user_id } }"
                    class="text-pr text-white custom-cursor-pointer text-center">{{ friend.sender.user_name
                    }}</router-link>
                <div class="d-flex gap-2">
                    <a  style="font-size: 12px;" class="btn btn-sm btn-primary" @click="acceptRequests(friend.sender.id)">Chấp nhận</a>
                    <a  style="font-size: 12px;" class="btn btn-sm btn-secondary" @click="declineRequests(friend.sender.id)">Từ chối</a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState, mapActions, mapGetters } from 'vuex';

export default {
    computed: {
        ...mapState('friends', {
            loading: state => state.loading
        }),
        ...mapGetters('friends', ['receivedRequests']),
        friendRequestWithUsers() {
            return this.receivedRequests;
        },
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
    },
    mounted() {
    },
    created() {
        this.$store.dispatch('friends/fetchFriendRequests');
    },
    methods: {
        ...mapActions('friends', ['acceptRequests']),
        ...mapActions('friends', ['declineRequests']),
    }
}
</script>