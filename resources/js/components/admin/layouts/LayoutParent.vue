<template>
    <div id="wrapper">
        <LeftSidebar />
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <HeaderView />
                <!-- start page title -->
                <div class="row mb-4" style="padding: 0 12px;">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between"
                            style="padding-bottom: 0px;">
                            <h4 class="mb-0 d-none d-md-block">
                                <!-- {{ pageName }} -->
                            </h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb" v-if="crumbs && crumbs.length > 0">
                                    <li class="breadcrumb-item" v-for="(crumb, index) in crumbs" :key="index">
                                        <p style="margin-bottom: 0px;">{{ crumb.title }}</p>
                                    </li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <div class="container-fluid">
                    <router-view></router-view>
                </div>
            </div>
            <FooterView />
        </div>
    </div>
</template>
<script>
import HeaderView from './HeaderView.vue'
import LeftSidebar from './Leftsiderbar.vue'
import FooterView from './FooterView.vue'
export default {
    components: {
        HeaderView,
        LeftSidebar,
        FooterView,
    },
    computed: {
        crumbs() {
            const routes = this.$route.matched
            const crumbs = routes.map(route => ({
                title: route.name,
                link: route.path,
                breadcrumb: route.breadcrumb
            }))
            if (crumbs.length > 0 && crumbs[crumbs.length - 1].title === 'Admin Dashboard') {
                crumbs.pop()
            }
            return crumbs
        },
        pageName() {
            if (this.crumbs.length > 0) {
                return this.crumbs[this.crumbs.length - 1].title
            } else {
                return "Dashboard"
            }
        }
    },
}
</script>