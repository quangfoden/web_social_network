<script setup>
import Image from 'vue-material-design-icons/Image.vue'
import Send from 'vue-material-design-icons/Send.vue';
import Undo from 'vue-material-design-icons/Undo.vue'
import Close from 'vue-material-design-icons/Close.vue'

</script>
<template>
    <li>
        <div class="comet-avatar">
            <img :src="repcomment.user.avatar" alt="">
        </div>
        <div v-if="!editerRepComment" style="width: 85%;" class="we-comment">
            <h5><a style="color: #fa6342;text-decoration: none; cursor: pointer;" href="time-line.html" title="">{{
                repcomment.user.user_name }}</a></h5>
            <p v-html="contentRepComment"></p>
            <span v-if="repcomment.user.id === authUser.id" class="edit-coment"
                style="margin-left: 50px; cursor: pointer;">
                <i class="fa-solid fa-ellipsis"></i>
                <ul class="edit-options"
                    style="margin: 0;border: none; position: absolute;left: 0;padding: 0;width: 100px; list-style: none;">
                    <li @click="editerRepComment = !editerRepComment" style="cursor: pointer;" class="mb-1">chỉnh sửa
                    </li>
                    <li @click="confirmDeleteRepComment(repcomment.id)" style="cursor: pointer">xoá</li>
                </ul>
            </span>
            <div class="w-100 mt-2 position-relative">
                <img width="150" @click="isFileDisplay = repcomment.url"
                    v-if="repcomment.type != null && repcomment.type.includes('image')" :src="repcomment.url"
                    alt="Image">
                <video width="150" @click="isFileDisplay = repcomment.url"
                    v-else-if="repcomment.type != null && repcomment.type.includes('video')" :src="repcomment.url"
                    controls autoplay muted></video>
            </div>
            <div class="inline-itms">
                <span>{{ repcomment.created_at_formatted }}</span>
                <a @click.prevent="clickRepComment2" class="we-reply" href="#" title="Reply"><i
                        class="fa fa-reply"></i></a>
                <p v-if="repcomment.created_at != repcomment.updated_at" class="mx-2 d-inline secondary-text"
                    style="font-size: 10px;">
                    đã chỉnh sửa</p>
            </div>
        </div>
        <div v-if="editerRepComment" class="edit_comment-form">
            <form style="position: relative;" @submit.prevent="EditRepComment(repcomment.id)" method="post">
                <textarea style="resize: none;" :placeholder="`Viết bình luận với vai trò ${authUser.user_name} ...`"
                :ref="'textarea' + repcomment.id" v-model="editContentRepComment" @input="onInput(repcomment.id, $event)"></textarea>
                <ul v-show="showSuggestions && filteredFriends.length >= 1"
                    class="suggestions rounded  position-absolute">
                    <li v-for="friend in filteredFriends" :key="friend.id" class="rounded"
                        @click="selectFriend(friend, repcomment.id)">
                        <div class="d-flex gap-2 align-items-center">
                            <img width="40" class="rounded-full ml-1 img-cus" :src="friend.avatar" alt="">
                            <p class="primary-text fw-bold mb-0">{{ friend.user_name }}</p>
                        </div>
                    </li>
                </ul>
                <div class="add-smiles">
                    <div v-if="repcomment.url != null" class="uploadimage">
                        <i :class="{ 'no-click': !isFileRepDelete || FileRepUrl.length >= 2 }" class="fa fa-image"></i>
                        <label class="fileContainer" :for="'fileRepCommentEdit' + repcomment.id">
                            <input :ref="'fieldMedia' + repcomment.id" :disabled="!isFileRepDelete || FileRepUrl.length >= 2" 
                                type="file" class="hidden" :id="'fileRepCommentEdit' + repcomment.id" accept="image/*,video/*"
                                @input="handleFileRepChange($event)">
                        </label>
                    </div>
                    <div v-else class="uploadimage">
                        <i :class="{ 'no-click': FileRepUrl.length >= 2 }" class="fa fa-image"></i>
                        <label class="fileContainer" :for="'fileRepCommentEdit' + repcomment.id">
                            <input :disabled="FileRepUrl.length >= 2" :ref="'fieldMedia' + repcomment.id" type="file"
                                class="hidden" :id="'fileRepCommentEdit' + repcomment.id" accept="image/*,video/*"
                                @input="handleFileRepChange($event)">
                        </label>
                    </div>
                    <span class="em em-expressionless" title="add icon"></span>
                    <div class="smiles-bunch">
                        <i class="em em---1"></i>
                        <i class="em em-smiley"></i>
                        <i class="em em-anguished"></i>
                        <i class="em em-laughing"></i>
                        <i class="em em-angry"></i>
                        <i class="em em-astonished"></i>
                        <i class="em em-blush"></i>
                        <i class="em em-disappointed"></i>
                        <i class="em em-worried"></i>
                        <i class="em em-kissing_heart"></i>
                        <i class="em em-rage"></i>
                        <i class="em em-stuck_out_tongue"></i>
                    </div>
                </div>
                <button style="padding: 5px 20px;" type="submit">
                    <i style="color: #535758;" class="fas fa-paper-plane"></i>
                </button>
            </form>
            <div v-if="FileRepUrl" class="pt-2 position-relative cus-img-dis">
                <div class="mb-1" v-for="fileUrl in FileRepUrl" :key=index>
                    <div v-if="fileUrl.url && fileUrl.url != null">
                        <Undo :class="{ 'no-click-event': FileRepUrl.length >= 2 && fileUrl.deleted }"
                            v-show="fileUrl.deleted" @click="revertFileRep(fileUrl)"
                            class="position-absolute bg-white p-1 m-2 z-1000 right-2 rounded-full border custom-cursor-pointer"
                            :size="22" fillColor="#5E6771" />
                        <div :class="{ 'opacity-50': fileUrl.deleted }">
                            <Close @click="clearFileRep(fileUrl)" v-show="!fileUrl.deleted"
                                class="position-absolute bg-white p-1 m-2 right-2 z-1000 rounded-full border custom-cursor-pointer"
                                :size="22" fillColor="#5E6771" />
                            <div v-if="fileUrl.type.includes('image')">
                                <img class="rounded-lg mx-auto w-50" :src="fileUrl.url" loading="lazy" alt="">
                            </div>
                            <div v-else-if="fileUrl.type.includes('video')">
                                <video class="rounded-lg mx-auto w-50" controls>
                                    <source :src="fileUrl.url" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            <div v-else>
                                <a href="fileUrl.url">{{ fileUrl.url }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p style="text-decoration: underline; display: inline;color: blue; cursor: pointer;" v-if="editerRepComment"
                @click="CancleEditRepComment()">Huỷ</p>
        </div>
    </li>
</template>
<script>
import { ref } from "vue";
import diacritics from 'diacritics';
import { v4 as uuidv4 } from 'uuid';
import { mapState,mapGetters  } from 'vuex';
export default {
    props: {
        repcomment: {
            type: Object,
            required: true,
        },
        index: {
            type: Number,
            required: true,
        },
        commentId: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            contentRepComment: "",
            showmodelEditRepComment: false,
            editerRepComment: false,
            isFileRepDelete: false,
            editContentRepComment: "",
            rawContent: this.repcomment.content,
            repCommentWatcher: null,
            FileRepUrl: ref([]),
            deleteFileRep: ref([]),
            fileRep: null,
            friend: [],
            selectedFrientEidtRepComment: null,
            filteredFriends: [],
            showSuggestions: false,
            isSendLoading: false
        }
    },
    computed: {
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
        ...mapGetters('friends', ['allFriends']),
        friends() {
            return this.allFriends;
        },
        ...mapState({
            accounts: state => state.users.accounts
        })
    },

    methods: {
        convertIdsToUsernames(content) {
            if (content) {
                return content.replace(/<a href='\/profile\/(\d+)' class='custom-span'>(\d+)<\/a>/g, (match, accountId) => {
                    const account = this.getUserById(accountId);
                    return `<a href='/profile/${account.user_id}' class='custom-span'>${account.user_name}</a>`;
                });
            }
        },
        clickRepComment2() {
            this.$emit('focus-input', this.commentId);
            this.$emit('reply-comment-clicked', {
                selectedRepCommentIndex: this.index,
                boxRepComment: true,
                isRepComment: false,
                istag: false,
                datacontentRepComment: '@' + this.repcomment.user.user_name + ' '
            });
        },
        handleFileRepChange($event) {
            const chosenFile = $event.target.files[0];
            if (chosenFile) {
                this.fileRep = chosenFile;
                $event.target.value = '';
                chosenFile.id = uuidv4();
                const promise = this.readFile(chosenFile);
                const type = this.getFileType(chosenFile);
                promise.then(url => {
                    const newFile = {
                        isNew: true,
                        url,
                        id: chosenFile.id,
                        type
                    };
                    this.FileRepUrl = this.FileRepUrl.filter(file => file.id !== newFile.id);
                    this.FileRepUrl.push(newFile);
                    this.isFileRepDelete = false
                });
            }

            else {
                this.isFileRepDelete = true
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
        readFile(file) {
            return new Promise((resolve, reject) => {
                const fileReader = new FileReader()
                fileReader.readAsDataURL(file)
                fileReader.onload = () => {
                    resolve(fileReader.result)
                }
                fileReader.onerror = reject
            })
        },
        clearFileRep(file) {
            if (file.isProp) {
                this.deleteFileRep.push(file.id)
                this.isFileRepDelete = true
                file.deleted = true;
                console.log(this.deleteFileRep);
                console.log(this.isFileRepDelete);
            } else {
                this.fileRep = null
                this.FileRepUrl = this.FileRepUrl.filter(f => f.id !== file.id)
                console.log(this.deleteFileRep);
                this.isFileRepDelete = true
                console.log(this.isFileRepDelete);
            }
        },
        revertFileRep(file) {
            this.deleteFileRep = this.deleteFileRep.filter(id => id !== file.id)
            file.deleted = false;
            this.isFileRepDelete = false
            console.log(this.deleteFileRep);
            console.log(this.isFileRepDelete);

        },
        toggleEditRepComment(repCommentid, repCommentUrl) {
            if (repCommentUrl === null) {
                this.isFileRepDelete = true
            }
            if (this.repcomment.id === repCommentid) {
                this.showmodelEditRepComment = !this.showmodelEditRepComment
            }
        },
        CancleEditRepComment() {
            this.editerRepComment = !this.editerRepComment
            this.editContentRepComment = this.convertLinksToMentions(this.rawContent)
        },
        EditRepComment(repCommentId) {
            this.isSendLoading = true
            let contentRepEdit = this.editContentRepComment
            if (this.selectedFrientEidtRepComment) {
                contentRepEdit = contentRepEdit.replace('@' + this.selectedFrientEidtRepComment.user_name,
                    "<a href='/profile/"
                    + this.selectedFrientEidtRepComment.user_id + "' class='custom-span'>" + this.selectedFrientEidtRepComment.user_id + "</a>"
                )
            }
            const formData = new FormData();
            formData.append('content', contentRepEdit);
            for (let _deletedFile of this.deleteFileRep) {
                formData.append('deletedFile[]', _deletedFile);
            }
            if (this.fileRep) {
                formData.append('file', this.fileRep);
            }
            this.$store.dispatch('post/editRepComment', { repCommentId: repCommentId, formData: formData })
                .then(response => {
                    this.isSendLoading = false
                    this.editerRepComment = false
                    this.selectedFrientEidtRepComment = null
                    this.$emit('repcomment-updated', response.data.repcomment);
                    formData.values = ''
                    this.fileRep = null
                    this.deleteFileRep = []
                    console.log(this.FileRepUrl);
                    this.$swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: `${response.data.message}`,
                        showConfirmButton: false,
                        timer: this.$config.notificationTimer ?? 3000,
                    });
                    this.resetRepCommentWatcher();
                })
                .catch(error => {
                    this.isSendLoading = false
                    this.selectedFrientEidtComment = null
                    this.editerRepComment = false
                    this.editContentRepComment = this.repcomment.content,
                        this.$swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: `cập nhật không thành công !`,
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                    console.error('Có lỗi xảy ra:', error);
                });
        },
        confirmDeleteRepComment(repcommentId) {
            this.$swal.fire({
                title: 'Bạn chắc chắn muốn xoá?',
                text: 'Hành động này sẽ không thể hoàn tác!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xoá',
                cancelButtonText: 'Hủy',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.DeleteRepComment(repcommentId);
                }
            });
        },
        DeleteRepComment(repcommentId) {
            this.$store.dispatch('post/delete_repcomment', repcommentId)
                .then(response => {
                    console.log(response);
                    this.$swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: `${response.data.message}`,
                        showConfirmButton: false,
                        timer: this.$config.notificationTimer ?? 3000,
                    });
                    this.$emit('repcomment-deleted', repcommentId);
                })
                .catch(error => {
                    console.log(error);
                    this.$swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: `Lỗi`,
                        showConfirmButton: false,
                        timer: this.$config.notificationTimer ?? 3000,
                    });
                })
        },
        convertLinksToMentions(html) {
            const regex = /<a href='\/profile\/(\d+)' class='custom-span'>(\d+)<\/a>/;
            const match = html.match(regex);
            if (match) {
                const userId = match[1];
                const account = this.getUserById(userId)
                this.selectedFrientEidtRepComment = account
                const username = account.user_name
                return html.replace(regex, `@${username}`);
            }
            return html;
        },
        getUserById(accountId) {
            return this.accounts.find(account => account.user_id == accountId);
        },
        onInput(id, event) {
            const textAreaComment = this.$refs['textarea' + id]
            textAreaComment.style.height = 'auto';
            textAreaComment.style.height = `${textAreaComment.scrollHeight}px`;
            const text = event.target.value;
            const position = event.target.selectionStart;
            const match = text.substring(0, position).match(/@(\S*)$/);
            if (match) {
                const query = diacritics.remove(match[1].toLowerCase());
                this.filteredFriends = this.friends.filter(
                    friend =>
                        diacritics.remove(friend.user_name.toLowerCase()).includes(query)
                );
                this.showSuggestions = true;
            } else {
                this.showSuggestions = false;
                console.log('lỗi nè');
            }
        },
        selectFriend(friend, id) {
            this.selectedFrientEidtRepComment = friend;
            console.log(this.selectedFrientEidtRepComment.user_name);
            console.log(this.selectedFrientEidtRepComment);
            const textArea = this.$refs['textarea' + id];
            const position = textArea.selectionStart;

            const text = textArea.value.substring(0, position);
            const mentionStart = text.lastIndexOf('@');
            const textBefore = textArea.value.substring(0, mentionStart);
            const textAfter = textArea.value.substring(position);

            this.editContentRepComment = `@${friend.user_name} `;
            this.showSuggestions = false;

            textArea.focus();
        },
        CusRedirect(event) {
            const url = event.target.getAttribute('href');
            if (url) {
                this.$router.push(url);
            }
        },
        setupRepCommentWatcher() {
            this.repCommentWatcher = this.$watch(
                () => this.repcomment,
                (newFile, oldFile) => {
                    const urlArray = Array.isArray(newFile) ? newFile : [newFile];
                    this.FileRepUrl = [
                        ...this.FileRepUrl,
                        ...urlArray.map(im => ({
                            id: im.id,
                            content: im.content,
                            type: im.type,
                            url: im.url,
                            isProp: true,
                            deleted: false
                        }))
                    ]
                },
                { immediate: true, deep: true }
            )
        },
        resetRepCommentWatcher() {
            if (this.repCommentWatcher) {
                this.repCommentWatcher();
            }
            this.repCommentWatcher = this.$watch(
                () => this.repcomment,
                (newFile, oldFile) => {
                    const urlArray = Array.isArray(newFile) ? newFile : [newFile]
                    this.FileRepUrl = urlArray.map(im => ({
                        id: im.id,
                        content: im.content,
                        type: im.type,
                        url: im.url,
                        isProp: true,
                        deleted: false
                    }))
                },
                { immediate: true, deep: true }
            )
        }
    },
    mounted() {
        this.editContentRepComment = this.convertLinksToMentions(this.rawContent)
        this.contentRepComment = this.convertIdsToUsernames(this.rawContent)
    },
    watch: {
        'repcomment.content'(newValue) {
            this.contentRepComment = this.convertIdsToUsernames(newValue)
            this.editContentRepComment = this.convertLinksToMentions(newValue);
        }
    },
    created() {
        this.setupRepCommentWatcher();
        // this.friends = this.accounts
    },
}
</script>