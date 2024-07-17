<template>
    <!-- Begin page -->
    <div id="layout-wrapper">

        <HeaderView />

        <!-- ========== Left Sidebar Start ========== -->
        <LeftSiderbar />
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content" style="background: #fff;">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
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
                    <router-view></router-view>

                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <FooterView />
        </div>
        <!-- end main content-->

    </div>

    <!-- END layout-wrapper -->
</template>
<script>

import LeftSiderbar from './LeftSiderbar.vue';
import HeaderView from './HeaderView.vue';
import FooterView from './FooterView.vue';
export default {
    components: {
        LeftSiderbar,
        HeaderView,
        FooterView
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