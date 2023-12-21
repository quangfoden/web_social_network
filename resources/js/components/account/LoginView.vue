<template>
  <div class="col-lg-9">
    <div class="text-center">
      <div>
        <a href="/" class="logo">
          <!-- ../../../assets/images/logo.png -->
          <img src="" height="50" alt="logo" />
        </a>
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
      <form class="form-horizontal" @submit.prevent="LoginUser">
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
          <label class="custom-control-label" for="customControlInline">
            Remember me
          </label>
        </div>
        <div class="mt-4 text-center">
          <button class="btn btn-all-add-edit w-md waves-effect waves-light" type="submit">
            Log In
          </button>
        </div>
      </form>
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
export default {
  data() {
    return {
      user: {
        email: '',
        password: '',
      }
    }
  },
  methods: {
    LoginUser() {
      this.$store.dispatch('loginUser', this.user)
        .then(() => {

        })
        .catch((error) => {
          console.log('Đăng nhập thất bại:', error)
        });
    },
  },
}
</script>