<script setup>
import Magnify from 'vue-material-design-icons/Magnify.vue';
import Home from 'vue-material-design-icons/Home.vue';
import AccountMultiple from 'vue-material-design-icons/AccountMultiple.vue';
import TelevisionPlay from 'vue-material-design-icons/TelevisionPlay.vue';
import StorefrontOutline from 'vue-material-design-icons/StorefrontOutline.vue';
import AccountGroup from 'vue-material-design-icons/AccountGroup.vue';
import ControllerClassicOutline from 'vue-material-design-icons/ControllerClassicOutline.vue';
import ClockTimetwoOutline from 'vue-material-design-icons/ClockTimetwoOutline.vue';
import VideoImage from 'vue-material-design-icons/VideoImage.vue';
import Flag from 'vue-material-design-icons/Flag.vue';
import DotsHorizontal from 'vue-material-design-icons/DotsHorizontal.vue';
import Restore from 'vue-material-design-icons/Restore.vue';
</script>
<template>
    <div id="RightSection">
        <div class="rightsection_cus">
            <div class="d-flex align-items-center justify-content-between">
                <div class="font-semibold">Contacts</div>
                <div class="d-flex align-items-center">
                    <div class="icon-right">
                        <VideoImage :size="23" fillColor="#050505" />
                    </div>
                    <div class="icon-right">
                        <Magnify :size="23" fillColor="#050505" />
                    </div>
                    <div class="icon-right">
                        <DotsHorizontal :size="23" fillColor="#050505" />
                    </div>
                </div>
            </div>
            <div class="list_friends">
                <div class="d-flex align-items-center justify-content-start friend_pr">
                    <img class="" src="https://picsum.photos/id/120/300/320"
                        alt="">
                    <div class="text-pr">Nguyen Thi Huong</div>
                </div>
                <div class="d-flex align-items-center justify-content-start friend_pr">
                    <img class="" src="https://picsum.photos/id/120/300/320"
                        alt="">
                    <div class="text-pr">Nguyen Thi Huong</div>
                </div>
                <div class="d-flex align-items-center justify-content-start friend_pr">
                    <img class="" src="https://picsum.photos/id/120/300/320"
                        alt="">
                    <div class="text-pr">Nguyen Thi Huong</div>
                </div>
                <div class="d-flex align-items-center justify-content-start friend_pr">
                    <img class="" src="https://picsum.photos/id/120/300/320"
                        alt="">
                    <div class="text-pr">Nguyen Thi Huong</div>
                </div>
            </div>
        </div>
    </div>
</template>