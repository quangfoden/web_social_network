<template>
  <div class="p-5">
    <div class="text-center">
      <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
    </div>
    <form @submit.prevent="LoginUser" class="user">
      <div class="form-group">
        <input type="email" class="form-control form-control-user" v-model="user.email" id="exampleInputEmail"
          placeholder="Enter Email...">
      </div>
      <div class="form-group">
        <input type="password" v-model="user.password" class="form-control form-control-user password"
          id="exampleInputPassword" placeholder="Password">
      </div>
      <div class="form-group">
        <div class="custom-control custom-checkbox small">
          <input type="checkbox" class="custom-control-input" id="customCheck">
          <label class="custom-control-label" for="customCheck">Remember
            Me</label>
        </div>
      </div>
      <button type="submit" class="btn btn-primary btn-user btn-block">
        Login
      </button>
    </form>
    <hr>
    <div class="text-center">
      <router-link :to="{ name: 'ForgotPassword' }" class="small">Forgot Password?</router-link>
    </div>
    <div class="text-center">
      <router-link :to="{ name: 'Register' }" class="small">Create an Account!</router-link>
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
          console.log('Đăng nhập thất bái:', error)
        });
    },
  },
}
</script>