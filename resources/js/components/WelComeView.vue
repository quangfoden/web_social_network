<template>
    <div class="login_register">
        <div class="home-btn d-none d-sm-block">
            <a href="/">
              
                <i class="mdi mdi-home-variant h2 text-white"></i>
            </a>
        </div>
        <div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="authentication-page-content d-flex align-items-center min-vh-100">
                            <div class="w-100">
                                <div class="row justify-content-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <div class="btn btn-primary"><router-link class="text-black" :to="{ name: 'Login User' }">Login</router-link></div>
                                        <div class="btn btn-danger"><router-link class="text-black" :to="{ name: 'Register User' }">Register</router-link></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="authentication-bg">
                            <div class="bg-overlay"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>