<template>
    <div class="col-12 col-lg-4 col-xl-3 order-2 order-lg-1">
        <div class="card mb-3 mt-3">
            <div id="CoverImage">
                <img src="../../../../assets/images/bgaccount2.jpg" alt="">
            </div>
            <div class="card-body text-center" style="margin-top: -100px;">
                <img :src="authUser.avatar" alt="Jassa Jas" class="img-fluid rounded-circle mb-2" width="128"
                    height="128">
                <h4 class="card-title mb-2">{{ authUser.user_name }}</h4>
                <!-- <div class="text-muted mb-2">Front-end Developer</div> -->

                <div v-if="!authUser">
                    <a class="btn btn-primary btn-sm" href="#">Follow</a>
                    <a class="btn btn-primary btn-sm" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-message-square">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg> Message</a>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                <div class="card-actions float-right">
                    <div class="dropdown show">
                        <a href="#" data-toggle="dropdown" data-display="static">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="19" cy="12" r="1"></circle>
                                <circle cx="5" cy="12" r="1"></circle>
                            </svg>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                </div>
                <h5 class="card-title mb-0">Giới thiệu</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-home feather-sm mr-1">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg> Sống tại huế
                    </li>
                    <li class="mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-briefcase feather-sm mr-1">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg> Đang học tại DH Pxu
                    </li>
                    <li class="mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-map-pin feather-sm mr-1">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg> Huế
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <div class="card-actions float-right">
                    <div class="dropdown show">
                        <a href="#" data-bs-toggle="dropdown" data-display="static">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="19" cy="12" r="1"></circle>
                                <circle cx="5" cy="12" r="1"></circle>
                            </svg>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Hành Động</a>
                            <a class="dropdown-item" href="#">...</a>
                            <a class="dropdown-item" href="#">More..</a>
                        </div>
                    </div>
                </div>
                <h5 class="card-title mb-0">Ảnh</h5>
            </div>
            <div class="d-flex m-3 flex-wrap align-items-center">
                <span class="" v-for="postsByUser in postsByUsers">
                    <span v-if="postsByUser.media" v-for="_media in postsByUser.media">
                        <span v-if="_media.type === 'image/gif' || _media.type === 'image/jpeg'"
                            class="media text-center">
                            <img :src="_media.url" width="60" height="60" class="border border-2 rounded-md mr-2"
                                alt="Andrew Jones">
                        </span>
                    </span>
                </span>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                <div class="card-actions float-right">
                    <div class="dropdown show">
                        <a href="#" data-bs-toggle="dropdown" data-display="static">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="19" cy="12" r="1"></circle>
                                <circle cx="5" cy="12" r="1"></circle>
                            </svg>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Hành Động</a>
                            <a class="dropdown-item" href="#">...</a>
                            <a class="dropdown-item" href="#">More..</a>
                        </div>
                    </div>
                </div>
                <h5 class="card-title mb-0">Bạn bè</h5>
            </div>
            <div class="card-body d-flex align-item-center flex-wrap gap-2">

                <div class="media text-center">
                    <img src="https://therichpost.com/wp-content/uploads/2021/03/avatar3.png" width="56" height="56"
                        class="rounded-circle mr-2 mb-2" alt="Andrew Jones">
                    <div class="media-body">
                        <!-- <p class="my-1"><strong>Nguyen Van Quang</strong></p> -->
                        <a class="btn btn-sm btn-outline-primary" href="#">Xem thêm</a>
                    </div>
                </div>

                <hr class="my-2">

                <div class="media text-center">
                    <img src="https://therichpost.com/wp-content/uploads/2021/03/avatar3.png" width="56" height="56"
                        class="rounded-circle mr-2 mb-2" alt="John Smit">
                    <div class="media-body">
                        <!-- <p class="my-1"><strong>Quốc Lê</strong></p> -->
                        <a class="btn btn-sm btn-outline-primary" href="#">Xem thêm</a>
                    </div>
                </div>

                <hr class="my-2">
            </div>
        </div>
    </div>
</template>
<script>
export default {
    computed: {
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
    },
    props: {
        postsByUsers: {
            type: Object,
            required: true,
        },
    },
}
</script>