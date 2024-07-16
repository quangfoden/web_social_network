<template>
    <div id="allYourFriends" class="col-12 col-lg-8 order-1 order-lg-2">
        <div v-for="friend in friendsWithUsers" :key="friend.id" class="card"
            style="width: 200px; height: fit-content;">
            <img :src="friend.user.avatar" class="card-img-top" alt="Placeholder Image">
            <div class="card-body">
                <h3 href="#" class="text-pr custom-cursor-pointer text-center">{{ friend.user.user_name }}</h3>
                <router-link @click="gotoFriendPr()" :to="{ name: 'Profile User', params: { id: friend.user.user_id } }"
                    href="#" class="btn btn-primary text-center">Xem trang cá nhân</router-link>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex';
import { getStorage, ref as storageRef, uploadBytes, getDownloadURL } from "firebase/storage";
import { storeToRefs } from 'pinia';
import { useGeneralStore } from '../../../store/general';
export default {
    data() {
        const useGeneral = useGeneralStore();
        const { showClickBack } = storeToRefs(useGeneral)
        return {
            showClickBack
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
        gotoFriendPr() {
            this.showClickBack = true
        }
    }
}
</script>
