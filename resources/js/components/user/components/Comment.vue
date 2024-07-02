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
    <div class="box-comment-cus">
        <div class="box-comment-cus_2">
            <div class="box-comment-cus_3">
                <div class="" v-if="!editerComment">
                    <div class="d-flex gap-2 align-items-start w-100 mb-1">
                        <router-link :to="{ name: 'Profile User', params: { id: comment.user.user_id } }" class="mr-2">
                            <img class="rounded-full ml-1 custom" :src="comment.user.avatar" alt="">
                        </router-link>
                        <div class="bg-input p-2 rounded-lg position-relative" style="max-width:50%">
                            <div class="d-flex align-items-center">
                                <router-link :to="{ name: 'Profile User', params: { id: comment.user.user_id } }"
                                    class="m-0 primary-text">{{
                                        comment.user.user_name }}</router-link>
                            </div>
                            <div class="primary-text d-flex align-items-center text-xs rounded-lg w-100">
                                <span style="word-break: break-all;" class="content_comment"
                                    @click.prevent="CusRedirect" v-html="contentComment">
                                </span>
                            </div>
                        </div>
                        <span @click="toggleEditComment(comment.id, comment.url)" v-if="comment.user.id === authUser.id"
                            class="ellipsis px-2 secondary-text" style="font-size: 1.5rem;"><i
                                class="fa-solid fa-ellipsis fs-5"></i></span>
                        <div v-if="comment.user.id === authUser.id && showmodelEditComment" class="edit_comment">
                            <ul @click="toggleEditComment(comment.id, comment.url)">
                                <li @click="editerComment = !editerComment">chỉnh sửa</li>
                                <li @click="confirmDeleteComment(comment.id)">xoá</li>
                            </ul>
                        </div>
                    </div>
                    <div class="w-100 position-relative" style="padding-left: 50px;">
                        <img width="150" @click="isFileDisplay = comment.url"
                            v-if="comment.type != null && comment.type.includes('image')" :src="comment.url"
                            alt="Image">
                        <video width="150" @click="isFileDisplay = comment.url"
                            v-else-if="comment.type != null && comment.type.includes('video')" :src="comment.url"
                            controls autoplay muted></video>
                    </div>
                </div>
                <div class="mb-3" v-if="editerComment">
                    <div id="EditComment" class="">
                        <form @submit.prevent="EditComment(comment.id)"
                            class="d-flex align-items-center pt-2 justify-content-between w-100">
                            <a href="/" class="mx-2">
                                <img class="rounded-full ml-1 custom" :src="comment.user.avatar" loading="lazy" alt="">
                            </a>
                            <div class="position-relative d-flex align-items-center bg-input rounded w-100 px-2">
                                <textarea :ref="'comment' + comment.id" @input="onInput(comment.id, $event)"
                                    style="min-height: 100px;" v-model="editContentComment" type="text"
                                    placeholder="Viết bình luận ..."
                                    class="primary-text custom-input w-100 focus-0 border-0 mx-1 border-none p-0 text-sm bg-input placeholder-[#64676B] ring-0 focus:ring-0">
                                </textarea>
                                <ul v-show="showSuggestions && filteredFriends.length >= 1"
                                    class="suggestions rounded position-absolute" style="top: 105px;left: 0;">
                                    <li v-for="friend in filteredFriends" :key="friend.id" class="rounded"
                                        @click="selectFriend(friend, comment.id)">
                                        <div class="d-flex gap-2 align-items-center">
                                            <img class="rounded-full ml-1 custom" :src="friend.avatar" alt="">
                                            <p class="primary-text fw-bold mb-0">{{ friend.user_name }}</p>
                                        </div>
                                    </li>
                                </ul>
                                <div class="px-3" style="width: 100px;">
                                    <div class="position-absolute" style="right: 50px; bottom: 0;"
                                        v-if="comment.url != null">
                                        <label v-if="!isSendLoading"
                                            :class="{ 'no-click': !isFileDelete || fileUrls.length >= 2 }"
                                            :disabled="!isFileDelete || fileUrls.length >= 2"
                                            class="hover-200 rounded-full p-2 custom-cursor-pointer"
                                            :for="'fileCommentEdit' + comment.id">
                                            <Image :size="27" fillColor="#43BE62" />
                                        </label>
                                        <input :disabled="!isFileDelete || fileUrls.length >= 2"
                                            :ref="'fieldMedia' + comment.id" type="file" class="hidden"
                                            :id="'fileCommentEdit' + comment.id" accept="image/*,video/*"
                                            @input="handleFileChange($event)">
                                    </div>
                                    <div v-else class="position-absolute" style="right:50px;bottom:0;">
                                        <label v-if="!isSendLoading" :class="{ 'no-click': !isFileDelete }"
                                            :disabled="!isFileDelete"
                                            class="hover-200 rounded-full p-2 custom-cursor-pointer"
                                            :for="'fileCommentEdit' + comment.id">
                                            <Image :size="27" fillColor="#43BE62" />
                                        </label>
                                        <input :disabled="!isFileDelete" :ref="'fieldMedia' + comment.id" type="file"
                                            class="hidden" :id="'fileCommentEdit' + comment.id" accept="image/*,video/*"
                                            @input="handleFileChange($event)">
                                    </div>
                                    <button v-if="!isSendLoading" style="right:0;bottom: 0;" type="submit"
                                        class="position-absolute bg-transparent  d-flex border-0 align-items-center text-sm p-2 rounded-full text-white font-bold">
                                        <Send :size="27" fillColor="#4299e1" />
                                    </button>
                                    <button v-if="isSendLoading" style="right: 0; bottom: 0;"
                                        class="btn position-absolute btn-sm btn-secondary" type="button" disabled>
                                        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                        <span class="visually-hidden" role="status">Loading...</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div v-if="fileUrls" class="pt-2 position-relative cus-img-dis" style="margin-left: 60px;">
                        <div v-for="fileUrl in fileUrls" :key=index>
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
                    <p style="text-decoration: underline; display: inline; margin-left: 60px; color: blue; cursor: pointer;"
                        v-if="editerComment" @click="CancleEditComment()">Huỷ</p>
                </div>
                <div v-if="!editerComment" id="bottom-cus" class="secondary-text">
                    <p class="custom-cursor-pointer secondary-text ">{{ comment.created_at_formatted }}</p>
                    <p class="custom-cursor-pointer secondary-text ">like</p>
                    <p @click="clickRepComment('repcomment' + comment.id)" class="custom-cursor-pointer secondary-text">
                        Phản hồi</p>
                    <p v-if="comment.created_at != comment.updated_at" class="mx-2 secondary-text"
                        style="font-size: 10px;">đã chỉnh sửa</p>
                </div>
                <div v-if="comment.repcomments.length > 0" class="repComment_array" id="repComment">
                    <div v-if="!showAllRepComments" class="text-center mb-1">
                        <button class="px-2 bg-transparent primary-text text-underline" @click="toggleRepComments">Xem
                            tất cả {{ repcomment_count }} phản
                            hồi</button>
                    </div>
                    <div v-if="showAllRepComments" class="mx-5 repcomment_list boxrepcomment-cus"
                        v-for="(repcomment, index) in comment.repcomments" :key="index">
                        <RepComment :repcomment="repcomment" :index=index :commentId=comment.id
                            @repcomment-updated="handleRepCommentUpdated" @repcomment-deleted="handleRepCommentDeleted"
                            @reply-comment-clicked="handleReplyCommentClicked" @focus-input="focusInput" />
                    </div>
                    <div v-if="showAllRepComments" class="text-center mb-1">
                        <button class="bg-transparent primary-text text-underline" @click="toggleRepComments">Ẩn
                            bớt</button>
                    </div>
                </div>
                <form v-if="!editerComment" style="padding-left:46px ;" @submit.prevent="CreateRepComment(comment.id)"
                    class="d-flex align-items-center gap-2 rounded-full justify-content-between w-100">
                    <a href="/" class="mr-2">
                        <img class="rounded-full ml-1 custom" :src="authUser.avatar" alt="">
                    </a>
                    <div class="d-flex align-items-center position-relative bg-input rounded px-2 w-100">
                        <textarea @input="_onInput(comment.id, $event)" v-model="formRepComment.content"
                            :id="'repcomment' + comment.id" :ref="'repcomment' + comment.id" type="text"
                            :placeholder="`Viết phản hồi với vai trò ${authUser.user_name}...`"
                            class="primary-text custom-input bg-input w-100 border-0 mx-1 border-none p-0 text-sm placeholder-[#64676B] focus-0">
                                        </textarea>
                        <ul v-show="showSuggestions && filteredFriends.length >= 1"
                            style="top: -145px;left: 0px;min-height: 130px;"
                            class="suggestions rounded  position-absolute">
                            <li v-for="friend in filteredFriends" :key="friend.id" class="rounded"
                                @click="_selectFriend(friend, comment.id)">
                                <div class="d-flex gap-2 align-items-center">
                                    <img class="rounded-full ml-1 custom" :src="friend.avatar" alt="">
                                    <p class="primary-text fw-bold mb-0">{{ friend.user_name }}</p>
                                </div>
                            </li>
                        </ul>
                        <label v-if="!isSendLoading" :for="`fieldMediaRepCM_${comment.id}`"
                            class="hover-200 rounded-full p-2 custom-cursor-pointer">
                            <Image :size="27" fillColor="#43BE62" />
                        </label>
                        <input :ref="`fieldMediaRepCM_${comment.id}`" type="file" accept="image/*,video/*"
                            class="hidden" :id="`fieldMediaRepCM_${comment.id}`"
                            @input="getUploadedImageRepComment($event)">
                        <button v-if="!isSendLoading" :disabled="isSubmitDisabled"
                            :class="{ 'no-click': isSubmitDisabled }" type="submit"
                            class="d-flex bg-transparent border-0 align-items-center text-sm px-3 rounded-fulltext-white font-bold">
                            <Send :size="27" fillColor="#4299e1" />
                        </button>
                        <button v-if="isSendLoading" class="btn btn-sm btn-secondary" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                            <span class="visually-hidden" role="status">Loading...</span>
                        </button>
                    </div>
                </form>
                <div style="margin-left: 90px;" v-if="formMediarepComment.url"
                    class="p-2 position-relative cus-img-dis">
                    <Close @click="clearImageRepComment(index)"
                        class="position-absolute bg-white p-1 m-2 right-2 z-1000 rounded-full border custom-cursor-pointer"
                        :size="22" fillColor="#5E6771" />
                    <div v-if="formMediarepComment.type === 'image'"><img class="rounded-lg mx-auto w-50"
                            :src="formMediarepComment.url" alt="">
                    </div>
                    <div v-if="formMediarepComment.type === 'video'"> <video class="rounded-lg mx-auto w-50" controls>
                            <source :src="formMediarepComment.url" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
import diacritics from 'diacritics';
import { toRefs, reactive, ref } from 'vue';
import { mapState } from 'vuex';
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
        isSubmitDisabled() {
            return this.formRepComment.content === '' && Object.keys(this.formMediarepComment).length === 0;
        }
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
                        diacritics.remove(friend.user_name.toLowerCase()).includes(query)
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
                        diacritics.remove(friend.user_name.toLowerCase()).includes(query)
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
            console.log(this.selectedFrientEidtComment.user_name);
            console.log(this.selectedFrientEidtComment);
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
        this.friends = this.accounts
        console.log(this.comment.content);
    },
}
</script>