<template>
    <div class="col-lg-4">
        <span v-if="isLoading" class="loader"></span>
        <div class="we-login-register">
            <div class="form-title">
                <i class="fa fa-key"></i>Đăng kí
                <span>Đăng ký ngay và gặp gỡ những người bạn tuyệt vời trên khắp thế giới.</span>
            </div>
            <form class="we-form" @submit.prevent="registerUser">
                <input type="text" v-model="user.first_name" placeholder="First name">
                <input type="text" v-model="user.last_name" placeholder="Last name">
                <input type="text" v-model="user.user_name" placeholder="User name">
                <input type="text" v-model="user.email" placeholder="Email">
                <input type="password" v-model="user.password" placeholder="Password">
                <input type="password" v-model="user.password_confirmation" placeholder="Repassword">
                <button type="submit" :disabled="isLoading" v-ripple>Đăng kí</button>
                <router-link :to="{name:'Forgot Password User'}" class="forgot underline" href="#" title="">Quên mật khẩu?</router-link>
            </form>
            <a v-ripple title="" href="#" class="with-smedia facebook"><i class="fab fa-facebook"></i></a>
            <a v-ripple title="" href="#" class="with-smedia google"><i class="fab fa-google-plus"></i></a>
            <span>Bạn đã có tài khoản? <router-link :to="{ name: 'Login User' }" class="we-account underline" href="#"
                    title="">Đăng nhập</router-link></span>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: {
                first_name: '',
                last_name: '',
                user_name: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            isLoading: false
        }
    },
    computed: {
        registrationStatus() {
            return this.$store.getters.getRegistrationStatus;
        },
        registrationErrors() {
            return this.$store.getters.getRegistrationErrors;
        },
    },
    methods: {
        registerUser() {
            this.isLoading = true
            this.$store.dispatch('registerUser', this.user)
                .then(() => {
                    this.isLoading = false
                })
                .catch((error) => {
                    console.log('Đăng ký thất bại:', error)
                    this.isLoading = false
                });
        },
    },
};
</script>