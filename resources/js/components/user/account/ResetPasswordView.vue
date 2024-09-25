<template>
    <div class="col-lg-4">
        <span v-if="isLoading" class="loader"></span>
        <div class="we-login-register">
            <div class="form-title">
                <i class="fa fa-key"></i>Đặt lại mật khẩu
                <span>Nhập mật khẩu mới để tiếp tục đăng nhập vào tài khoản của bạn.</span>
            </div>
            <form class="we-form" @submit.prevent="resetPassword">
                <input v-model="token" type="hidden" />
                <input type="hidden" v-model="email" />
                <input type="password" v-model="password" placeholder="Mật khẩu mới">
                <input type="password" v-model="password_confirmation" placeholder="Xác nhận mật khẩu">
                <button type="submit" v-ripple>Đặt lại mật khẩu</button>
            </form>
            <div v-if="message" :class="{ 'success': isSuccess, 'error': !isSuccess }">
                {{ message }}
            </div>
            <span>Bạn đã có tài khoản? <router-link :to="{ name: 'Login User' }" class="we-account underline"
                    title="đăng kí tài khoản">Đăng nhập</router-link></span>
        </div>
    </div>
</template>
<style scoped>
.success {
    color: aqua;
}

.error {
    color: rgba(240, 8, 8, 0.836);
}
</style>
<script>
export default {
    data() {
        return {
            token: this.$route.params.token || '',
            email: this.$route.query.email || '',
            password: '',
            password_confirmation: '',
            message: '',
            isSuccess: true,
            isLoading: false

        };
    },
    methods: {
        async resetPassword() {
            this.isLoading = true
            try {
                const response = await axios.post('/api/reset-password', {
                    token: this.token,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation
                });
                this.isLoading = false
                this.isSuccess = true;
                this.message = response.data.status;
            } catch (error) {
                if (error.response && error.response.data) {
                    if (error.response.data.errors) {
                        this.message = error.response.data.errors.password[0] || 'Có lỗi xảy ra. Vui lòng thử lại.'
                    }
                    else {
                        this.message = error.response.data.error || 'Có lỗi xảy ra. Vui lòng thử lại.';
                    }
                } else {
                    this.message = 'Có lỗi xảy ra. Vui lòng thử lại.';
                }
                this.isLoading = false
                this.isSuccess = false;
            }
        }
    }
};
</script>