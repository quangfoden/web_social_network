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
        <div class="chat-body p-2 pt-3" ref="scrollContainer">
            <div ref="messageContainer" v-for="message in listMessage" :key="message.id"
                :class="{ 'left-user': message.sender.id != currentUser.id, 'right-user': message.sender.id == currentUser.id }">
                <div class="user-ifo d-flex gap-2">
                    <img src="/images/avatar.gif" class="custom" alt="">
                    <div class="content-message custom-cursor-pointer">
                        <p v-if="message.text">{{ message.text }}</p>
                        <div v-if="message.fileURL" class="pb-3">
                            <template v-if="message.fileType === 'image'">
                                <img @click="isFileDisplay = message.fileURL" width="150" :src="message.fileURL"
                                    alt="Selected File Preview">
                            </template>
                            <template v-else-if="message.fileType === 'video'">
                                <video @click="isFileDisplay = message.fileURL" width="150" autoplay muted
                                    :src="message.fileURL"></video>
                            </template>
                            <template v-else>
                                <p>{{ filePreview }}</p>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="isTyping" class="custom-loading-mess">
                <div class="wrapper">
                    <img :src="friend.avatar" class="custom" alt="">
                    <div class="circles">
                        <div class="circle"></div>
                        <div class="circle"></div>
                        <div class="circle"></div>
                        <div class="shadow"></div>
                        <div class="shadow"></div>
                        <div class="shadow"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chat-bottom">
            <form @submit.prevent="sendMessage">
                <div class="input-icon d-flex gap-2 align-items-center">
                    <textarea ref="mess-text" @keydown.enter="sendMessage" @input="onInput" v-model="contentMessage"
                        @blur="stopTyping" name="" id="" placeholder="nhập tin nhắn...">
            </textarea>
                    <div v-if="filePreview" class="file-preview p-1 position-relative">
                        <span @click="clearFileMess"
                            class="clear-file-mess position-absolute end-0 custom-cursor-pointer"><i
                                class="fas fa-close"></i></span>
                        <template v-if="fileType === 'image'">
                            <img @click="isFileDisplay = filePreview" width="100" :src="filePreview"
                                alt="Selected File Preview">
                        </template>
                        <template @click="isFileDisplay=filePreview" v-else-if="fileType === 'video'">
                            <video width="100" :src="filePreview"></video>
                        </template>
                        <template v-else>
                            <p>{{ filePreview }}</p>
                        </template>
                    </div>
                    <label class="lmess" for="file-mess">
                        <Image :size="27" fillColor="#43BE62" />
                    </label>
                    <input @change="handleFileChange($event)" type="file" id="file-mess" class="hidden">
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
import { getStorage, ref as storageRef, uploadBytes, getDownloadURL } from "firebase/storage";
import { database, ref as dbRef, ref, push, onValue, query, orderByChild, equalTo, set } from '../../../firebase';
export default {
    data() {
        const useGeneral = useGeneralStore();
        const { isChatBoxOverLay, isLoadingChatBox, isFileDisplay, filesMessUpload } = storeToRefs(useGeneral)
        return {
            isChatBoxOverLay,
            isLoadingChatBox,
            isFileDisplay,
            listMessage: [],
            contentMessage: '',
            filesMessUpload,
            fileURL: null,
            fileType: null,
            filePath: null,
            filePreview: null,
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
            isTyping: false,
            stopTypingTimer: null,
            friendStatusId: null
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
            let format = '';
            if (!lastChanged) return '';
            const currentTime = Date.now();
            const lastChangedTime = new Date(lastChanged);
            const diffMilliseconds = currentTime - lastChangedTime;
            const diffMinutes = Math.floor(diffMilliseconds / (1000 * 60));
            const diffHours = Math.floor(diffMilliseconds / (1000 * 60 * 60));
            const diffDays = Math.floor(diffMilliseconds / (1000 * 60 * 60 * 24));
            const diffMonths = Math.floor(diffMilliseconds / (1000 * 60 * 60 * 24 * 30));
            if (diffMinutes) {
                if (diffMinutes < 1) {
                    format = 'Hoạt động một vài giây trước';
                } else if (diffMinutes === 1) {
                    format = 'Hoạt động 1 phút trước';
                } else if (diffMinutes < 60) {
                    format = `Hoạt động ${diffMinutes} phút trước`;
                } else if (diffHours === 1) {
                    format = 'Hoạt động một giờ trước';
                } else if (diffHours < 24) {
                    format = `Hoạt động ${diffHours} giờ trước`;
                } else if (diffDays === 1) {
                    format = 'Hoạt động một ngày trước';
                } else if (diffDays < 30) {
                    format = `Hoạt động ${diffDays} ngày trước`;
                } else {
                    const day = String(lastChangedTime.getDate()).padStart(2, '0');
                    const month = String(lastChangedTime.getMonth() + 1).padStart(2, '0'); // Months are 0-based
                    const year = lastChangedTime.getFullYear();
                    format = `Hoạt động lần cuối vào ${day}/${month}/${year}`;
                }
            }
            return format;
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

        async sendMessage(event) {
            if (event) event.preventDefault();
            this.stopTyping()
            console.log(this.filesMessUpload);

            if (this.contentMessage.trim() !== '' || this.filesMessUpload) {
                await this.startConversation();
                const messagesRef = dbRef(database, 'messages');

                let messageData = {
                    conversationId: this.conversationId,
                    sender: this.currentUser,
                    receiver: this.friend,
                    text: this.contentMessage,
                    timestamp: Date.now()
                };

                if (this.filesMessUpload) {
                    console.log('hehe');

                    const storage = getStorage();
                    const fileRef = storageRef(storage, `uploads/message/${this.filesMessUpload.name}`);
                    await uploadBytes(fileRef, this.filesMessUpload);
                    const fileURL = await getDownloadURL(fileRef);

                    messageData.fileURL = fileURL;
                    messageData.fileType = this.getFileType(this.filesMessUpload);
                    messageData.filePath = `uploads/message/${this.filesMessUpload.name}`;

                    this.clearFileMess()

                }
                push(messagesRef, messageData);
                this.contentMessage = '';
            }
        },
        getFileType(file) {
            const mimeType = file.type;

            if (mimeType.startsWith('image/')) {
                return 'image';
            } else if (mimeType.startsWith('video/')) {
                return 'video';
            } else if (mimeType.startsWith('audio/')) {
                return 'audio';
            } else if (mimeType === 'application/pdf') {
                return 'pdf';
            } else {
                return 'other';
            }
        },
        handleFileChange($event) {
            this.filesMessUpload = $event.target.files[0];
            $event.target.value = '';
            if (this.filesMessUpload) {
                const fileType = this.filesMessUpload.type;
                if (fileType.startsWith('image/')) {
                    this.filePreview = URL.createObjectURL(this.filesMessUpload);
                    this.fileType = 'image';
                } else if (fileType.startsWith('video/')) {
                    this.filePreview = URL.createObjectURL(this.filesMessUpload);
                    this.fileType = 'video';
                } else {
                    this.filePreview = this.file.name;
                    this.fileType = 'other';
                }
            }
            console.log(this.filePreview);

        },
        clearFileMess() {
            console.log('clear');

            this.filesMessUpload = null
            this.filePreview = null
            this.fileType = null;
            console.log(this.filesMessUpload);
            console.log(this.filePreview);

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
        },
        onInput() {
            const textAreaMess = this.$refs['mess-text'];
            textAreaMess.style.height = 'auto';
            textAreaMess.style.height = `${textAreaMess.scrollHeight}px`;
            set(ref(database, `chats/${this.conversationId}/typingStatus/${this.currentUser.user_id}`), true);
            clearTimeout(this.stopTypingTimer);
            this.stopTypingTimer = setTimeout(() => {
                this.stopTyping();
            }, 3000);
            onValue(dbRef(database, `chats/${this.conversationId}/typingStatus`), (snapshot) => {
                const typingStatus = snapshot.val();
                if (typingStatus) {
                    const typingUsers = Object.keys(typingStatus).filter(userId => typingStatus[userId]);
                    this.friendStatusId = typingUsers[0]
                    if (this.friendStatusId != this.currentUser.user_id) {
                        this.isTyping = typingUsers.length > 0;
                    }
                } else {
                    this.isTyping = false;
                }
            });
        },
        stopTyping() {
            set(dbRef(database, `chats/${this.conversationId}/typingStatus/${this.currentUser.user_id}`), false);
        },
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
    },
    mounted() {
        this.currentUser = this.authUser
        this.scrollToBottom();
        if (this.friend) {
            const participants = [this.currentUser.user_id, this.friend.user_id].sort().join('_');
            this.conversationId = participants;
            this.loadMessages(this.conversationId);
            this.getFriendStatus(this.friend.id)
        }
    },
    updated() {
        this.scrollToBottom();
    },
}
</script>