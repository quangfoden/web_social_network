import axios from 'axios';
import router from './router'; // Đảm bảo bạn đã cấu hình Vue Router

// Thiết lập URL cơ sở cho Axios
axios.defaults.baseURL = 'http://localhost:8000';

axios.interceptors.response.use(

    response => {
        return response;
    },
    error => {
        if (error.response && error.response.status === 401) {
            router.push({name:'Login User'})
            localStorage.removeItem('loginResponse');
            localStorage.removeItem('authUser');
        }
        return Promise.reject(error);
    }
);

const config = {
    notificationTimer: 3000,
};

export default { config };
