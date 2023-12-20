<template>
    <div v-if="registrationErrors" style="color: red;">
        <ul>
            <li v-for="error in registrationErrors">{{ error }}</li>
        </ul>
    </div>
    <div class="p-5">
        <div class="text-center">
            <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
        </div>
        <form @submit.prevent="registerUser" class="user">
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" id="exampleFirstName" placeholder="First Name"
                        v-model="user.first_name">
                </div>
                <div class="col-sm-6">
                    <input type="text" class="form-control form-control-user" id="exampleLastName" placeholder="Last Name"
                        v-model="user.last_name">
                </div>
            </div>
            <div class="form-group">
                <input type="email" class="form-control form-control-user" id="exampleInputEmail"
                    placeholder="Email Address" v-model="user.email">
            </div>
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="password" class="form-control form-control-user" id="exampleInputPassword"
                        placeholder="Password" v-model="user.password">
                </div>
                <div class="col-sm-6">
                    <input type="password" class="form-control form-control-user" id="exampleRepeatPassword"
                        placeholder="Repeat Password" v-model="user.password_confirmation">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-user btn-block">
                Register Account
            </button>
            <hr>
        </form>
        <hr>
        <div class="text-center">
            <router-link :to="{ name: 'ForgotPassword' }" class="small">Forgot Password?</router-link>
        </div>
        <div class="text-center">
            <router-link :to="{ name: 'Login' }" class="small">Already have an account?
                Login!</router-link>
        </div>
    </div>
    <!-- Hiển thị thông báo đăng ký thành công -->
    <div v-if="registrationStatus === true" style="color: green;">
        Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.
    </div>
</template>
<script>
export default {
    data() {
        return {
            user: {
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                password_confirmation: ''
            }
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
            this.$store.dispatch('registerUser', this.user)
                .then(() => {
                    setTimeout(() => {
                        this.$router.push('/login');
                    }, 3000);
                })
                .catch((error) => {
                    console.log('Đăng ký thất bại:', error)
                });
        },
    },
};
</script>