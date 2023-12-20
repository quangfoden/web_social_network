<template>
  <div class="p-5">
    <div class="text-center">
      <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
    </div>
    <form @submit.prevent="login" class="user">
      <div class="form-group">
        <input type="text" class="form-control form-control-user username" v-model="username" id="exampleInputEmail"
          placeholder="Enter Username...">
      </div>
      <div class="form-group">
        <input type="password" v-model="password" class="form-control form-control-user password"
          id="exampleInputPassword" placeholder="Password">
      </div>
      <div class="form-group">
        <div class="custom-control custom-checkbox small">
          <input type="checkbox" class="custom-control-input" id="customCheck">
          <label class="custom-control-label" for="customCheck">Remember
            Me</label>
        </div>
      </div>
      <a @click="login()" class="btn btn-primary btn-user btn-block">
        Login
      </a>
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
      username: '',
      password: '',
      authen: false
    };
  },
  methods: {
    login() {
      this.authenticateUser(this.username, this.password, 'data_user.json', '/user-manage');
    },
    authenticateUser(username, password, jsonFilePath, successRedirect) {
      fetch(jsonFilePath)
        .then(response => response.json())
        .then(data => {
          const user = data.data.find(u => u.username === username && u.password === password);
          if (user) {
            alert("Login successful! Welcome, " + user.username + "!");
            console.log(user);
            this.authen = true
            this.$router.push(successRedirect);
          } else {
            alert("Invalid username or password. Please try again.");
          }
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    }
  }
};
</script>