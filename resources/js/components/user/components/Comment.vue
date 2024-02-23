<template>
    <div class="box-comment-cus">
        <div class="box-comment-cus_2">
            <div class="box-comment-cus_3">
                <div class="d-flex gap-2 align-items-start w-100 mb-1">
                    <a href="/" class="mr-2">
                        <img class="rounded-full ml-1 img-cus" :src="comment.user.avatar" alt="">
                    </a>
                    <div class="bg-EFF2F5 p-2 rounded-lg">
                        <h6>{{ comment.user.user_name }}</h6>
                        <div class="d-flex align-items-center text-xs rounded-lg w-100">
                            {{ comment.content }}
                        </div>
                    </div>
                    <!-- <a class="rounded-full p-1.5 ml-2 custom-cursor-pointer">
                                        <Delete fillColor="#64676B" size="20" />
                                    </a> -->
                </div>
                <div class="w-100 position-relative" style="margin-left: 50px;">
                    <img width="150" v-if="comment.type != null && comment.type.includes('image')" :src="comment.path"
                        alt="Image">
                    <video width="150" v-else-if="comment.type != null && comment.type.includes('video')"
                        :src="comment.path" controls></video>
                </div>
                <div id="bottom-cus">
                    <p class="custom-cursor-pointer">{{ comment.created_at_formatted }}</p>
                    <p class="custom-cursor-pointer">like</p>
                    <p @click="clickRepComment()" class="custom-cursor-pointer">Phản hồi</p>
                </div>
                <div v-if="comment.repcomments.length > 0" style="background: white;" class="repComment_array"
                    id="repComment">
                    <div class="mx-5 repcomment_list boxrepcomment-cus" v-for="(repcomment, index) in comment.repcomments"
                        :key="index">
                        <div class="d-flex gap-2 align-items-start w-100 mb-1">
                            <a href="/" class="mr-2">
                                <img class="rounded-full ml-1 img-cus" :src="repcomment.user.avatar" alt="">
                            </a>
                            <div class="bg-EFF2F5 p-2 rounded-lg">
                                <h6>{{ repcomment.user.user_name }}</h6>
                                <div class="d-flex align-items-center text-xs rounded-lg w-100">
                                    {{ repcomment.content }}
                                </div>
                            </div>
                        </div>
                        <div class="w-100 position-relative" style="margin-left: 50px;">
                            <img width="150" v-if="repcomment.type != null && repcomment.type.includes('image')"
                                :src="repcomment.path" alt="Image">
                            <video width="150" v-else-if="repcomment.type != null && repcomment.type.includes('video')"
                                :src="repcomment.path" controls></video>
                        </div>
                        <div id="bottom-cus">
                            <p class="custom-cursor-pointer">{{ repcomment.created_at_formatted }}</p>
                            <p class="custom-cursor-pointer">like</p>
                            <p @click="clickRepComment2(index)" class="custom-cursor-pointer">Phản
                                hồi
                            </p>
                        </div>
                    </div>
                    <a href="#" class="text-center w-100" id="loadMoreRepComment"
                        style="text-decoration:underline !important;color: black;font-weight: 500;">Xem
                        thêm</a>
                </div>
                <form style="background: white;margin-left:46px ;" @submit.prevent="CreateRepComment(comment.id, index)"
                    class="d-flex align-items-center rounded-full justify-content-between w-100">
                    <a href="/" class="mr-2">
                        <img class="rounded-full ml-1 img-cus" :src="authUser.avatar" alt="">
                    </a>
                    <div class="d-flex align-items-center bg-EFF2F5 rounded-full px-2 w-100">
                        <textarea v-model="formRepComment.content" :id="'repcomment' + comment.id + index" type="text"
                            placeholder="Viết phản hồi ..."
                            class="custom-input bg-EFF2F5 w-100 border-0 mx-1 border-none p-0 text-sm placeholder-[#64676B] focus-0">
                                        </textarea>
                        <label class="hover-200 rounded-full p-2 custom-cursor-pointer">
                            <Image :size="27" fillColor="#43BE62" />
                        </label>
                        <input ref="fieldMediaRepCM" type="file" accept="image/*,video/*" class=""
                            :id="`fieldMediaRepCM_${index}`" @change="getUploadedImageComment($event, index)">
                        <button type="submit"
                            class="d-flex border-0 align-items-center text-sm px-3 rounded-full bg-blue-500 hover:bg-blue-600 text-white font-bold">
                            <Check />Gửi
                        </button>
                    </div>
                </form>
                <div style="margin-left: 60px;" v-if="formMediarepComment.url" class="p-2 position-relative cus-img-dis">
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
import { toRefs, reactive, ref } from 'vue';
export default {
    props: {
        comment: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            formRepComment: { content: '' },
            formMediarepComment: {},
            boxRepComment: reactive(false),
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
        getUploadedImageComment(e) {
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
        CreateRepComment(commentId, index) {
            const formData = new FormData();
            formData.append('content', this.formRepComment.content);
            formData.append('file', this.formMediarepComment.file);
            axios.post(`api/user/create_rep_comment/${commentId}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            })
                .then(response => {
                    if (response.status === 200 && response.data.data.success === true) {
                        const input = document.getElementById(`fieldMediaRepCM_${index}`);
                        if (input) {
                            input.value = null;
                        }
                        this.comment.repcomments.push(response.data.data.repcomment)
                        this.$swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Rep Comment thành công !",
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                        this.formRepComment = {}
                        this.formMediarepComment= {};
                        this.$refs.fieldMediaRepCM.value = null
                    }
                    else {
                        this.$swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "Rep Comment không thành công !",
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                        this.formMediaComment = {};
                        this.formComment.content = null
                        this.$refs['fieldMedia'].value = null
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
                });

        },
        clickRepComment() {
            this.boxRepComment= true
            this.formRepComment.content = this.comment.user.user_name
        },
        clickRepComment2(index) {
            this.boxRepComment= true
            this.formRepComment.content = this.comment.repcomments[index].user.user_name
        },
    }
}
</script>