<template>
    <div class="col-12 col-lg-12 col-xl-3 order-3 order-lg-3 mt-3">
        <div class="card">
            <div class="card-header">
                <div class="card-actions float-right">
                    <div class="dropdown show">
                        <a href="#" data-bs-toggle="dropdown" data-bs-display="static">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="19" cy="12" r="1"></circle>
                                <circle cx="5" cy="12" r="1"></circle>
                            </svg>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Hành động</a>
                            <router-link :to="{ name: 'Repository User' }" class="dropdown-item" href="#">Kho lưu trữ</router-link>
                                <a class="dropdown-item" href="#">More...</a>
                        </div>
                    </div>
                </div>
                <h5 class="card-title mb-0">Hoạt động gần đây</h5>
            </div>
            <div class="card-body h-100">
                <div class="media">
                    <img src="https://therichpost.com/wp-content/uploads/2021/03/avatar3.png" width="36" height="36"
                        class="rounded-circle mr-2" alt="Jassa Jas">
                    <div class="media-body">
                        <small class="float-right text-navy">5 phút trước </small>
                        <strong>Quốc </strong> đã chọc bạn
                        <br>
                        <small class="text-muted">hôm nay 7h51p</small>
                        <br>
                    </div>
                </div>

                <hr>
                <div class="media">
                    <img src="https://therichpost.com/wp-content/uploads/2021/03/avatar3.png" width="36" height="36"
                        class="rounded-circle mr-2" alt="Andrew Jones">
                    <div class="media-body">
                        <small class="float-right text-navy">30 phút trước </small>
                        <strong>Uyên </strong> đã đăng 1 bài viết mới
                        <br>
                        <small class="text-muted">Hôm nay 7:21p</small>
                    </div>
                </div>

                <hr>
                <hr>
                <div class="media">
                    <img src="https://therichpost.com/wp-content/uploads/2021/03/avatar3.png" width="36" height="36"
                        class="rounded-circle mr-2" alt="John Smith">
                    <div class="media-body">
                        <small class="float-right text-navy">3h trước </small>
                        <strong>Quang</strong> đã đăng 2 ảnh mới

                        <br>
                        <small class="text-muted">Hôm nay 10h</small>
                        <div class="row no-gutters mt-1">
                            <div class="col-6 col-md-4 col-lg-4 col-xl-3">
                                <img src="https://therichpost.com/wp-content/uploads/2021/03/avatar3.png"
                                    class="img-fluid pr-2" alt="Unsplash">
                            </div>
                            <div class="col-6 col-md-4 col-lg-4 col-xl-3">
                                <img src="https://therichpost.com/wp-content/uploads/2021/03/avatar4.png"
                                    class="img-fluid pr-2" alt="Unsplash">
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <hr>
                <div class="media">
                    <img src="https://therichpost.com/wp-content/uploads/2021/03/avatar3.png" width="36" height="36"
                        class="rounded-circle mr-2" alt="Andrew Jones">
                    <div class="media-body">
                        <small class="float-right text-navy">1 ngày trước </small>
                        <strong>Quang </strong> bắt đầu theo dõi bạn
                        <br>
                        <small class="text-muted">Hôm qua 5h31p </small>
                    </div>
                </div>

                <hr>
                <a href="#" class="btn btn-primary btn-sm btn-block">Xem thêm</a>
            </div>
        </div>
    </div>
</template>