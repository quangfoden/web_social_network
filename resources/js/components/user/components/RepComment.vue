<template>
    <div>
        <div class="d-flex gap-2 align-items-start w-100 mb-1">
            <router-link :to="{ name: 'Profile User', params: { id: repcomment.user.user_id } }" class="mr-2">
                <img class="rounded-full ml-1 img-cus" :src="repcomment.user.avatar" alt="">
            </router-link>
            <div class="bg-EFF2F5 p-2 w-50 rounded-lg">
                <div class="d-flex gap-2 justify-content-between">
                    <router-link :to="{ name: 'Profile User', params: { id: repcomment.user.user_id } }" class="m-0">{{
                        repcomment.user.user_name }}</router-link>
                    <span v-if="repcomment.user.id === authUser.id" class="ellipsis" style="margin-top: -10px;"><i
                            class="fa-solid fa-ellipsis fs-5"></i></span>
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
                @click="isFileDisplay = repcomment.url" :src="repcomment.url" controls autoplay muted></video>
        </div>
        <div id="bottom-cus">
            <p class="custom-cursor-pointer">{{ repcomment.created_at_formatted }}</p>
            <p class="custom-cursor-pointer">like</p>
            <p @click="clickRepComment2()" class="custom-cursor-pointer">
                Phản
                hồi
            </p>
        </div>
    </div>
</template>
<script>
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
    computed: {
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        }
    },
    methods: {
        clickRepComment2() {
            this.$emit('focus-input',this.commentId);
            this.$emit('reply-comment-clicked', {
                selectedRepCommentIndex: this.index,
                boxRepComment: true,
                isRepComment: false,
                datacontentRepComment: '@' + this.repcomment.user.user_name + ' '
            });
        },
        CusRedirect(event) {
            const url = event.target.getAttribute('href');
            if (url) {
                this.$router.push(url);
            }
        },
    }
}
</script>