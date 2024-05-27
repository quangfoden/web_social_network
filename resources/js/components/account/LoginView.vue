<template>
  <div class="col-lg-9">
    <div class="text-center">
      <div>
        <!-- <a href="/" class="logo"> -->
        <!-- ../../../assets/images/logo.png -->
        <!-- <img src="" height="50" alt="logo" />
        </a> -->
      </div>
      <h4 class="font-size-18 mt-4">Welcome Back !</h4>
      <!-- <div class="form-group"> -->
      <!-- <router-link :to="{ name: 'login', query: { email: loginData.email } }"
          class="login-challenge-email form-control"><i class="ri-user-line align-middle mr-1"></i>{{
            loginData.email }}</router-link> -->
      <!-- </div> -->
      <!-- <p>NewName: {{ $store.getters['myModule/getAge'] }}</p>                                                                           <p>UserName: {{ $store.getters['myModule/getUser'] }}</p> -->
    </div>
    <!-- <b-alert variant="danger" class="mt-3"> </b-alert> -->
    <div class="p-2 mt-5">
      <form class="form-horizontal">
        <div class="form-group auth-form-group-custom mb-3">
          <i class="mdi mdi-email-outline text-black auti-custom-input-icon"></i>
          <label for="email">Email</label>
          <input type="email" v-model="user.email" class="form-control" id="email" placeholder="Enter email" />
        </div>
        <div class="form-group auth-form-group-custom mb-3">
          <i class="mdi mdi-lock text-black auti-custom-input-icon"></i>
          <label for="userpassword">Password</label>
          <input v-model="user.password" type="password" class="form-control" id="userpassword"
            placeholder="Enter password" />
        </div>
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="customControlInline" />
          <label class="custom-control-label mx-2" for="customControlInline">
            Remember me
          </label>
        </div>
        <div class="mt-4 text-center">
          <button v-if="!isLoading" @click.prevent="LoginUser"
            class="btn btn-all-add-edit w-md waves-effect waves-light" type="submit">
            LogIn
          </button>
          <div v-if="isLoading" class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </form>
      <div class="mt-4 text-center">
        <button v-if="!isLoading" @click.prevent="loginFaceId"
          class="btn btn-all-add-edit w-md waves-effect waves-light bg-info">
          login with faceID
        </button>
      </div>
      <div class="mt-5 text-center">
        <p>
          Don't have an account ?
          <router-link :to="{ name: 'Register' }" class="font-weight-medium text-login-register">Register</router-link>
        </p>
        <p>
          © 2023 Create
          <i class="mdi mdi-heart text-danger"></i> by
          me
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import Swal from 'sweetalert2';
export default {
  data() {
    return {
      user: {
        email: '',
        password: '',
      },
      dataUser: {
        user_id: null,
        username: null
      },
      isLoading: false
    }
  },
  methods: {
    LoginUser() {
      this.isLoading = true
      this.$store.dispatch('loginUser', this.user)
        .then(() => {
          setTimeout(() => {
            this.isLoading = false
          }, 3000);
        })
        .catch((error) => {
          console.log('Đăng nhập thất bại:', error)
          setTimeout(() => {
            this.isLoading = false
          }, 3000);
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
    }
  },
}
</script>