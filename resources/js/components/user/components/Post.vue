<script setup>

import AccountMultiple from 'vue-material-design-icons/AccountMultiple.vue';
import ThumbUp from 'vue-material-design-icons/ThumbUp.vue';
import Check from 'vue-material-design-icons/Check.vue';
import Delete from 'vue-material-design-icons/Delete.vue';




// const props = defineProps({
//     user: Object,
//     post: Object,
//     comment: Object
// })
// const { user, post, comment } = toRefs(props)
</script>
<template>
    <div id="post">
        <div class="d-flex align-items-center py-3 px-0">
            <a class="mr-2">
                <img class="img-cus" src="https://picsum.photos/id/120/300/320" alt="">
            </a>
            <div class="d-flex align-items-center justify-content-between p-2 rounded-full w-100">
                <div>
                    <div class="text-pr">Nguyen Van Quang</div>
                    <div class="d-flex align-items-center text-xs text-gray-600">
                        14h
                        <AccountMultiple :size="15" class="ml-1" fillColor="#64676B" />
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <a class="rounded-full p-1 custom-cursor-pointer">
                    <Delete :size="20" fillColor="#64676B" />
                </a>
            </div>
        </div>
        <div class="px-1 pb-2 text-cus-pos">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias deleniti aliquam dolorem odit neque laborum
            ratione quos ex ipsam facilis. Tenetur quas at pariatur aperiam, numquam soluta obcaecati minima praesentium
            doloribus molestiae ipsam voluptatem officia, sunt hic voluptas ratione possimus. Minima, et? Illo ullam et
            culpa natus, delectus quisquam impedit maiores ad quidem! Consectetur ducimus vel hic ut incidunt quibusdam,
            ratione odio? Suscipit ullam totam natus magnam eos voluptatum facere, rerum in molestiae ipsa. Esse, obcaecati
            natus dignissimos adipisci sed deleniti eius odit rem exercitationem quis corrupti est voluptates? Officia id
            tempore laudantium repellendus a nulla dolor deleniti earum alias.
        </div>
        <img @click="isImageDisplay = 'https://picsum.photos/id/189/800/800'" class="mx-auto custom-cursor-pointer w-100"
            alt="" src="https://picsum.photos/id/189/800/800">
        <div id="Likes" class="px-5">
            <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                <ThumbUp :size="16" fillColor="#1D72E2" />
                <div class="text-sm text-gray-600 font-semibold">5 bình luận</div>
            </div>
        </div>
        <div id="comments" class="px-3">
            <div id="CreateComment" class="d-flex align-items-center py-2">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <a href="/" class="mr-2">
                        <img class="rounded-full ml-1 img-cus" src="https://picsum.photos/id/189/800/800" alt="">
                    </a>
                    <div class="d-flex align-items-center bg-EFF2F5 p-2 rounded-full w-100">
                        <input type="text" placeholder="Bình luận ..."
                            class="w-100 border-0 mx-1 border-none p-0 text-sm bg-EFF2F5 placeholder-[#64676B] ring-0 focus:ring-0">
                        <button type="button"
                            class="d-flex border-0 align-items-center text-sm px-3 rounded-full bg-blue-500 hover:bg-blue-600 text-white font-bold">
                            <Check />Gửi
                        </button>
                    </div>
                </div>
            </div>
            <div id="Comments">
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
    setup() {
        const form = reactive({ comment: null });
        const useGeneral = useGeneralStore()
        const { isImageDisplay } = storeToRefs(useGeneral)
        return {
            isImageDisplay,
            form
        }
    }
}
</script>