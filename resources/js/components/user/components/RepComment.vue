<script setup>
import Image from 'vue-material-design-icons/Image.vue'
import Send from 'vue-material-design-icons/Send.vue';
import Undo from 'vue-material-design-icons/Undo.vue'
import Close from 'vue-material-design-icons/Close.vue'

</script>
<template>
    <div v-if="!editerRepComment" class="position-relative">
        <div class="d-flex gap-2 align-items-start w-100 mb-1">
            <router-link :to="{ name: 'Profile User', params: { id: repcomment.user.user_id } }" class="mr-2">
                <img class="rounded-full ml-1 img-cus" :src="repcomment.user.avatar" alt="">
            </router-link>
            <div class="bg-input p-2 w-50 rounded-lg">
                <div class="d-flex gap-2 justify-content-between">
                    <router-link :to="{ name: 'Profile User', params: { id: repcomment.user.user_id } }"
                        class="m-0 primary-text">{{
                            repcomment.user.user_name }}</router-link>
                    <span @click="toggleEditRepComment(repcomment.id, repcomment.url)"
                        v-if="repcomment.user.id === authUser.id" class="ellipsis" style="margin-top: -10px;"><i
                            class="secondary-text fa-solid fa-ellipsis fs-5"></i></span>
                </div>
                <div class="primary-text d-flex align-items-center text-xs rounded-lg w-100">
                    <span class="content_repcomment" @click.prevent="CusRedirect" v-html="contentRepComment"></span>
                </div>
            </div>
        </div>
        <div class="w-100 position-relative" style="margin-left: 50px;">
            <img width="150" v-if="repcomment.type != null && repcomment.type.includes('image')"
                @click="isFileDisplay = repcomment.url" :src="repcomment.url" alt="Image">
            <video width="150" v-else-if="repcomment.type != null && repcomment.type.includes('video')"
                @click="isFileDisplay = repcomment.url" :src="repcomment.url" controls autoplay muted></video>
        </div>
        <div id="bottom-cus">
            <p class="custom-cursor-pointer secondary-text">{{ repcomment.created_at_formatted }}</p>
            <p class="custom-cursor-pointer secondary-text">like</p>
            <p @click="clickRepComment2()" class="custom-cursor-pointer secondary-text">
                Phản
                hồi
            </p>
        </div>
        <div v-if="repcomment.user.id === authUser.id && showmodelEditRepComment" class="edit_repcomment"
            style="position: absolute;top: 0;">
            <ul @click="toggleEditRepComment(repcomment.id, repcomment.url)">
                <li @click="editerRepComment = !editerRepComment">chỉnh sửa</li>
                <li @click="confirmDeleteComment(comment.id)">xoá</li>
            </ul>
        </div>
    </div>
    <div v-if="editerRepComment" class="mb-4">
        <div id="EditRepComment" class="">
            <form @submit.prevent="EditComment(comment.id)"
                class="d-flex align-items-center pt-2 justify-content-between w-100">
                <a href="/" class="mr-2">
                    <img class="rounded-full ml-1 img-cus" :src="repcomment.user.avatar" loading="lazy" alt="">
                </a>
                <div class="position-relative d-flex align-items-center bg-input w-100 rounded px-2">
                    <textarea style="min-height: 100px;" @input="adjustHeight(repcomment.id)"
                        :ref="'textarea' + repcomment.id" v-model="editContentRepComment" type="text"
                        placeholder="Viết bình luận ..."
                        class="primary-text custom-input w-100 focus-0 border-0 mx-1 border-none p-0 text-sm bg-input placeholder-[#64676B] ring-0 focus:ring-0">
                                </textarea>
                    <div classs="px-3" style="width: 100px;">
                        <div class="position-absolute" style="right:50px ;bottom: 0;" v-if="repcomment.url != null">
                            <label :class="{ 'no-click opacity-50': !isFileRepDelete || FileRepUrl.length >= 2 }"
                                class="hover-200 rounded-full p-2 custom-cursor-pointer"
                                :for="'fileRepCommentEdit' + repcomment.id">
                                <Image :size="27" fillColor="#43BE62" />
                            </label>
                            <input :ref="'fieldMedia' + repcomment.id" type="file" class="hidden"
                                :id="'fileRepCommentEdit' + repcomment.id" accept="image/*,video/*"
                                @input="handleFileRepChange($event)">
                        </div>
                        <div style="right: 50px; bottom: 0;" class="position-absolute" v-else>
                            <label :class="{ 'no-click opacity-50': !isFileRepDelete }"
                                class="hover-200 rounded-full p-2 custom-cursor-pointer"
                                :for="'fileRepCommentEdit' + repcomment.id">
                                <Image :size="27" fillColor="#43BE62" />
                            </label>
                            <input :ref="'fieldMedia' + repcomment.id" type="file" class="hidden"
                                :id="'fileRepCommentEdit' + repcomment.id" accept="image/*,video/*"
                                @input="handleFileRepChange($event)">
                        </div>
                        <button style="right: 0; bottom: 0;" type="submit"
                            class="position-absolute d-flex border-0 align-items-center bg-transparent text-sm p-2 rounded-full text-white font-bold">
                            <Send :size="27" fillColor="#4299e1" />
                        </button>
                    </div>
                </div>
            </form>
            <div v-if="FileRepUrl" class="pt-2 position-relative cus-img-dis" style="margin-left: 55px;">
                <div v-for="fileUrl in FileRepUrl" :key=index>
                    <div v-if="fileUrl.url && fileUrl.url != null">
                        <Undo :class="{ 'no-click': fileUrl.isnew }" v-show="fileUrl.deleted"
                            @click="revertFileRep(fileUrl)"
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
            <p style="text-decoration: underline; display: inline; margin-left: 60px; color: blue; cursor: pointer;"
                v-if="editerRepComment" @click="CancleEditRepComment()">Huỷ</p>
        </div>
    </div>
</template>
<script>
import { ref } from "vue";
import { v4 as uuidv4 } from 'uuid';
import { mapState } from 'vuex';
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
            // isFileRepDelete:false
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
        })
    },

    methods: {
        convertIdsToUsernames(content) {
            return content.replace(/<a href='\/profile\/(\d+)' class='custom-span'>(\d+)<\/a>/g, (match, accountId) => {
                const account = this.getUserById(accountId);
                return `<a href='/profile/${account.user_id}' class='custom-span'>${account.user_name}</a>`;
            });
        },
        getUserById(accountId) {
            return this.accounts.find(account => account.user_id == accountId);
        },
        clickRepComment2() {
            this.$emit('focus-input', this.commentId);
            this.$emit('reply-comment-clicked', {
                selectedRepCommentIndex: this.index,
                boxRepComment: true,
                isRepComment: false,
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
        convertLinksToMentions(html) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;
            const links = tempDiv.getElementsByTagName('a');

            Array.from(links).forEach(link => {
                const account_id = link.textContent;
                const account = this.getUserById(account_id);
                const username = account.user_name
                const textNode = document.createTextNode(`@${username}`);
                link.parentNode.replaceChild(textNode, link);
            });

            return tempDiv.textContent || tempDiv.innerText;
        },
        adjustHeight(repCommentId) {
            const textarea = this.$refs['textarea' + repCommentId];
            textarea.style.height = 'auto';
            textarea.style.height = `${textarea.scrollHeight}px`;
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
        resetCommentWatcher() {
            if (this.repCommentWatcher) {
                this.commentWatcher();
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
                }
            )
        }
    },
    mounted() {
        this.editContentRepComment = this.convertLinksToMentions(this.rawContent)
        this.contentRepComment = this.convertIdsToUsernames(this.rawContent)
        console.log('Danh sách accounts từ Vuex:', this.accounts);
    },
    watch: {
        'repcomment.content'(newValue) {
            this.editContentRepComment = this.convertLinksToMentions(newValue);
        }
    },
    created() {
        this.setupRepCommentWatcher();
    },
}
</script>