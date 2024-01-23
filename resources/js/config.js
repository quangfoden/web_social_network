import axios from 'axios'
axios.defaults.baseURL = 'http://localhost:8000'
const config = {
    notificationTimer: 3000,
}
export default { config }