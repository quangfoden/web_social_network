import axios from 'axios';
import Router from '../../router';
import Swal from 'sweetalert2';
import Config from '../../config';

const state = {
    registrationStatus: null,
    registrationErrors: null,
    loginResponse: {},
    authUser: {},
};

const getters = {
    getRegistrationStatus: state => state.registrationStatus,
    getRegistrationErrors: state => state.registrationErrors,
    getLoginResponse: state => state.loginResponse,
    getAuthUser: state => state.authUser,
}
const mutations = {
    setRegistrationStatus(state, status) {
        state.registrationStatus = status;
    },
    setRegistrationErrors(state, errors) {
        state.registrationErrors = errors;
    },
    mutateLoginResponse: (state, payload) => (state.loginResponse = payload),
    mutateAuthUser: (state, payload) => (state.authUser = payload),
}
const actions = {
    registerUser({ commit }, userData) {
        return new Promise((resolve, reject) => {
            axios.post('/register', userData)
                .then(response => {
                    if (response.data.authenticated == true) {
                        commit('setRegistrationStatus', true);
                        resolve(response);
                    } else {
                        commit('setRegistrationStatus', false);
                        commit('setRegistrationErrors', response.data.response_data);
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
                            text: `Error ${getters.getLoginResponse.response_data[0]}`,
                            showConfirmButton: false,
                            timer: Config.notificationTimer ?? 1000
                        })
                        Router.push('/user-manage');
                    }

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi đăng nhập',
                        text: `Error ${getters.getLoginResponse.response_data[0]}`,
                        showConfirmButton: false,
                        timer: Config.notificationTimer ?? 1000
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
