<script setup>

import AccountMultiple from 'vue-material-design-icons/AccountMultiple.vue';
import ThumbUp from 'vue-material-design-icons/ThumbUp.vue';
import Check from 'vue-material-design-icons/Check.vue';
import Delete from 'vue-material-design-icons/Delete.vue';

</script>
<template>
    <div id="post">
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
            <div v-for="medias in media" :key="media.id">
                <div class="" v-if="medias.type === 'image'">
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
        <div id="Likes" class="px-5">
            <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                <ThumbUp :size="16" fillColor="#1D72E2" />
                <div class="text-sm text-gray-600 font-semibold">5 bình luận</div>
            </div>
        </div>
        <div id="comments" class="px-3">
            <div id="CreateComment" class="d-flex align-items-center py-2">
                <form class="d-flex align-items-center justify-content-between w-100">
                    <a href="/" class="mr-2">
                        <img class="rounded-full ml-1 img-cus" :src="authUser.avatar" alt="">
                    </a>
                    <div class="d-flex align-items-center bg-EFF2F5 p-2 rounded-full w-100">
                        <input type="text" placeholder="Bình luận ..."
                            class="w-100 border-0 mx-1 border-none p-0 text-sm bg-EFF2F5 placeholder-[#64676B] ring-0 focus:ring-0">
                        <button type="button"
                            class="d-flex border-0 align-items-center text-sm px-3 rounded-full bg-blue-500 hover:bg-blue-600 text-white font-bold">
                            <Check />Gửi
                        </button>
                    </div>
                </form>
            </div>
            <div id="Comment">
                <div class="d-flex align-items-center justify-content-between pb-2">
                    <div class="d-flex align-items-center w-100 mb-1">
                        <a href="/" class="mr-2">
                            <img class="rounded-full ml-1 img-cus" src="https://picsum.photos/id/189/800/800" alt="">
                        </a>
                        <div class="d-flex align-items-center bg-EFF2F5 text-xs p-2 rounded-lg w-100">
                            Chao ban
                        </div>
                    </div>
                    <a class="rounded-full p-1.5 ml-2 custom-cursor-pointer">
                        <Delete fillColor="#64676B" size="20" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { toRefs, reactive } from 'vue';
import { useGeneralStore } from '../../../store/general';
import { storeToRefs } from 'pinia';

export default {
    props: ['post', 'user', 'media'],
    data() {
        const useGeneral = useGeneralStore()
        const { isFileDisplay } = storeToRefs(useGeneral)
        return {
            isFileDisplay,
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
  
}
</script>