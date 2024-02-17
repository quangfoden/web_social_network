<script setup>
import Image from 'vue-material-design-icons/Image.vue'
import AccountMultiple from 'vue-material-design-icons/AccountMultiple.vue';
import ThumbUp from 'vue-material-design-icons/ThumbUp.vue';
import Check from 'vue-material-design-icons/Check.vue';
import Delete from 'vue-material-design-icons/Delete.vue';
import Close from 'vue-material-design-icons/Close.vue'

</script>
<template>
    <div id="post" class="pb-2">
        <div class="d-flex align-items-center py-3 px-0">
            <a class="mr-2">
                <img class="img-cus" :src="user.avatar" alt="">
            </a>
            <div class="d-flex align-items-center justify-content-between p-2 rounded-full w-100">
                <div>
                    <div class="text-pr">{{ user.user_name }}</div>
                    <div class="d-flex align-items-center text-xs text-gray-600">
                        {{ post.created_at_formatted }}
                        <i v-if="post.privacy === 'public'" class="mx-2 fas fa-globe"></i>
                        <i v-if="post.privacy === 'friends'" class="mx-2 fas fa-user-friends"></i>
                        <i v-if="post.privacy === 'only_me'" class="mx-2 fas fa-lock"></i>
                    </div>
                </div>
            </div>
            <!-- <div class="d-flex align-items-center">
                <a class="rounded-full p-1 custom-cursor-pointer">
                    <Delete :size="20" fillColor="#64676B" />
                </a>
            </div> -->
        </div>
        <div class="px-1 pb-2 text-cus-pos">
            {{ post.content }}
        </div>
        <div class="cus-post-media">
            <div class="list_item_media" v-for="medias in media" :key="media.id">
                <div v-if="medias.type === 'image'">
                    <img @click="isFileDisplay = medias.path" :src="medias.path" alt="Image"
                        class="mx-auto custom-cursor-pointer w-100">
                </div>
                <div class="" v-else-if="medias.type === 'video'">
                    <video @click="isFileDisplay = medias.path" :src="medias.path"
                        class="mx-auto custom-cursor-pointer w-100">
                    </video>
                </div>
            </div>
        </div>
        <div id="Likes" class="">
            <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                <ThumbUp :size="16" fillColor="#1D72E2" />
                <div class="text-sm text-gray-600 font-semibold">5 bình luận</div>
            </div>
        </div>
        <div id="comments" class="">
            <div id="CreateComment" class="">
                <form @submit.prevent="CreateComment()"
                    class="d-flex align-items-center pt-2 justify-content-between w-100">
                    <a href="/" class="mx-2">
                        <img class="rounded-full ml-1 img-cus" :src="authUser.avatar" alt="">
                    </a>
                    <div class="d-flex align-items-center bg-EFF2F5 rounded-full w-100  px-2">
                        <textarea v-model="formComment.content" type="text" placeholder="Viết bình luận ..."
                            class="custom-input w-100 focus-0 border-0 mx-1 border-none p-0 text-sm bg-EFF2F5 placeholder-[#64676B] ring-0 focus:ring-0">
                        </textarea>
                        <label class="hover-200 rounded-full p-2 custom-cursor-pointer" for="image">
                            <Image :size="27" fillColor="#43BE62" />
                        </label>
                        <input ref="fieldMedia" type="file" class="" id="image" accept="image/*,video/*"
                            @input="getUploadedImage($event)">
                        <button type="submit"
                            class="d-flex border-0 align-items-center text-sm px-3 rounded-full bg-blue-500 hover:bg-blue-600 text-white font-bold">
                            <Check />Gửi
                        </button>
                    </div>
                </form>
            </div>
            <div v-if="formMediaComment.url" class="p-2 position-relative cus-img-dis">
                <Close @click="clearImage()"
                    class="position-absolute bg-white p-1 m-2 right-2 z-1000 rounded-full border custom-cursor-pointer"
                    :size="22" fillColor="#5E6771" />
                <div v-if="formMediaComment.type === 'image'"><img class="rounded-lg mx-auto w-50"
                        :src="formMediaComment.url" alt=""></div>
                <div v-if="formMediaComment.type === 'video'"> <video class="rounded-lg mx-auto w-50" controls>
                        <source :src="formMediaComment.url" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
            <div v-if="comments.length > 0" id="Comment" class="comment_array">
                <div class="my-1 comment_list" v-for="(comment, index) in comments" :key="comment.id">
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
                                    <img width="150" v-if="comment.type != null && comment.type.includes('image')"
                                        :src="comment.path" alt="Image">
                                    <video width="150" v-else-if="comment.type != null && comment.type.includes('video')"
                                        :src="comment.path" controls></video>
                                </div>
                                <div id="bottom-cus">
                                    <p class="custom-cursor-pointer">{{ comment.created_at_formatted }}</p>
                                    <p class="custom-cursor-pointer">like</p>
                                    <p @click="clickRepComment(index)" class="custom-cursor-pointer">Phản hồi</p>
                                </div>
                                <!-- <div v-if="comment.repcomments.length > 0" style="background: white;"
                                    class="repComment_array" id="repComment">
                                    <div class="mx-5 repcomment_list boxrepcomment-cus"
                                        v-for="(repcomment, index2) in comment.repcomments" :key="index2">
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
                                            <img width="150"
                                                v-if="repcomment.type != null && repcomment.type.includes('image')"
                                                :src="repcomment.path" alt="Image">
                                            <video width="150"
                                                v-else-if="repcomment.type != null && repcomment.type.includes('video')"
                                                :src="repcomment.path" controls></video>
                                        </div>
                                        <div id="bottom-cus">
                                            <p class="custom-cursor-pointer">{{ repcomment.created_at_formatted }}</p>
                                            <p class="custom-cursor-pointer">like</p>
                                            <p @click="clickRepComment2(index, index2)" class="custom-cursor-pointer">Phản
                                                hồi
                                            </p>
                                        </div>
                                    </div>
                                    <a href="#" class="text-center w-100" id="loadMoreRepComment"
                                        style="text-decoration:underline !important;color: black;font-weight: 500;">Xem
                                        thêm</a>
                                </div> -->
                                <!-- <form style="background: white;margin-left:46px ;"
                                    @submit.prevent="CreateRepComment(comment.id, index)"
                                    class="d-flex align-items-center rounded-full justify-content-between w-100">
                                    <a href="/" class="mr-2">
                                        <img class="rounded-full ml-1 img-cus" :src="authUser.avatar" alt="">
                                    </a>
                                    <div class="d-flex align-items-center bg-EFF2F5 rounded-full px-2 w-100">
<<<<<<< HEAD
                                        <textarea v-model="formRepComment[index].content" :id="'repcomment' + comment.id + index"
                                            type="text" placeholder="Viết phản hồi ..."
=======
                                        <textarea v-model="formRepComment[index].content"
                                            :id="'repcomment' + comment.id + index" type="text"
                                            placeholder="Viết phản hồi ..."
>>>>>>> af992cf33e76e35da7ae4f94b05e490849d46e8f
                                            class="custom-input bg-EFF2F5 w-100 border-0 mx-1 border-none p-0 text-sm placeholder-[#64676B] focus-0">
                                        </textarea>
                                        <label class="hover-200 rounded-full p-2 custom-cursor-pointer">
                                            <Image :size="27" fillColor="#43BE62" />
                                        </label>
                                        <input ref="fieldMediaRepCM" type="file" accept="image/*,video/*" class=""
                                            :id="`fieldMediaRepCM_${index}`"
                                            @change="getUploadedImageComment($event, index)">
                                        <button type="submit"
                                            class="d-flex border-0 align-items-center text-sm px-3 rounded-full bg-blue-500 hover:bg-blue-600 text-white font-bold">
                                            <Check />Gửi
                                        </button>
                                    </div>
                                </form> -->
                                <!-- <div style="margin-left: 60px;" v-if="formMediarepComment[index].url"
                                    class="p-2 position-relative cus-img-dis">
                                    <Close @click="clearImageRepComment(index)"
                                        class="position-absolute bg-white p-1 m-2 right-2 z-1000 rounded-full border custom-cursor-pointer"
                                        :size="22" fillColor="#5E6771" />
                                    <div v-if="formMediarepComment[index].type === 'image'"><img
                                            class="rounded-lg mx-auto w-50" :src="formMediarepComment[index].url" alt="">
                                    </div>
                                    <div v-if="formMediarepComment[index].type === 'video'"> <video
                                            class="rounded-lg mx-auto w-50" controls>
                                            <source :src="formMediarepComment[index].url" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
                <a href="#" id="loadMore" style="text-decoration:underline !important;color: black;font-weight: 500;">Xem
                    tất cả bình
                    luận</a>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState, mapActions } from 'vuex';
import { toRefs, reactive, ref } from 'vue';

import { useGeneralStore } from '../../../store/general';
import { storeToRefs } from 'pinia';

export default {
    props: {
        comments: {
            type: Object,
            required: true,
        },
        post: {
            type: Object,
            required: true,
        },
        user: {
            type: Object,
            required: true,
        },
        media: {
            type: Object,
            required: true,
        },
        // repcomments: {
        //     type: Object,
        //     required: true,
        // },
    },

    data() {
        const useGeneral = useGeneralStore()
        const { isFileDisplay } = storeToRefs(useGeneral)
        return {
            isFileDisplay,
            formComment: reactive({
                content: ''
            }),
            // listComments: ref(this.comments),
            formMediaComment: reactive({

            }),
            formRepComment: this.comments.map(() => reactive({ content: '' })),
            formMediarepComment: this.comments.map(() => reactive({})),
            boxRepComment: this.comments.map(() => reactive(false)),
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
    mounted() {
        this.loadMoreComments()
        this.loadMoreRepComments()
        // console.log(this.comments)
    },
    methods: {
        ...mapActions('post', ['fetchPosts']),
        getUploadedImage(e) {
            const file = e.target.files[0];
            let mediaType;
            if (file.type.startsWith('image/')) {
                mediaType = 'image';
            } else if (file.type.startsWith('video/')) {
                mediaType = 'video';
            }
            const url = URL.createObjectURL(file);
            // this.formMediaComment.push({ type: mediaType, file, url });
            this.formMediaComment.type = mediaType;
            this.formMediaComment.url = url;
       
        },
        getUploadedImageComment(e, index) {
            const file = e.target.files[0];
            let mediaType;
            if (file.type.startsWith('image/')) {
                mediaType = 'image';
            } else if (file.type.startsWith('video/')) {
                mediaType = 'video';
            }
            const url = URL.createObjectURL(file);
            this.formMediarepComment[index].type = mediaType;
            this.formMediarepComment[index].url = url;
            this.formMediarepComment[index].file = file;
        },
        clearImage() {
            this.formMediaComment = {};
            this.$refs.fieldMedia.value = null
        },
        clearImageRepComment(index) {
            this.formRepComment[index] = {};
            this.formMediarepComment[index] = {}
            this.$refs.fieldMediaRepCM.value = null
            // Clear the input value for the specific iteration
            const input = document.getElementById(`fieldMediaRepCM_${index}`);
            if (input) {
                input.value = null;
            }
        },

        CreateComment() {
            const fieldMediaCMRef = this.$refs['fieldMedia']
            const formData = new FormData();
            formData.append('content', this.formComment.content);
            formData.append('file', fieldMediaCMRef.files[0]);
            axios.post(`api/user/create_comment/${this.post.id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            })
                .then(response => {
                    if (response.status === 200 && response.data.data.success === true) {
                        this.$swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Comment thành công !",
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                        this.formMediaComment = {};
                        this.formComment.content = null
                        this.$refs['fieldMedia'].value = null
                      
                    } else {
                        this.$swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "Comment không thành công !",
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
        CreateRepComment(commentId, index) {
            const formData = new FormData();
            formData.append('content', this.formRepComment[index].content);
            formData.append('file', this.formMediarepComment[index].file);
            axios.post(`api/user/create_rep_comment/${commentId}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            })
                .then(response => {
                    if (response.status === 200 && response.data.data.success === true) {
                        this.fetchPosts()
                        const input = document.getElementById(`fieldMediaRepCM_${index}`);
                        if (input) {
                            input.value = null;
                        }
                        this.$swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Rep Comment thành công !",
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                        this.formRepComment[index] = {}
                        this.formMediarepComment[index] = {};
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
        clickRepComment(index) {
            this.boxRepComment[index] = true
            this.formRepComment[index].content = this.comments[index].user.user_name
        },
        clickRepComment2(index, index2) {
            this.boxRepComment[index] = true
            this.formRepComment[index].content = this.comments[index].repcomments[index2].user.user_name
        },
       
        loadMoreComments() {
            $(document).ready(function () {
                $(".comment_array").each(function () {
                    var $commentArray = $(this);
                    var $commentList = $commentArray.find(".comment_list");
                    $commentList.slice(0, 1).show();
                    $commentArray.find("#loadMore").on("click", function (e) {
                        e.preventDefault();
                        var $hiddenComments = $commentList.filter(":hidden").slice(0, 3);
                        $hiddenComments.slideDown();
                        if ($hiddenComments.length === 0) {
                            $(this).addClass("noContent");
                        }
                    });
                });
            });
        },
        loadMoreRepComments() {
            $(document).ready(function () {
                $(".repComment_array").each(function () {
                    var $RepcommentArray = $(this);
                    var $RepcommentList = $RepcommentArray.find(".repcomment_list");
                    $RepcommentList.slice(0, 1).show();
                    $RepcommentArray.find("#loadMoreRepComment").on("click", function (e) {
                        e.preventDefault();
                        var $hiddenRepComments = $RepcommentList.filter(":hidden").slice(0, 3);
                        $hiddenRepComments.slideDown();
                        if ($hiddenRepComments.length === 0) {
                            $(this).addClass("noContent");
                        }
                    });
                });
            });
        },

    },

}
</script>