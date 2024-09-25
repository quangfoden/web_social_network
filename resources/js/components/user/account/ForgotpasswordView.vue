<template>
    <div class="col-lg-4">
        <span v-if="isLoading" class="loader"></span>
        <div class="we-login-register">
            <div class="form-title">
                <i class="fa fa-key"></i>Đặt lại mật khẩu
                <span>Nhập email để đặt lại mật khẩu cho tài khoản của bạn.</span>
            </div>
            <form class="we-form" @submit.prevent="sendResetLink">
                <input type="text" v-model="email" placeholder="Email">
                <button type="submit" v-ripple>Gửi</button>
            </form>
            <div v-if="message" :class="{ 'success': isSuccess, 'error': !isSuccess }">
                {{ message }}
            </div>
            <span>bạn chưa có tài khoản? <router-link :to="{ name: 'Register User' }" class="we-account underline"
                    title="đăng kí tài khoản">Đăng ký ngay</router-link></span>
        </div>
    </div>
</template>
<style scoped>
.success{
    color: aqua;
}
.error{
    color: rgba(240, 8, 8, 0.836);
}
</style>
<script>
export default {
    data() {
        return {
            email: '',
            message: '',
            isSuccess: true,
            isLoading:false,
        };
    },
    methods: {
        async sendResetLink() {
            this.isLoading=true
            try {
                const response = await axios.post('/api/forgot-password', {
                    email: this.email,
                });
                this.message = response.data.status;
                this.isSuccess = true;
                this.isLoading=false
            } catch (error) {
                if (error.response && error.response.data) {
                    this.message = error.response.data.error || 'Có lỗi xảy ra. Vui lòng thử lại.';
                } else {
                    this.message = 'Có lỗi xảy ra. Vui lòng thử lại.';
                }
                this.isSuccess = false;
                this.isLoading=false
            }
        }
    }
}
</script>