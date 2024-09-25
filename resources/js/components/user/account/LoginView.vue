<template>
  <div class="col-lg-4">
    <span v-if="isLoading" class="loader"></span>
    <div class="we-login-register">
      <div class="form-title">
        <i class="fa fa-key"></i>Đăng nhập
        <span>Đăng nhập ngay và gặp gỡ những người bạn tuyệt vời trên khắp thế giới.</span>
      </div>
      <form class="we-form" @submit.prevent="LoginUser">
        <input type="text" v-model="user.email" placeholder="Email">
        <input type="password" v-model="user.password" placeholder="Password">
        <input type="checkbox" v-model="user.remember"><label>Nhớ mật khẩu</label>
        <button type="submit" :disabled="isLoading" v-ripple>Đăng
          nhập</button>
        <router-link :to="{ name: 'Forgot Password User' }" class="forgot underline" href="#" title="">Quên mật
          khẩu</router-link>
      </form>
      <button :disabled="isLoading" @click.prevent="loginFaceId" class="login-fade-id" v-ripple>
        Đăng nhập bằng khuôn mặt
      </button>
      <a class="with-smedia facebook" href="#" title="" v-ripple><i class="fab fa-facebook"></i></a>
      <a class="with-smedia google" href="#" title="" v-ripple><i class="fab fa-google-plus"></i></a>
      <span>bạn chưa có tài khoản? <router-link :to="{ name: 'Register User' }" class="we-account underline"
          title="đăng kí tài khoản">Đăng ký ngay</router-link></span>
    </div>
  </div>
</template>
<style scoped>
.login-fade-id {
  margin-bottom: 30px;
  background: #fff;
  color: #515365;
  border-radius: 20px;
  border: none;
  padding: 5px;
  width: 100%;
  text-align: center;
  text-transform: capitalize;
}
</style>
<script>
import Swal from 'sweetalert2';
export default {
  data() {
    return {
      user: {
        email: '',
        password: '',
        remember: false
      },
      dataUser: {
        user_id: null,
        username: null
      },

      isLoading: false
    }
  },
  computed: {

  },
  created() {

  },

  methods: {
    LoginUser() {
      this.isLoading = true
      this.$store.dispatch('loginUser', this.user)
        .then(response => {
          //   if (this.rememberMe) {
          //   localStorage.setItem('authToken', response.data.access_token);
          // } else {
          //   sessionStorage.setItem('authToken', response.data.access_token);
          // }
          console.log(response);

          this.isLoading = false
        })
        .catch((error) => {
          console.log('Đăng nhập thất bại:', error)
          this.isLoading = false
        });
    },
    loginFaceId() {
      this.isLoading = true
      axios.post('http://127.0.0.1:5000/start')
        .then(response => {
          this.dataUser.user_id = response.data.user.user_id
          this.dataUser.username = response.data.user.username

          this.$store.dispatch('loginWithFaceId', this.dataUser)
            .then(() => {
            })
            .catch((error) => {
              console.log('Đăng nhập thất bại:', error)
            });
          this.isLoading = false
        })
        .catch(error => {
          console.error('Lỗi khi gửi yêu cầu:', error);
          Swal.fire({
            icon: 'error',
            title: 'Xác thực thất bại',
            showConfirmButton: false,
            timer: 3000
          })
          this.isLoading = false
        });
    },

  },
}
</script>