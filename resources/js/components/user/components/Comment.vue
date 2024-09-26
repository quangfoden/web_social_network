<script setup>
import Image from 'vue-material-design-icons/Image.vue'
import AccountMultiple from 'vue-material-design-icons/AccountMultiple.vue';
import ThumbUp from 'vue-material-design-icons/ThumbUp.vue';
import Check from 'vue-material-design-icons/Check.vue';
import Delete from 'vue-material-design-icons/Delete.vue';
import Close from 'vue-material-design-icons/Close.vue'
import Send from 'vue-material-design-icons/Send.vue';
import Undo from 'vue-material-design-icons/Undo.vue'

</script>
<template>
    <li>
        <div class="comet-avatar">
            <img :src="comment.user.avatar" alt="">
        </div>
        <div v-if="!editerComment" class="we-comment">
            <h5><a style="color: #fa6342;text-decoration: none; cursor: pointer;" href="time-line.html" title="">{{
                comment.user.user_name }}</a></h5>
            <p v-html="contentComment"></p>
            <span v-if="comment.user.id === authUser.id" class="edit-coment"
                style="position: relative; margin-left: 50px; cursor: pointer;">
                <i class="fa-solid fa-ellipsis"></i>
                <ul class="edit-options"
                    style="margin: 0;border: none; position: absolute;left: 0;padding: 0;width: 100px; list-style: none;">
                    <li @click="editerComment = !editerComment" style="cursor: pointer;" class="mb-1">chỉnh sửa</li>
                    <li @click="confirmDeleteComment(comment.id)" style="cursor: pointer">xoá</li>
                </ul>
            </span>
            <div class="w-100 mt-2 position-relative">
                <img width="150" @click="isFileDisplay = comment.url"
                    v-if="comment.type != null && comment.type.includes('image')" :src="comment.url" alt="Image">
                <video width="150" @click="isFileDisplay = comment.url"
                    v-else-if="comment.type != null && comment.type.includes('video')" :src="comment.url" controls
                    autoplay muted></video>
            </div>
            <div class="inline-itms">
                <span>{{ comment.created_at_formatted }}</span>
                <a class="we-reply" href="#" title="Reply"><i class="fa fa-reply"></i></a>
                <p v-if="comment.created_at != comment.updated_at" class="mx-2 d-inline secondary-text" style="font-size: 10px;">
                    đã chỉnh sửa</p>
            </div>
        </div>
        <div v-if="editerComment" class="edit_comment-form">
            <form style="position: relative;" @submit.prevent="EditComment(comment.id)" method="post">
                <textarea style="resize: none;" :placeholder="`Viết bình luận với vai trò ${authUser.user_name} ...`"
                    :ref="'comment' + comment.id" @input="onInput(comment.id, $event)"
                    v-model="editContentComment"></textarea>
                <ul v-show="showSuggestions && filteredFriends.length >= 1"
                    class="suggestions rounded  position-absolute">
                    <li v-for="friend in filteredFriends" :key="friend.id" class="rounded"
                        @click="selectFriend(friend.user, post.id)">
                        <div class="d-flex gap-2 align-items-center">
                            <img class="rounded-full ml-1 img-cus" :src="friend.user.avatar" alt="">
                            <p class="primary-text fw-bold mb-0">{{ friend.user.user_name }}</p>
                        </div>
                    </li>
                </ul>
                <div class="add-smiles">
                    <div v-if="comment.url != null" class="uploadimage">
                        <i :class="{ 'no-click': !isFileDelete || fileUrls.length >= 2 }" class="fa fa-image"></i>
                        <label class="fileContainer">
                            <input :disabled="!isFileDelete || fileUrls.length >= 2" :ref="'fieldMedia' + comment.id" type="file" class="hidden"
                                :id="'fileCommentEdit' + comment.id" accept="image/*,video/*"
                                @input="handleFileChange($event)">
                        </label>
                    </div>
                    <div v-else class="uploadimage">
                        <i :class="{ 'no-click':fileUrls.length >= 2 }" class="fa fa-image"></i>
                        <label class="fileContainer">
                            <input :disabled="fileUrls.length >= 2" :ref="'fieldMedia' + comment.id" type="file" class="hidden"
                                :id="'fileCommentEdit' + comment.id" accept="image/*,video/*"
                                @input="handleFileChange($event)">
                        </label>
                    </div>
                    <span class="em em-expressionless" title="add icon"></span>
                    <div class="smiles-bunch active">
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
            <div v-if="fileUrls" class="pt-2 position-relative cus-img-dis">
                <div class="mb-1" v-for="fileUrl in fileUrls" :key=index>
                    <div v-if="fileUrl.url && fileUrl.url != null">
                        <Undo :class="{ 'no-click-event': fileUrls.length >= 2 && fileUrl.deleted }"
                            v-show="fileUrl.deleted" @click="revertFile(fileUrl)"
                            class="position-absolute bg-white p-1 m-2 z-1000 right-2 rounded-full border custom-cursor-pointer"
                            :size="22" fillColor="#5E6771" />
                        <div :class="{ 'opacity-50': fileUrl.deleted }">
                            <Close @click="clearFile(fileUrl)" v-show="!fileUrl.deleted"
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
            <p style="text-decoration: underline; display: inline;color: blue; cursor: pointer;" v-if="editerComment"
                @click="CancleEditComment()">Huỷ</p>
        </div>
    </li>
</template>
<script>
import diacritics from 'diacritics';
import { toRefs, reactive, ref } from 'vue';
import { mapState, mapActions, mapGetters } from 'vuex';
import RepComment from '../Components/Repcomment.vue'
import { useGeneralStore } from '../../../store/general';
import { storeToRefs } from 'pinia';
import { param } from 'jquery';
import { v4 as uuidv4 } from 'uuid';
import { error } from 'jquery';

export default {
    components: {
        RepComment
    },
    props: {
        comment: {
            type: Object,
            required: true,
        },
        repcomment_count: {
            type: Number,
            required: true,
        },
    },
    data() {
        const useGeneral = useGeneralStore()
        const { isFileDisplay } = storeToRefs(useGeneral)
        return {
            contentComment: "",
            rawContent: this.comment.content,
            isComment: this.comment,
            isFileDisplay,
            formRepComment: { content: '' },
            formMediarepComment: {},
            boxRepComment: reactive(false),
            filteredFriends: [],
            friends: [],
            selectedFrientCreatedComment: null,
            selectedFrientEidtComment: null,
            isRepComment: false,
            istag: false,
            selectedRepCommentIndex: null,
            showAllRepComments: false,
            showmodelEditComment: false,
            editerComment: false,
            editContentComment: ref(this.comment.content),
            commentWatcher: null,
            file: null,
            fileUrls: ref([]),
            deleteFile: ref([]),
            isFileDelete: false,
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
        ...mapState({
            accounts: state => state.users.accounts
        }),
        ...mapGetters('friends', ['getFriendsWithUsers']),
        friendsWithUsers() {
            return this.getFriendsWithUsers;
        },
        isSubmitDisabled() {
            return this.formRepComment.content === '' && Object.keys(this.formMediarepComment).length === 0;
        }
    },
    mounted() {

    },
    methods: {
        getUploadedImageRepComment(e) {
            const file = e.target.files[0];
            let mediaType;
            if (file.type.startsWith('image/')) {
                mediaType = 'image';
            } else if (file.type.startsWith('video/')) {
                mediaType = 'video';
            }
            const url = URL.createObjectURL(file);
            this.formMediarepComment.type = mediaType;
            this.formMediarepComment.url = url;
            this.formMediarepComment.file = file;
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
            const chosenFile = $event.target.files[0];
            if (chosenFile) {
                this.file = chosenFile;
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
                    this.fileUrls = this.fileUrls.filter(file => file.id !== newFile.id);
                    this.fileUrls.push(newFile);
                    this.isFileDelete = false
                });
            }
            else {
                this.isFileDelete = true
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
        clearFile(file) {
            if (file.isProp) {
                this.deleteFile.push(file.id)
                this.isFileDelete = true
                file.deleted = true;
            } else {
                this.file = null
                this.fileUrls = this.fileUrls.filter(f => f.id !== file.id)
                this.isFileDelete = true
            }
        },
        revertFile(file) {
            this.deleteFile = this.deleteFile.filter(id => id !== file.id)
            file.deleted = false;
            this.isFileDelete = false
            console.log(this.deleteFile);
        },
        EditComment(commentId) {
            this.isSendLoading = true
            let contentEdit = ""
            if (this.editContentComment) {
                contentEdit = this.editContentComment
            }
            if (this.selectedFrientEidtComment) {
                contentEdit = contentEdit.replace('@' + this.selectedFrientEidtComment.user_name,
                    "<a href='/profile/"
                    + this.selectedFrientEidtComment.user_id + "' class='custom-span'>" + this.selectedFrientEidtComment.user_id + "</a>"
                )
            }
            const formData = new FormData();
            formData.append('content', contentEdit);
            for (let _deletedFile of this.deleteFile) {
                formData.append('deletedFile[]', _deletedFile);
            }
            if (this.file) {
                formData.append('file', this.file);
            }
            this.$store.dispatch('post/editComment', { commentId: commentId, formData: formData })
                .then(response => {
                    this.isSendLoading = false
                    this.editerComment = false
                    this.selectedFrientEidtComment = null
                    this.$emit('comment-updated', response.data.comment);
                    formData.values = ''
                    this.file = null
                    this.deleteFile = []
                    this.$swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: `${response.data.message}`,
                        showConfirmButton: false,
                        timer: this.$config.notificationTimer ?? 3000,
                    });
                    this.resetCommentWatcher();
                })
                .catch(error => {
                    this.isSendLoading = false
                    this.selectedFrientEidtComment = null
                    this.editerComment = false
                    this.editContentComment = this.comment.content,
                        this.$swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: `bình luận cập nhật không thành công !`,
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                    console.error('Có lỗi xảy ra:', error);
                });
        },
        confirmDeleteComment(commentId) {
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
                    this.DeleteComment(commentId);
                }
            });
        },
        DeleteComment(commentId) {
            this.$store.dispatch('post/delete_comment', commentId)
                .then(response => {
                    console.log(response);
                    this.$swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: `${response.data.message}`,
                        showConfirmButton: false,
                        timer: this.$config.notificationTimer ?? 3000,
                    });
                    this.$emit('comment-deleted', commentId);
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
        handleReplyCommentClicked(payload) {
            this.selectedRepCommentIndex = payload.selectedRepCommentIndex
            this.boxRepComment = payload.boxRepComment;
            this.isRepComment = payload.isRepComment;
            this.istag = payload.istag;
            this.formRepComment.content = payload.datacontentRepComment;
        },
        CreateRepComment(commentId) {
            this.isSendLoading = true
            const formData = new FormData();
            let content = this.formRepComment.content
            if (this.isRepComment && !this.istag) {
                content = content.replace('@' + this.comment.user.user_name,
                    "<a href='/profile/"
                    + this.comment.user.user_id + "' class='custom-span'>" + this.comment.user.user_id + "</a>")
            }
            else {
                if (this.selectedRepCommentIndex && !this.istag) {
                    content = content.replace('@' + this.comment.repcomments[this.selectedRepCommentIndex].user.user_name,
                        "<a href='/profile/"
                        + this.comment.repcomments[this.selectedRepCommentIndex].user.user_id + "' class='custom-span'>" + this.comment.repcomments[this.selectedRepCommentIndex].user.user_id + "</a>")
                }
            }
            if (this.istag && this.selectedFrientCreatedComment) {
                content = content.replace('@' + this.selectedFrientCreatedComment.user_name,
                    "<a href='/profile/"
                    + this.selectedFrientCreatedComment.user_id + "' class='custom-span'>" + this.selectedFrientCreatedComment.user_id + "</a>")
            }
            formData.append('content', content);
            formData.append('file', this.formMediarepComment.file);
            this.$store.dispatch('post/createRepComment', { commentId: commentId, formData: formData })
                .then(response => {
                    this.isSendLoading = false
                    console.log(this.istag);
                    console.log(this.selectedFrientCreatedComment);
                    if (response.status === 200 && response.data.data.success === true) {
                        const input = document.getElementById(`fieldMediaRepCM_${commentId}`);
                        if (input) {
                            input.value = null;
                        }
                        this.comment.repcomments.push(response.data.data.repcomment)
                        this.$emit('repcomment-created')
                        this.$swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Rep Comment thành công !",
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                        this.formRepComment.content = ''; // Reset nội dung comment
                        this.formMediarepComment = {}
                        this.formMediarepComment.file = null;
                        this.isRepComment = false
                    }
                    else {
                        const input = document.getElementById(`fieldMediaRepCM_${commentId}`);
                        if (input) {
                            input.value = null;
                        }
                        this.$swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "Rep Comment không thành công !",
                            text: `Lỗi: ${response.data.data.message}`,
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                        this.formRepComment.content = '';
                        this.formMediarepComment = {}
                        this.formMediarepComment.file = null;
                    }
                })
                .catch(error => {
                    this.isSendLoading = false
                    this.$swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: error,
                        showConfirmButton: false,
                        timer: this.$config.notificationTimer ?? 3000,
                    });
                    this.formRepComment.content = '';
                    this.formMediarepComment = {}
                    this.formMediarepComment.file = null;
                });

        },
        toggleEditComment(commentid, commentUrl) {
            if (commentUrl === null) {
                this.isFileDelete = true
            }
            if (this.comment.id === commentid) {
                this.showmodelEditComment = !this.showmodelEditComment
            }
        },
        CancleEditComment() {
            this.selectedFrientEidtComment = null
            this.editerComment = !this.editerComment
            this.editContentComment = this.convertHtmlToUsername(this.comment.content)
        },
        toggleRepComments() {
            this.showAllRepComments = !this.showAllRepComments;
        },
        clearImageRepComment(commentId) {
            this.formMediarepComment = {}
            const input = document.getElementById(`fieldMediaRepCM_${commentId}`);
            if (input) {
                console.log(input.value);
            }
        },
        clickRepComment(refName) {
            this.boxRepComment = true
            this.isRepComment = true
            this.istag = false
            console.log(this.isRepComment);
            this.formRepComment.content = '@' + this.comment.user.user_name + ' ';
            this.$refs[refName].focus();
        },
        focusInput(commentId) {
            this.$refs['repcomment' + commentId].focus();
        },
        handleRepCommentUpdated(updatedRepComment) {
            const index = this.comment.repcomments.findIndex(c => c.id === updatedRepComment.id);
            if (index !== -1) {
                this.comment.repcomments[index].content = updatedRepComment.content;
                this.comment.repcomments[index].url = updatedRepComment.url;
                this.comment.repcomments[index].path = updatedRepComment.path;
                this.comment.repcomments[index].type = updatedRepComment.type;
                this.comment.repcomments[index].created_at = updatedRepComment.created_at;
                this.comment.repcomments[index].updated_at = updatedRepComment.updated_at;
            }
        },
        handleRepCommentDeleted(repcommentId) {
            const index = this.comment.repcomments.findIndex(repcomment => repcomment.id === repcommentId);
            if (index !== -1) {
                this.comment.repcomments.splice(index, 1);
                this.$emit('repcomment-deleted');
            }
        },
        setupCommentWatcher() {
            this.commentWatcher = this.$watch(
                () => this.comment,
                (newFile, oldFile) => {
                    const urlArray = Array.isArray(newFile) ? newFile : [newFile];
                    this.fileUrls = [
                        ...this.fileUrls,
                        ...urlArray.map(im => ({
                            id: im.id,
                            content: im.content,
                            type: im.type,
                            url: im.url,
                            isProp: true,
                            deleted: false
                        }))
                    ];
                },
                { immediate: true, deep: true }
            );
        },
        resetCommentWatcher() {
            if (this.commentWatcher) {
                this.commentWatcher();
            }
            this.commentWatcher = this.$watch(
                () => this.comment,
                (newFile, oldFile) => {
                    const urlArray = Array.isArray(newFile) ? newFile : [newFile];
                    this.fileUrls = urlArray.map(im => ({
                        id: im.id,
                        content: im.content,
                        type: im.type,
                        url: im.url,
                        isProp: true,
                        deleted: false
                    }));
                },
                { immediate: true, deep: true }
            );
        },
        CusRedirect(event) {
            const url = event.target.getAttribute('href');
            if (url) {
                this.$router.push(url);
            }
        },
        convertIdsToUsernames(content) {
            if (content) {
                return content.replace(/<a href='\/profile\/(\d+)' class='custom-span'>(\d+)<\/a>/g, (match, accountId) => {
                    const account = this.getUserById(accountId);
                    if (account) {
                        return `<a href='/profile/${account.user_id}' class='custom-span'>${account.user_name}</a>`;
                    }
                    return match;
                });
            }
        },
        getUserById(accountId) {
            return this.accounts.find(account => account.user_id == accountId);
        },
        convertHtmlToUsername(html) {
            if (html) {
                const regex = /<a href='\/profile\/(\d+)' class='custom-span'>(\d+)<\/a>/;
                const match = html.match(regex);
                if (match) {
                    const userId = match[1];
                    const account = this.getUserById(userId)
                    if (account && account.user_name) {
                        this.selectedFrientEidtComment = account;
                        const username = account.user_name;
                        return html.replace(regex, `@${username}`);
                    } else {
                        console.error(`User with ID ${userId} not found or invalid account structure.`);
                        return html;
                    }
                }

                return html;
            }
        },
        onInput(id, event) {
            const textAreaComment = this.$refs['comment' + id]
            textAreaComment.style.height = 'auto';
            textAreaComment.style.height = `${textAreaComment.scrollHeight}px`;
            const text = event.target.value;
            const position = event.target.selectionStart;
            const match = text.substring(0, position).match(/@(\S*)$/);
            if (match) {
                const query = diacritics.remove(match[1].toLowerCase());
                this.filteredFriends = this.friends.filter(
                    friend =>
                        diacritics.remove(friend.user.user_name.toLowerCase()).includes(query)
                );
                this.showSuggestions = true;
            } else {
                this.showSuggestions = false;
                console.log('lỗi nè');
            }
        },
        _onInput(id, event) {
            const textAreaRepComment = this.$refs['repcomment' + id]
            textAreaRepComment.style.height = 'auto';
            textAreaRepComment.style.height = `${textAreaRepComment.scrollHeight}px`;
            const text = event.target.value;
            const position = event.target.selectionStart;
            const match = text.substring(0, position).match(/@(\S*)$/);
            if (match) {
                const query = diacritics.remove(match[1].toLowerCase());
                this.filteredFriends = this.friends.filter(
                    friend =>
                        diacritics.remove(friend.user.user_name.toLowerCase()).includes(query)
                );
                this.showSuggestions = true;
            } else {
                this.showSuggestions = false;
            }
        },
        _selectFriend(friend, id) {
            this.isRepComment = false
            this.istag = true
            console.log(this.istag);
            console.log(this.isRepComment);
            this.selectedFrientCreatedComment = friend;
            console.log(this.selectedFrientCreatedComment.user_name);
            console.log(this.selectedFrientCreatedComment);
            const textArea = this.$refs['repcomment' + id];
            const position = textArea.selectionStart;

            const text = textArea.value.substring(0, position);
            const mentionStart = text.lastIndexOf('@');
            const textBefore = textArea.value.substring(0, mentionStart + 1);
            const textAfter = textArea.value.substring(position);
            this.formRepComment.content = `@${friend.user_name} `;
            this.showSuggestions = false;
            textArea.focus();
        },
        selectFriend(friend, id) {
            this.selectedFrientEidtComment = friend;
            const textArea = this.$refs['comment' + id];
            const position = textArea.selectionStart;

            const text = textArea.value.substring(0, position);
            const mentionStart = text.lastIndexOf('@');
            const textBefore = textArea.value.substring(0, mentionStart);
            const textAfter = textArea.value.substring(position);

            this.editContentComment = `@${friend.user_name} `;
            this.showSuggestions = false;

            textArea.focus();
        },
    },
    mounted() {
        this.contentComment = this.convertIdsToUsernames(this.rawContent)
        this.editContentComment = this.convertHtmlToUsername(this.editContentComment);
    },
    watch: {
        'comment.content'(newValue) {
            this.contentComment = this.convertIdsToUsernames(newValue)
            this.editContentComment = this.convertHtmlToUsername(newValue);
        },
    },

    created() {
        this.setupCommentWatcher();
        this.friends = this.friendsWithUsers
    },
}
</script>