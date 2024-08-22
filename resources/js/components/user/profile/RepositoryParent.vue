<template>
    <TopBar />
    <div id="RepoParent">
        <div class="container-fluid">
            <div class="row">
                <RepositoryLeft />
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>
<script>
import TopBar from '../layouts/TopBar.vue'
import RepositoryLeft from './RepositoryLeft.vue'
export default {
    components: {
        TopBar,
        RepositoryLeft
    }
}
</script>