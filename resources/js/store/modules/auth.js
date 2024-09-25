import axios from 'axios';
import Router from '../../router';
import Swal from 'sweetalert2';
import Config from '../../config';
import { error } from 'jquery';

const state = {
    registrationStatus: null,
    registrationErrors: null,
    loginResponse: {},
    authUser: {},
    registerResponse: {}
};

const getters = {
    getRegisterResponse: state => state.registerResponse,
    getLoginResponse: state => state.loginResponse,
    getAuthUser: state => state.authUser,
}
const mutations = {
    mutateRegisterResponse: (state, payload) => (state.registerResponse = payload),
    mutateLoginResponse: (state, payload) => (state.loginResponse = payload),
    mutateAuthUser: (state, payload) => {
        console.log('Mutating authUser:', payload); // Kiểm tra xem payload có dữ liệu không
        state.authUser = payload;
    }
}
const actions = {

    async registerUser({ commit, getters }, userData) {
        try {
            await axios.get('/sanctum/csrf-cookie');
            const response = await axios.post('/api/register', userData);
            commit('mutateRegisterResponse', response.data);
            localStorage.setItem('registerResponse', JSON.stringify(response.data));
            if (getters.getRegisterResponse.authenticated === true) {
                Swal.fire({
                    icon: 'success',
                    title: 'Đăng ký thành công',
                    text: `Kiểm tra email của bạn để xác thực.`,
                    showConfirmButton: false,
                    timer: Config.notificationTimer ?? 5000
                });
                Router.push('/login');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi đăng ký',
                    text: `${getters.getRegisterResponse.response_data[0]}`,
                    showConfirmButton: false,
                    timer: Config.notificationTimer ?? 5000
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi đăng ký',
                text: error.response?.data?.response_data?.[0] || 'Có lỗi xảy ra, vui lòng thử lại sau.',
                showConfirmButton: false,
                timer: Config.notificationTimer ?? 5000
            });
        }
    },

    loginUser({ commit, getters }, loginData) {
        return new Promise((resolve, reject) => {
            axios.get('/sanctum/csrf-cookie')
                .then(() => {
                    axios.post('/api/login', loginData)
                        .then(response => {
                            commit('mutateLoginResponse', response.data);
                            localStorage.setItem('loginResponse', JSON.stringify(response.data));
                            if (getters.getLoginResponse.response_type == 'success' && getters.getLoginResponse.status == 1) {
                                axios.get('/api/user')
                                    .then(response => {
                                        if (response.status === 200 && getters.getLoginResponse.role.includes('admin')) {
                                            commit('mutateAuthUser', response.data.data.user);
                                            localStorage.setItem('authUser', JSON.stringify(response.data.data.user));
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Chào mừng Admin',
                                                showConfirmButton: false,
                                                timer: Config.notificationTimer ?? 5000
                                            });
                                            Router.push('/admin');
                                            resolve(response.data.data.user); // Trả về user khi đăng nhập thành công
                                        } else if (getters.getLoginResponse.response_type == 'success' && getters.getLoginResponse.role == 'user') {
                                            commit('mutateAuthUser', response.data.data.user);
                                            localStorage.setItem('authUser', JSON.stringify(response.data.data.user));
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Đăng nhập thành công',
                                                showConfirmButton: false,
                                                timer: Config.notificationTimer ?? 5000
                                            });
                                            Router.push('/');
                                            resolve(response.data.data.user);
                                        }
                                    })
                                    .catch(error => {
                                        reject(error);
                                    });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi đăng nhập',
                                    text: `${getters.getLoginResponse.response_data[0]}`,
                                    showConfirmButton: false,
                                    timer: Config.notificationTimer ?? 5000
                                });
                                reject(new Error('Authentication failed'));
                            }
                        })
                        .catch(error => {
                            console.log(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi đăng nhập',
                                text: error.response?.data?.response_data?.[0] || 'Có lỗi xảy ra, vui lòng thử lại sau.',
                                showConfirmButton: false,
                                timer: Config.notificationTimer ?? 5000
                            });
                            reject(error);
                        });
                });
        });
    },

    loginWithFaceId({ commit, getters }, userData) {
        axios.get('/sanctum/csrf-cookie').then(() => {
            axios.post('/api/login-faceid', userData)
                .then(response => {
                    commit('mutateLoginResponse', response.data);
                    localStorage.setItem(
                        'loginResponse',
                        JSON.stringify(response.data)
                    );
                    console.log(getters.getLoginResponse.response_type);
                    console.log(getters.getLoginResponse.status);
                    if (getters.getLoginResponse.response_type == 'success' && getters.getLoginResponse.status == 1) {
                        axios.get('/api/user')
                            .then(response => {
                                if (response.status === 200 && getters.getLoginResponse.role.includes('admin')) {

                                    commit('mutateAuthUser', response.data.data.user);
                                    localStorage.setItem(
                                        'authUser',
                                        JSON.stringify(response.data.data.user)
                                    );
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Chào mừng Admin',
                                        text: `Success ${getters.getLoginResponse.response_data[0]}`,
                                        showConfirmButton: false,
                                        timer: Config.notificationTimer ?? 5000
                                    })
                                    Router.push('/admin');
                                }
                                else if (getters.getLoginResponse.response_type == 'success' && getters.getLoginResponse.role == 'user') {
                                    commit('mutateAuthUser', response.data.data.user);

                                    localStorage.setItem(
                                        'authUser',
                                        JSON.stringify(response.data.data.user)
                                    );
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Đăng nhập thành công',
                                        text: `Success ${getters.getLoginResponse.response_data[0]}`,
                                        showConfirmButton: false,
                                        timer: Config.notificationTimer ?? 5000
                                    })
                                    Router.push('/');
                                }
                            })
                    }
                    else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi đăng nhập',
                            showConfirmButton: false,
                            timer: Config.notificationTimer ?? 5000
                        })
                    }
                })
                .catch(error => {
                    console.log(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi đăng nhập',
                        text: `${error.response.data.message}`,
                        showConfirmButton: false,
                        timer: Config.notificationTimer ?? 5000
                    })
                })
        })
    },
    setAuthuser({ commit, getters }) {
        axios.get('/api/user')
            .then(response => {
                commit('mutateAuthUser', response.data.data.user);
                localStorage.setItem(
                    'authUser',
                    JSON.stringify(response.data.data.user)
                );
            })
    },
    logout() {
        axios.post('/api/user/logout').then(() => {
            localStorage.removeItem('loginResponse');
            localStorage.removeItem('authUser');
            Swal.fire({
                icon: 'success',
                title: 'Đăng xuất thành công',
                showConfirmButton: false,
                timer: Config.notificationTimer ?? 5000
            })
            Router.push('/login');
        });
    },
    storeUpdateUser({ commit, getters }) {
        axios.get('/sanctum/csrf-cookie').then(() => {
            axios.get('/api/user').then(response => {
                if (response.data.status === 200) {
                    commit('mutateAuthUser', response.data.data.user);
                    localStorage.setItem(
                        'authUser',
                        JSON.stringify(response.data.data.user)
                    );
                }
            })
        });
    }
}
export default {
    state,
    getters,
    actions,
    mutations
};
