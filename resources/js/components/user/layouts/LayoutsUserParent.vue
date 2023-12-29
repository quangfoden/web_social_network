<template>
    <TopBar />
    <div class="wrapper_customs">
        <div class="wrapper_2">
            <SidebarLeft />
            <router-view></router-view>
            <SidebarRight />
        </div>
    </div>
</template>
<script>
import SidebarLeft from './SidebarLeft.vue';
import SidebarRight from './SidebarRight.vue';
import TopBar from './TopBar.vue';
export default{
    components:{
        SidebarLeft,
        SidebarRight,
        TopBar
    }
}
</script>