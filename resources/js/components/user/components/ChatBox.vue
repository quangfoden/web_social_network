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
                        <div v-if="message.fileURL" class="pb-2">
                            <template v-if="message.fileType === 'image'">
                                <img width="150" :src="message.fileURL" alt="Selected File Preview">
                            </template>
                            <template v-else-if="message.fileType === 'video'">
                                <video width="150" controls :src="message.fileURL"></video>
                            </template>
                            <template v-else>
                                <p>{{ filePreview }}</p>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chat-bottom">
            <form @submit.prevent="sendMessage">
                <div class="input-icon d-flex gap-2 align-items-center">
                    <textarea @keydown.enter="sendMessage" v-model="contentMessage" name="" id=""
                        placeholder="nhập tin nhắn...">
            </textarea>
                    <div v-if="filePreview" class="file-preview p-3">
                        <template v-if="fileType === 'image'">
                            <img width="100" :src="filePreview" alt="Selected File Preview">
                        </template>
                        <template v-else-if="fileType === 'video'">
                            <video width="100" controls :src="filePreview"></video>
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
        const { isChatBoxOverLay, isLoadingChatBox } = storeToRefs(useGeneral)
        return {
            isChatBoxOverLay,
            isLoadingChatBox,
            listMessage: [],
            contentMessage: '',
            file: null,
            fileURL: null, // URL của file (nếu có)
            fileType: null, // Loại file
            filePath: null,  // Đường dẫn lưu trữ trong dự án (nếu có)
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
                    format = 'Hoạt động một phút trước';
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
        // async sendMessage() {
        //     if (this.contentMessage.trim() !== '') {
        //         await this.startConversation();
        //         const messagesRef = ref(database, 'messages');
        //         push(messagesRef, {
        //             conversationId: this.conversationId,
        //             sender: this.currentUser,
        //             receiver: this.friend,
        //             text: this.contentMessage,
        //             timestamp: Date.now()
        //         });
        //         if (this.file) {
        //             const storage = getStorage();
        //             const fileRef = storageRef(storage, `uploads/${this.file.name}`);
        //             await uploadBytes(fileRef, this.file);
        //             const fileURL = await getDownloadURL(fileRef);
        //             messageData.fileURL = fileURL;
        //             messageData.fileName = this.file.name;
        //             this.file = null;
        //         }
        //         push(messagesRef, messageData);
        //         this.contentMessage = '';
        //     }
        // },
        async sendMessage(event) {
            if (event) event.preventDefault();

            if (this.contentMessage.trim() !== '' || this.file) {
                await this.startConversation();
                const messagesRef = dbRef(database, 'messages');

                let messageData = {
                    conversationId: this.conversationId,
                    sender: this.currentUser,
                    receiver: this.friend,
                    text: this.contentMessage,
                    timestamp: Date.now()
                };

                if (this.file) {
                    const storage = getStorage();
                    const fileRef = storageRef(storage, `uploads/message/${this.file.name}`);
                    await uploadBytes(fileRef, this.file);
                    const fileURL = await getDownloadURL(fileRef);

                    messageData.fileURL = fileURL;
                    messageData.fileType = this.getFileType(this.file);
                    messageData.filePath = `uploads/message/${this.file.name}`; // Đường dẫn trong dự án

                    this.file = null;
                    this.filePreview = null;
                    this.fileType = '';
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
            this.file = $event.target.files[0];
            $event.target.value = '';

            if (this.file) {
                const fileType = this.file.type;
                if (fileType.startsWith('image/')) {
                    this.filePreview = URL.createObjectURL(this.file);
                    this.fileType = 'image';
                } else if (fileType.startsWith('video/')) {
                    this.filePreview = URL.createObjectURL(this.file);
                    this.fileType = 'video';
                } else {
                    this.filePreview = this.file.name;
                    this.fileType = 'other';
                }
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
        // listMessage(newMessages) {
        //     this.$nextTick(() => {
        //         if (newMessages.length > 0) {
        //             const lastMessage = this.$refs.messageContainer[this.$refs.messageContainer.length - 1];
        //             lastMessage.scrollIntoView({ behavior: 'smooth', block: 'end' });
        //         }
        //     });
        // },

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