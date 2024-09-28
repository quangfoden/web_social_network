<template>
    <div class="fixed-sidebar right">
        <div class="chat-friends">
            <ul class="chat-users">
                <li @click="fetchFriendChat(friend.id)" v-for="(friend, index) in friends" :key="friend.id">
                    <div class="author-thmb">
                        <img width="40" :src="friend.avatar" alt="">
                        <span class="status f-online"></span>
                    </div>
                    <span class="user-name">{{ friend.user_name }}</span>
                </li>
            </ul>
           
        </div>
    </div><!-- right sidebar user chat -->
</template>
<script>
import { mapState, mapActions, mapGetters } from 'vuex';
import { useGeneralStore } from '../../../store/general';
import { storeToRefs } from 'pinia';
import { database, ref as dbRef, ref, push, onValue, query, orderByChild, equalTo, set } from '../../../firebase';
import friends from '../../../store/modules/friends';

export default {
    data() {
        const useGeneral = useGeneralStore();
        const { isChatBoxOverLay, isLoadingChatBox, selecFriendId } = storeToRefs(useGeneral)
        return {
            isChatBoxOverLay,
            isLoadingChatBox,
            selecFriendId,
            friendStatus: {
                state: 'loading...',
                last_changed: ' '
            },
            friends: [],
        }
    },
    mounted() {
        this.updateFriendStatus()
    },
    watch: {
        friendsWithUsers(newValue) {
            if (newValue && newValue.length) {
                this.updateFriendStatus();
            }
        }
    },
    computed: {
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
        ...mapState({
            accounts: state => state.users.accounts
        }),
        ...mapGetters('friends', ['getFriendsWithUsers']),
        friendsWithUsers() {
            return this.getFriendsWithUsers;
        },
    },
    methods: {
        ...mapActions('chat', ['getFriend']),
        fetchFriendChat(accountId) {
            this.isLoadingChatBox = true
            this.isChatBoxOverLay = true
            this.getFriend(accountId)
                .then(response => {
                    this.isLoadingChatBox = false
                    this.selecFriendId = accountId
                })
                .catch(error => {
                    console.error("Error in fetchFriendChat:", error);
                    this.isLoadingChatBox = true
                });
        },
        getFriendStatus(friendId) {
            const friendStatusRef = ref(database, `status/${friendId}`);
            onValue(friendStatusRef, snapshot => {
                this.friendStatus = snapshot.val();
            });
        },
        updateFriendStatus() {
            for (let friend of this.friendsWithUsers) {
                this.getFriendStatus(friend.user.id);
            }
        },

        getAllFriendById() {
            axios.get(`/api/user/friends/${this.authUser.user_id}`)
                .then(response => {
                    this.friends = response.data;
                })
                .catch(error => {
                    console.error('Error fetching friends:', error);
                });

        },
    },
    created() {
        this.getAllFriendById()
    }
}
</script>