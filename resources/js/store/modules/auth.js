import axios from 'axios';
import Router from '../../router';
import Swal from 'sweetalert2';
import Config from '../../config';

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
    mutateAuthUser: (state, payload) => (state.authUser = payload),
}
const actions = {
    registerUser({ commit,getters }, userData) {
        return new Promise((resolve, reject) => {
            axios.post('/register', userData)
                .then(response => {
                    commit('mutateRegisterResponse', response.data);
                    localStorage.setItem(
                        'registerResponse',
                        JSON.stringify(response.data)
                    );
                    if (getters.getRegisterResponse.authenticated == true) {
                        if (response.status == 200) {
                            resolve(response);
                            Swal.fire({
                                icon: 'success',
                                title: 'Đăng ký thành công',
                                text: `Error ${getters.getRegisterResponse.response_data[0]}`,
                                showConfirmButton: false,
                                timer: Config.notificationTimer ?? 3000
                            })
                            Router.push('/login');
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi đăng ký',
                            text: `Error ${getters.getRegisterResponse.response_data[0]}`,
                            showConfirmButton: false,
                            timer: Config.notificationTimer ?? 3000
                        })
                    }
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    loginUser({ commit, getters }, loginData) {
        axios
            .post('/login', loginData)
            .then(response => {
                commit('mutateLoginResponse', response.data);
                localStorage.setItem(
                    'loginResponse',
                    JSON.stringify(response.data)
                );

                if (getters.getLoginResponse.response_type == 'success') {
                    if (response.status === 200) {
                        // commit('mutateAuthUser', response.data.data.user);
                        // localStorage.setItem(
                        //     'authUser',
                        //     JSON.stringify(response.data.data.user)
                        // );
                        Swal.fire({
                            icon: 'success',
                            title: 'Đăng nhập thành công',
                            text: `Success ${getters.getLoginResponse.response_data[0]}`,
                            showConfirmButton: false,
                            timer: Config.notificationTimer ?? 3000
                        })
                        Router.push('/user-manage');
                    }

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi đăng nhập',
                        text: `Error ${getters.getLoginResponse.response_data[0]}`,
                        showConfirmButton: false,
                        timer: Config.notificationTimer ?? 3000
                    })
                }
            });

    }
}
export default {
    state,
    getters,
    actions,
    mutations
};
