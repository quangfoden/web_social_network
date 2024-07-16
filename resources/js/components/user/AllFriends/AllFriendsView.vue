<template>
    <div id="AllFriends" class="container-fluid">
        <div class="row">
            <LeftAllFriends id="LeftAllFriends" />
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import LeftAllFriends from './LeftAllFriends.vue'
export default {
    components:{
        LeftAllFriends
    }
}
</script>