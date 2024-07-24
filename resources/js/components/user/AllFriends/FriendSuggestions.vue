<template>
    <div id="FriendSuggestions" class="col-12 col-lg-8 order-1 order-lg-2">
        <div v-if="AllFriendSuggestions.length === 0">
            <h4 class="text-white">Không có dữ liệu</h4>
        </div>
        <div v-else class="d-flex gap-2">
            <div v-for="friend in AllFriendSuggestions" :key="friend.id"style="width: 200px; height: fit-content;">
                <div class="card" v-if="!friendsWithUsersMap[friend.id]">
                    <img :src="friend.avatar" class="card-img-top" alt="Placeholder Image">
                    <div class="card-body">
                        <router-link style="font-size: 1.75rem;"
                            :to="{ name: 'Profile User', params: { id: friend.user_id } }"
                            class="text-pr text-white custom-cursor-pointer text-center">{{ friend.user_name
                            }}</router-link>
                        <div class="d-flex gap-2">
                            <div>
                                <a style="font-size: 12px;" v-if="friendRequestsMap[friend.id]"
                                    @click.prevent="cancelFriendships(friend.id)" class="btn btn-sm btn-primary">Đã gửi
                                    yêu
                                    cầu kết bạn</a>
                                <div v-else-if="friendreceivedMap[friend.id]" class="d-flex gap-2">
                                    <a style="font-size: 12px;" class="btn btn-sm btn-primary"
                                        @click="acceptRequests(friend.id)">Chấp nhận</a>
                                    <a style="font-size: 12px;" class="btn btn-sm btn-secondary"
                                        @click="declineRequests(friend.id)">Từ chối</a>
                                </div>
                                <a v-else style="font-size: 12px;" class="btn btn-sm btn-primary"
                                    @click.prevent="sendFriendRequest(friend.id)">Thêm
                                    bạn
                                    bè</a>
                            </div>
                            <a style="font-size: 12px;" v-if="!friendreceivedMap[friend.id]"
                                class="btn btn-sm btn-secondary" @click.prevent="fetchFriendChat(friend.id)">Nhắn
                                tin</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState, mapActions, mapGetters } from 'vuex';
import { useGeneralStore } from '../../../store/general';
import { storeToRefs } from 'pinia';
export default {
    data() {
        const useGeneral = useGeneralStore();
        const { isChatBoxOverLay, isLoadingChatBox, selecFriendId } = storeToRefs(useGeneral)
        return {
            isChatBoxOverLay,
            isLoadingChatBox,
            selecFriendId,
        }
    },
    computed: {
        ...mapState({
            accounts: state => state.users.accounts
        }),
        ...mapState('friends', {
            loading: state => state.loading
        }),
        ...mapGetters('friends', ['sentRequests']),
        friendsentRequeststWithUsers() {
            return this.sentRequests;
        },
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
        ...mapGetters('friends', ['getFriendsWithUsers']),
        friendsWithUsersMap() {
            return this.getFriendsWithUsers.reduce((map, request) => {
                map[request.user.id] = request;
                return map;
            }, {});
        },
        AllFriendSuggestions() {
            return this.accounts.filter(account => account.id !== this.authUser.id);
        },
        friendRequestsMap() {
            return this.sentRequests.reduce((map, request) => {
                map[request.receiver_id] = request;
                return map;
            }, {});
        },
        friendreceivedMap() {
            return this.receivedRequests.reduce((map, request) => {
                map[request.sender_id] = request;
                return map;
            }, {});
        },

    },
    created() {
        this.$store.dispatch('friends/getFriendRequestsFrbase', this.authUser.id);
    },
    methods: {
        ...mapActions('friends', ['sendFriendRequest']),
        ...mapActions('friends', ['cancelFriendships']),
        ...mapActions('friends', ['acceptRequests']),
        ...mapActions('friends', ['declineRequests']),
        fetchFriendChat(frendId) {
            console.log(frendId);
            this.isLoadingChatBox = true
            this.isChatBoxOverLay = true
            this.$store.dispatch('chat/getFriend', frendId)
                .then(response => {
                    this.isLoadingChatBox = false
                    this.selecFriendId = frendId
                })
                .catch(error => {
                    console.error("Error in fetchFriendChat:", error);
                    this.isLoadingChatBox = true
                });
        },
    }
}
</script>