<template>
  <div class="www-layout">
    <section>
      <div class="gap no-gap signin whitish medium-opacity">
        <div class="bg-image" style="background-image:url(/images/theme-bg.jpg)"></div>
        <div class="container">
          <div class="row">
            <div class="col-lg-8">
              <div class="big-ad">
                <figure><h3>Imnotify</h3></figure>
                <h1>Chào mừng bạn đến với Imnotify</h1>
                <p>
                  Imnotify là một mẫu giao diện mạng xã hội có thể được sử dụng để kết nối mọi người. Sử dụng mẫu này cho
                  nhiều mục đích xã hội như tìm việc, hẹn hò, đăng bài, viết blog và nhiều hơn thế nữa. Tham gia ngay
                  bây giờ và Kết Bạn Thú Vị trên toàn thế giới!!!
                </p>
                <div class="fun-fact">
                  <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-4">
                      <div class="fun-box">
                        <i class="fas fa-check"></i>
                        <h6>Người dùng đã đăng kí</h6>
                        <span>1,01,242</span>
                      </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4">
                      <div class="fun-box">
                        <i class="fas fa-file-alt"></i>
                        <h6>Bài viết đã đăng</h6>
                        <span>21,03,245</span>
                      </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4">
                      <div class="fun-box">
                        <i class="fas fa-user"></i>
                        <h6>người dùng trực tuyến</h6>
                        <span>40,145</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <router-view></router-view>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>