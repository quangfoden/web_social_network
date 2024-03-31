<template>
    <div id="AddnewFaceID">
        <router-link :to="{name:'Home Section'}" class="btn btn-sm btn-danger m-4">Trở về</router-link>
        <div class="text_item text-center text-white font-bold">
            <h3>Click để bắt đầu đăng ký khuôn mặt</h3>
            <button @click="AddNewFaceID" class="btn btn-primary">bắt đầu</button>
        </div>
    </div>
</template>
<style scoped>
#AddnewFaceID {
    position: relative;
    background-image: url(../../../../assets/images/bgfaceid.jpg);
    background-repeat: no-repeat;
    background-size: cover;
    width: 100vw;
    height: 100vh;
}

.text_item {
    width: 20%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%)
}
</style>
<script>
import Router from '../../../router';
import Swal from 'sweetalert2';
export default {
    data() {
        return {
            userData: {
                userId: null,
                userName: null
            }
        }
    },
    computed: {
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
    },
    methods: {
        AddNewFaceID() {
            this.userData.userId = this.authUser.id
            this.userData.userName = this.authUser.user_name
            axios.get('/sanctum/csrf-cookie').then(() => {
                axios.post('http://127.0.0.1:5000/add', this.userData)
                    .then(response => {
                        console.log(response.data);
                        Swal.fire({
                            icon: 'success',
                            text: `Success ${response.data.message}`,
                            showConfirmButton: false,
                            timer: 3000
                        })
                        this.$store.dispatch('setAuthuser')
                        Router.push('/');
                    })
                    .catch(error => {
                        console.error('Lỗi khi gửi yêu cầu:', error);
                        let errorMessage = 'Có lỗi xảy ra khi gửi yêu cầu';
                        if (error.response && error.response.data && error.response.data.message) {
                            errorMessage = error.response.data.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            text: errorMessage,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    });
            })
        }
    },
}
</script>