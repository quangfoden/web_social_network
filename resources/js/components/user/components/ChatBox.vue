<script setup>
import Image from 'vue-material-design-icons/Image.vue'
</script>
<template>
    <div v-if="!isLoadingChatBox && friend" id="ChatBox">
        <div class="chat-header">
            <div class="">
                <div class="user-ifo d-flex gap-2">
                    <img src="/images/avatar.gif" class="custom" alt="">
                    <span class="dot-status" :class="{ 'online': friendStatus.state === 'online' }"></span>
                    <div>
                        <h3 class="user-name">{{ friend.user_name }}</h3>
                        <small class="secondary-text">
                            {{ friendStatus.state === "online" ? "Đang hoạt động" :
                                formatLastChanged(friendStatus.last_changed) }}
                        </small>
                    </div>
                    <span @click="closeChatBox" class="close-mess">
                        <i class="fas fa-close"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="chat-body  p-2" ref="scrollContainer">
            <div ref="messageContainer" v-for="message in listMessage" :key="message.id"
                :class="{ 'left-user': message.sender.id != currentUser.id, 'right-user': message.sender.id == currentUser.id }">
                <div class="user-ifo d-flex gap-2">
                    <img src="/images/avatar.gif" class="custom" alt="">
                    <div class="content-message">
                        <p>{{ message.text }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="chat-bottom">
            <form @submit.prevent="sendMessage">
                <div class="input-icon d-flex gap-2 align-items-center">
                    <textarea v-model="contentMessage" name="" id="" placeholder="nhập tin nhắn..."></textarea>
                    <label class="lmess" for="file-mess">
                        <Image :size="27" fillColor="#43BE62" />
                    </label>
                    <input type="file" id="file-mess" class="hidden">
                </div>
                <button type="submit" class="sendmess">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
    <div v-if="isLoadingChatBox" id="ChatBox">
        <div class="chat-header">
            <div class="">
                <div class="user-ifo d-flex gap-2">
                    <div>
                        <h3 class="user-name"></h3>
                        <small class="text-xs text-gray-600"></small>
                    </div>
                    <span @click="closeChatBox" class="close-mess">
                        <i class="fas fa-close"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="chat-body">
            <div class="left-user">
                <div class="user-ifo d-flex gap-2">
                    <div class="">
                        <p></p>
                    </div>
                </div>
            </div>
            <div class="right-user">
                <div class="user-ifo d-flex gap-2">
                    <div class="">
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import { useGeneralStore } from '../../../store/general';
import { useUserStatus } from '../../../core/coreFunction';
import { storeToRefs } from 'pinia';
import { mapActions, mapState, mapGetters } from 'vuex';
import { database, ref, push, onValue, query, orderByChild, equalTo, set } from '../../../firebase';
export default {
    data() {
        const useGeneral = useGeneralStore();
        const { isChatBoxOverLay, isLoadingChatBox } = storeToRefs(useGeneral)
        return {
            isChatBoxOverLay,
            isLoadingChatBox,
            listMessage: [],
            contentMessage: '',
            sender: null,
            receiver: null,
            reply: {
                message: ''
            },
            isReply: false,
            currentUser: null,
            conversationId: null,
            friendStatus: {
                state: 'loading...',
                last_changed: ' '
            },
        }
    },
    computed: {
        ...mapGetters('chat', ['getFriend']),
        friend() {
            return this.getFriend;
        },
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
    },
    methods: {
        ...mapActions('chat', ['setFriend']),
        closeChatBox() {
            this.isChatBoxOverLay = false
            this.setFriend(null);
        },
        formatLastChanged(lastChanged) {
            let format = " "
            if (!lastChanged) return '';
            const currentTime = Date.now();
            const lastChangedTime = new Date(lastChanged);

            const diffMinutes = Math.floor((currentTime - lastChangedTime) / (1000 * 60));
            if (diffMinutes) {
                if (diffMinutes >= 1440) {
                    const hours = lastChangedTime.getHours();
                    const minutes = lastChangedTime.getMinutes();
                    format = `Hoạt động lần cuối vào lúc ${hours}:${minutes}`
                } else {
                    if (diffMinutes < 1) {
                        return format;
                    } else if (diffMinutes === 1) {
                        format = 'Hoạt động một phút trước'
                    } else {
                        format = `Hoạt động ${diffMinutes} phút trước`
                    }
                }
            }
            return format
        },
        async startConversation() {
            const participants = [this.currentUser.user_id, this.friend.user_id].sort().join('_');
            const conversationRef = ref(database, `conversations/${participants}`);
            set(conversationRef, {
                participants,
                createdAt: Date.now()
            });
            return participants;
        },
        loadMessages(conversationId) {
            const messagesRef = query(ref(database, 'messages'), orderByChild('conversationId'), equalTo(conversationId));
            onValue(messagesRef, snapshot => {
                const messages = [];
                snapshot.forEach(childSnapshot => {
                    messages.push({ id: childSnapshot.key, ...childSnapshot.val() });
                });
                this.listMessage = messages;
            });
        },
        async sendMessage() {
            if (this.contentMessage.trim() !== '') {
                await this.startConversation();
                const messagesRef = ref(database, 'messages');
                push(messagesRef, {
                    conversationId: this.conversationId,
                    sender: this.currentUser,
                    receiver: this.friend,
                    text: this.contentMessage,
                    timestamp: Date.now()
                });
                this.contentMessage = '';
            }
        },
        getFriendStatus(friendId) {
            const friendStatusRef = ref(database, `status/${friendId}`);
            onValue(friendStatusRef, snapshot => {
                this.friendStatus = snapshot.val();
            });
        },
        scrollToBottom() {
            if (this.$refs.scrollContainer) {
                this.$refs.scrollContainer.scrollTop = this.$refs.scrollContainer.scrollHeight;
            }
        }
    },
    watch: {
        friend(newFriend) {
            if (newFriend) {
                const participants = [this.currentUser.user_id, newFriend.user_id].sort().join('_');
                this.conversationId = participants;
                this.loadMessages(this.conversationId);
                this.getFriendStatus(newFriend.id)
            }
            else {
                console.log('No friend');
            }
        },
        listMessage(newMessages) {
            this.$nextTick(() => {
                if (newMessages.length > 0) {
                    const lastMessage = this.$refs.messageContainer[this.$refs.messageContainer.length - 1];
                    lastMessage.scrollIntoView({ behavior: 'smooth', block: 'end' });
                }
            });
        },

    },
    mounted() {
        this.currentUser = this.authUser
        this.scrollToBottom();

    },
    updated() {
        this.scrollToBottom();
    },
}
</script>