<template>
    <!-- ========== Left Sidebar Start ========== -->
    <div class="vertical-menu">

        <div data-simplebar class="h-100">

            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li class="menu-title">Menu</li>
                    <li>
                        <router-link :to="{ name: 'All User' }" class=" waves-effect" exact tag="li">
                            <i class="far fa-user"></i>
                            <span>Quản lý User</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link :to="{ name: 'All Post' }" class=" waves-effect" exact tag="li">
                            <i class="far fa-user"></i>
                            <span>Quản lý Post</span>
                        </router-link>
                    </li>
                    <!-- <li>
                        <a href="javascript: void(0);"
                            :class="{ 'mm-active': $route.name === 'All Roles' || $route.name === 'Add Role' }"
                            class="has-arrow waves-effect">
                            <i class="fas fa-users-cog"></i>
                            <span>Quản lý Post</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <router-link :to="{ name: 'All Roles' }" exact tag="li" exact-active-class="active">Danh
                                    sách</router-link>
                            </li>
                            <li>
                                <router-link :to="{ name: 'Add Role' }" exact tag="li" exact-active-class="active">Thêm
                                    mới</router-link>
                            </li>
                        </ul>
                    </li>
                    <li :class="{ 'mm-active': $route.name === 'All Permissions' || $route.name === 'Add Permission' }"
                        v-if="checkPermission(['Role Manager'])">
                        <a href="javascript: void(0);"
                            :class="{ 'mm-active': $route.name === 'All Permissions' || $route.name === 'Add Permission' }"
                            class="has-arrow waves-effect">
                            <i class="fas fa-users"></i>
                            <span>Trình quản lý quyền</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <router-link :to="{ name: 'All Permissions' }" exact tag="li"
                                    exact-active-class="active">Danh sách</router-link>
                            </li>
                            <li>
                                <router-link :to="{ name: 'Add Permission' }" exact tag="li"
                                    exact-active-class="active">Thêm mới</router-link>
                            </li>
                        </ul>
                    </li> -->
                    <!-- <li class="menu-title">Pages</li> -->

                </ul>
            </div>
            <!-- Sidebar -->
        </div>
    </div>
    <!-- Left Sidebar End -->
</template>
<script>
// import { checkRolesAccess, checkPermissionAccess } from '@resources/js/middleware/access.js';
// export default {
//     data() {
//         return {
//             isExpanded: false
//         }
//     },
//     created() {
//     },
//     methods: {
//         checkPermission(permissions) {
//             return checkPermissionAccess(permissions);
//         }
//     },
//     mounted() {
//         // Đặt lại thuộc tính aria-expanded khi component được tải lại
//         // const element = document.getElementsByClassName('has-arrow');
//         // if (element) {
//         //     element.setAttribute('aria-expanded', this.isExpanded);
//         // }
//         const element = document.querySelector('.has-arrow');
//         if (element instanceof Element) {
//             element.setAttribute('aria-expanded', 'true');
//         }
//     },
//     computed: {
//         userHasAdmin() {
//             return checkRolesAccess(['admin'])
//         },
//     }
// }
</script>
