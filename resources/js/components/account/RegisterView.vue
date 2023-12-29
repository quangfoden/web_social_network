<template>
    <div class="col-lg-12">
        <div>
            <div class="text-center">
                <div>
                    <a href="/" class="logo">
                        <img src="/assets/images/logo-dark.png" height="20" alt="logo" />
                    </a>
                </div>
                <h4 class="font-size-18 mt-4 mt-md-3">Register account</h4>
            </div>
            <div class="p-2 mt-5 mt-md-3">
                <form class="form-horizontal" @submit.prevent="registerUser">
                    <div class="d-lg-flex gap-1 justify-content-between">
                        <div class="form-group auth-form-group-custom mb-3">
                            <i class="mdi mdi-account text-black auti-custom-input-icon"></i>
                            <label for="userfirstname">First name</label>
                            <input v-model="user.first_name" type="text" class="form-control" id="userfirstname"
                                placeholder="Enter first name" />
                        </div>
                        <div class="form-group auth-form-group-custom mb-3">
                            <i class="mdi mdi-alphabetical text-black auti-custom-input-icon"></i>
                            <label for="userlastname">Last name</label>
                            <input v-model="user.last_name" type="text" class="form-control" id="userlastname"
                                placeholder="Enter last name" />
                        </div>
                        <div class="form-group auth-form-group-custom mb-3">
                            <i class="mdi mdi-alphabetical text-black auti-custom-input-icon"></i>
                            <label for="userlastname">User Name</label>
                            <input v-model="user.user_name" type="text" class="form-control" id="userusername"
                                placeholder="Enter username" />
                        </div>

                    </div>
                    <div class="form-group auth-form-group-custom mb-3">
                        <i class="mdi mdi-email-outline text-black auti-custom-input-icon"></i>
                        <label for="useremail">Email</label>
                        <input v-model="user.email" type="email" class="form-control" id="useremail"
                            placeholder="Enter email" />
                    </div>

                    <div class="form-group auth-form-group-custom mb-3">
                        <i class="mdi mdi-lock text-black auti-custom-input-icon"></i>
                        <label for="userpassword">Password</label>
                        <input v-model="user.password" type="password" class="form-control" id="userpassword"
                            placeholder="Enter password" />
                    </div>
                    <div class="form-group auth-form-group-custom mb-3">
                        <i class="mdi mdi-lock text-black auti-custom-input-icon"></i>
                        <label for="userpassword">Repassword</label>
                        <input v-model="user.password_confirmation" type="password" class="form-control" id="userrepassword"
                            placeholder="Enter repassword" />
                    </div>

                    <div class="text-center">
                        <button class="btn btn-all-add-edit w-md waves-effect waves-light" type="submit">
                            Register
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-2 text-center">
                <p>
                    Already have an account ?
                    <router-link :to="{ name: 'Login' }" class="font-weight-medium text-login-register">Login</router-link>
                </p>
                <p>
                    © 2023. Create 
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
                first_name: '',
                last_name: '',
                user_name: '',
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