<script setup>
import Image from 'vue-material-design-icons/Image.vue'
import AccountMultiple from 'vue-material-design-icons/AccountMultiple.vue';
import ThumbUp from 'vue-material-design-icons/ThumbUp.vue';
import Check from 'vue-material-design-icons/Check.vue';
import Delete from 'vue-material-design-icons/Delete.vue';
import Close from 'vue-material-design-icons/Close.vue'

</script>
<template>
    <div class="box-comment-cus">
        <div class="box-comment-cus_2">
            <div class="box-comment-cus_3">
                <div class="" v-if="!editerComment">
                    <div class="d-flex gap-2 align-items-start w-100 mb-1">
                        <router-link :to="{ name: 'Profile User', params: { id: comment.user.user_id } }" class="mr-2">
                            <img class="rounded-full ml-1 img-cus" :src="comment.user.avatar" alt="">
                        </router-link>
                        <div class="bg-EFF2F5 p-2 rounded-lg w-50">
                            <div class="d-flex gap-2 justify-content-between">
                                <router-link :to="{ name: 'Profile User', params: { id: comment.user.user_id } }"
                                    class="m-0">{{
                                        comment.user.user_name }}</router-link>
                                <span @click="toggleEditComment(comment.id)" v-if="comment.user.id === authUser.id"
                                    class="ellipsis" style="margin-top: -10px;"><i
                                        class="fa-solid fa-ellipsis fs-5"></i></span>
                            </div>
                            <div class="d-flex align-items-center text-xs rounded-lg w-100">
                                {{ comment.content }}
                            </div>
                        </div>
                    </div>
                    <div class="w-100 position-relative" style="margin-left: 50px;">
                        <img width="150" @click="isFileDisplay = comment.url"
                            v-if="comment.type != null && comment.type.includes('image')" :src="comment.url"
                            alt="Image">
                        <video width="150" @click="isFileDisplay = comment.url"
                            v-else-if="comment.type != null && comment.type.includes('video')" :src="comment.url"
                            controls autoplay muted></video>
                    </div>
                </div>
                <div class="" v-if="editerComment">
                    <div id="CreateComment" class="">
                        <form @submit.prevent="EditComment(comment.id)"
                            class="d-flex align-items-center pt-2 justify-content-between w-100">
                            <a href="/" class="mx-2">
                                <img class="rounded-full ml-1 img-cus" :src="comment.user.avatar" loading="lazy" alt="">
                            </a>
                            <div class="d-flex align-items-center bg-EFF2F5 rounded-full w-100  px-2">
                                <textarea v-model="editContentComment" type="text" placeholder="Viết bình luận ..."
                                    class="custom-input w-100 focus-0 border-0 mx-1 border-none p-0 text-sm bg-EFF2F5 placeholder-[#64676B] ring-0 focus:ring-0">
                                </textarea>
                                <label class="hover-200 rounded-full p-2 custom-cursor-pointer"
                                    :for="'fileCommentEdit' + comment.id">
                                    <Image :size="27" fillColor="#43BE62" />
                                </label>
                                <input :ref="'fieldMedia' + comment.id" type="file" class="hidden"
                                    :id="'fileCommentEdit' + comment.id" accept="image/*,video/*"
                                    @input="handleFileChange($event)">
                                <button type="submit"
                                    class="d-flex border-0 align-items-center text-sm px-3 rounded-full bg-blue-500 hover:bg-blue-600 text-white font-bold">
                                    <Check />Gửi
                                </button>
                            </div>
                        </form>
                    </div>
                    <div v-if="comment.url" class="p-2 position-relative cus-img-dis" style="margin-left: 55px;">
                        <Close @click="clearImage(post.id)"
                            class="position-absolute bg-white p-1 m-2 right-2 z-1000 rounded-full border custom-cursor-pointer"
                            :size="22" fillColor="#5E6771" />
                        <div v-if="comment.type.includes('image')"><img class="rounded-lg mx-auto w-50"
                                :src="comment.url" loading="lazy" alt=""></div>
                        <div v-else-if="comment.type.includes('video')">
                            <video class="rounded-lg mx-auto w-50" controls>
                                <source :src="comment.url" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        <div v-else>
                            <a href="comment.url">{{ comment.url }}</a>
                        </div>
                    </div>
                    <p style="text-decoration: underline; margin-left: 60px; color: blue; cursor: pointer;"
                        v-if="editerComment" @click="editerComment = !editerComment">Huỷ</p>
                </div>
                <div v-if="!editerComment" id="bottom-cus">
                    <p class="custom-cursor-pointer">{{ comment.created_at_formatted }}</p>
                    <p class="custom-cursor-pointer">like</p>
                    <p @click="clickRepComment('repcomment' + comment.id)" class="custom-cursor-pointer">Phản hồi</p>
                </div>
                <div v-if="comment.repcomments.length > 0" style="background: white;" class="repComment_array"
                    id="repComment">
                    <div v-if="!showAllRepComments" class="text-center mb-1">
                        <button class="px-2" @click="toggleRepComments">Xem tất cả {{ repcomment_count }} phản
                            hồi</button>
                    </div>
                    <div v-if="showAllRepComments" class="mx-5 repcomment_list boxrepcomment-cus"
                        v-for="(repcomment, index) in comment.repcomments" :key="index">
                        <div class="d-flex gap-2 align-items-start w-100 mb-1">
                            <router-link :to="{ name: 'Profile User', params: { id: repcomment.user.user_id } }"
                                class="mr-2">
                                <img class="rounded-full ml-1 img-cus" :src="repcomment.user.avatar" alt="">
                            </router-link>
                            <div class="bg-EFF2F5 p-2 w-50 rounded-lg">
                                <div class="d-flex gap-2 justify-content-between">
                                    <router-link :to="{ name: 'Profile User', params: { id: repcomment.user.user_id } }"
                                        class="m-0">{{ repcomment.user.user_name }}</router-link>
                                    <span v-if="repcomment.user.id === authUser.id" class="ellipsis"
                                        style="margin-top: -10px;"><i class="fa-solid fa-ellipsis fs-5"></i></span>
                                </div>
                                <div class="d-flex align-items-center text-xs rounded-lg w-100">
                                    <span @click.prevent="CusRedirect" v-html="repcomment.content"></span>
                                </div>
                            </div>
                        </div>
                        <div class="w-100 position-relative" style="margin-left: 50px;">
                            <img width="150" v-if="repcomment.type != null && repcomment.type.includes('image')"
                                @click="isFileDisplay = repcomment.url" :src="repcomment.url" alt="Image">
                            <video width="150" v-else-if="repcomment.type != null && repcomment.type.includes('video')"
                                @click="isFileDisplay = repcomment.url" :src="repcomment.url" controls autoplay
                                muted></video>
                        </div>
                        <div id="bottom-cus">
                            <p class="custom-cursor-pointer">{{ repcomment.created_at_formatted }}</p>
                            <p class="custom-cursor-pointer">like</p>
                            <p @click="clickRepComment2(index, 'repcomment' + comment.id)"
                                class="custom-cursor-pointer">
                                Phản
                                hồi
                            </p>
                        </div>
                    </div>
                    <div v-if="showAllRepComments" class="text-center mb-1">
                        <button @click="toggleRepComments">Ẩn bớt</button>
                    </div>
                </div>
                <form style="background: white;margin-left:46px ;" @submit.prevent="CreateRepComment(comment.id)"
                    class="d-flex align-items-center rounded-full justify-content-between w-100">
                    <a href="/" class="mr-2">
                        <img class="rounded-full ml-1 img-cus" :src="authUser.avatar" alt="">
                    </a>
                    <div class="d-flex align-items-center bg-EFF2F5 rounded-full px-2 w-100">
                        <textarea v-model="formRepComment.content" :id="'repcomment' + comment.id"
                            :ref="'repcomment' + comment.id" type="text"
                            :placeholder="`Viết phản hồi với vai trò ${authUser.user_name}...`"
                            class="custom-input bg-EFF2F5 w-100 border-0 mx-1 border-none p-0 text-sm placeholder-[#64676B] focus-0">
                                        </textarea>
                        <label :for="`fieldMediaRepCM_${comment.id}`"
                            class="hover-200 rounded-full p-2 custom-cursor-pointer">
                            <Image :size="27" fillColor="#43BE62" />
                        </label>
                        <input :ref="`fieldMediaRepCM_${comment.id}`" type="file" accept="image/*,video/*"
                            class="hidden" :id="`fieldMediaRepCM_${comment.id}`"
                            @change="getUploadedImageRepComment($event)">
                        <button type="submit"
                            class="d-flex border-0 align-items-center text-sm px-3 rounded-full bg-blue-500 hover:bg-blue-600 text-white font-bold">
                            <Check />Gửi
                        </button>
                    </div>
                </form>
                <div style="margin-left: 60px;" v-if="formMediarepComment.url"
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
                <div v-if="comment.user.id === authUser.id && showmodelEditComment" class="edit_comment"
                    style="position: absolute;top: 0;">
                    <ul @click="toggleEditComment(comment.id)">
                        <li @click="editerComment = !editerComment">chỉnh sửa</li>
                        <li>xoá</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { toRefs, reactive, ref } from 'vue';
import { useGeneralStore } from '../../../store/general';
import { storeToRefs } from 'pinia';
import { param } from 'jquery';
export default {
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
            isFileDisplay,
            formRepComment: { content: '' },
            formMediarepComment: {},
            boxRepComment: reactive(false),
            isRepComment: true,
            selectedRepCommentIndex: null,
            showAllRepComments: false,
            showmodelEditComment: false,
            editerComment: false,
            editContentComment: this.comment.content,
            file: null,
        }
    },
    computed: {
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },

    },
    methods: {
        CusRedirect(event) {
            const url = event.target.getAttribute('href');
            if (url) {
                this.$router.push(url);
            }
        },
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
        handleFileChange(event) {
            this.file = event.target.files[0];
        },
        EditComment(commentId) {
            const formData = new FormData();
            formData.append('content', this.editContentComment);
            if (this.file) {
                formData.append('file', this.file);
            }
            this.$store.dispatch('post/editComment', { commentId: commentId, formData: formData })
                .then(response => {
                    this.editerComment = false
                    console.log('bình luận được cập nhật thành công');
                    this.$emit('comment-updated', response.data.comment);
                    this.$swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: `${response.data.message}`,
                        showConfirmButton: false,
                        timer: this.$config.notificationTimer ?? 3000,
                    });
                })
                .catch(error => {
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
        CreateRepComment(commentId) {
            const formData = new FormData();
            let content = this.formRepComment.content
            if (this.isRepComment) {
                content = content.replace('@' + this.comment.user.user_name,
                    "<a href='/profile/"
                    + this.comment.user.user_id + "' class='custom-span'>" + this.comment.user.user_name + "</a>")
            }
            else {
                content = content.replace('@' + this.comment.repcomments[this.selectedRepCommentIndex].user.user_name,
                    "<a href='/profile/"
                    + this.comment.repcomments[this.selectedRepCommentIndex].user.user_id + "' class='custom-span'>" + this.comment.repcomments[this.selectedRepCommentIndex].user.user_name + "</a>")
            }
            formData.append('content', content);
            formData.append('file', this.formMediarepComment.file);
            this.$store.dispatch('post/createRepComment', { commentId: commentId, formData: formData })
                .then(response => {
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
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                        this.formRepComment.content = '';
                        this.formMediarepComment = {}
                        this.formMediarepComment.file = null;
                        this.isRepComment = false
                    }
                })
                .catch(error => {
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
                    this.isRepComment = false

                });

        },
        toggleEditComment(commentid) {
            if (this.comment.id === commentid) {
                this.showmodelEditComment = !this.showmodelEditComment
            }
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
            console.log(this.isRepComment);
            this.formRepComment.content = '@' + this.comment.user.user_name + ' ';
            this.$refs[refName].focus();
        },
        clickRepComment2(index, refName) {
            this.selectedRepCommentIndex = index
            this.boxRepComment = true
            this.isRepComment = false
            console.log(this.isRepComment);
            this.formRepComment.content = '@' + this.comment.repcomments[index].user.user_name + ' '
            this.$refs[refName].focus();
        },
    }
}
</script>