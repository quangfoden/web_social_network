<template>
    <div class="col-12">
        <div class="tab-pane" id="edit-profile">
            <div class="set-title">
                <h5>Chỉnh sửa thông tin cá nhân</h5>
                <span>Mọi người trên Imnotify sẽ biết tới bạn</span>
            </div>
            <div class="setting-meta">
                <div class="change-photo">
                    <figure>
                        <img width="100" :src="form.avatar || '/images/avatar.png'" alt="Avatar" />
                    </figure>
                    <div class="edit-img">
                        <form class="edit-phto">
                            <label class="fileContainer">
                                <i class="fa fa-camera-retro"></i>
                                Thay đổi ảnh đại diện
                                <input type="file" @change="onFileChange">
                            </label>
                        </form>
                    </div>
                </div>
            </div>
            <div class="stg-form-area">
                <form @submit.prevent="updateProfile" class="c-form">
                    <div>
                        <label>First name</label>
                        <input type="text" v-model="form.first_name" placeholder="Jack Carter">
                    </div>
                    <div>
                        <label>Last name</label>
                        <input type="text" v-model="form.last_name" placeholder="Carter">
                    </div>
                    <div>
                        <label>Tiểu sử cá nhân</label>
                        <textarea v-model="form.bio" rows="3" placeholder="Write something about yourself"></textarea>
                    </div>
                    <div class="uzer-nam">
                        <label>User Name</label>
                        <input type="text" v-model="form.user_name" placeholder="jackcarter4023">
                    </div>
                    <div>
                        <label>Giới tính</label>
                        <div class="form-radio">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="male" v-model="form.gender" checked><i
                                        class="check-box"></i>Nam
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" value="female" v-model="form.gender"><i class="check-box"></i>Nữ
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" value="other" v-model="form.gender"><i class="check-box"></i>Khác
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label>Ngày sinh</label>
                        <input type="date" v-model="form.birthdate" placeholder="Chọn ngày sinh" />
                    </div>

                    <div>
                        <label>Số diện thoại</label>
                        <input type="text" v-model="form.phone_number" placeholder="Ex. (123) 456-7890">
                    </div>
                    <div>
                        <label>Địa chỉ</label>
                        <input type="text" v-model="form.address" placeholder="Ex. San Francisco, CA">
                    </div>
                    <div>
                        <label>Nghề nghiệp</label>
                        <input type="text" v-model="form.occupation" placeholder="Ex. Developer">
                    </div>
                    <div>
                        <button type="button" v-ripple @click="cancel">Bỏ qua</button>
                        <button type="submit" v-ripple>Lưu</button>
                    </div>
                </form>
            </div>
        </div><!-- edit profile -->
    </div>
</template>

<script>
import { mapState } from 'vuex';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';
export default {
    data() {
        return {
            userId: null,
            form: {
                avatar:'',
                first_name: '',
                last_name: '',
                user_name: '',
                bio: '',
                gender: 'Nam',
                birthdate: '',
                phone_number: '',
                address: '',
                occupation: '',
            },
        };
    },
    computed: {
        ...mapState('post', ['user']),
        authUser() {
            if (this.$store.getters.getAuthUser.id !== undefined) {
                return this.$store.getters.getAuthUser;
            }
            return JSON.parse(localStorage.getItem('authUser'));
        },
    },
    methods: {
        onFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.selectedFile = file; // Lưu tệp đã chọn vào biến
                console.log('Selected file:', file);
                this.form.avatar = URL.createObjectURL(file);
            }
        },
        updateProfile() {
            const formData = new FormData();

            // Chỉ thêm các trường vào FormData nếu chúng có giá trị
            if (this.form.first_name) {
                formData.append('first_name', this.form.first_name);
            }
            if (this.form.last_name) {
                formData.append('last_name', this.form.last_name);
            }
            if (this.form.user_name) {
                formData.append('user_name', this.form.user_name);
            }
            if (this.form.birthdate) {
                formData.append('birthdate', this.form.birthdate);
            }
            if (this.form.gender) {
                formData.append('gender', this.form.gender);
            }
            if (this.form.phone_number) {
                formData.append('phone_number', this.form.phone_number);
            }
            if (this.form.address) {
                formData.append('address', this.form.address);
            }
            if (this.form.occupation) {
                formData.append('occupation', this.form.occupation);
            }
            if (this.form.bio) {
                formData.append('bio', this.form.bio);
            }

            if (this.selectedFile) {
                formData.append('avatar', this.selectedFile);
            }

            // Gọi API để cập nhật thông tin người dùng
            axios.post(`/api/user/update-profile/${this.userId}`, formData)
                .then(response => {
                    toastr.success('Cập nhật thông tin cá nhân thành công !', 'Thông báo', {
                        positionClass: 'toast-bottom-left',
                        backgroundColor: '#4CAF50',
                        progressBar: true,
                        closeButton: true,
                        timeOut: 10000,
                    });
                    localStorage.setItem('authUser', JSON.stringify(response.data.user));
                    window.location.reload()
                  console.log('Profile updated successfully:', response.data.user);
                })
                .catch(error => {
                    toastr.error('Đã xảy ra lỗi vui lòng thử lại!', 'Thông báo', {
                        positionClass: 'toast-bottom-left',
                        backgroundColor: '#F44336',
                        progressBar: true,
                        closeButton: true,
                        timeOut: 10000,
                    });
                    console.error('Error updating profile:', error.response.data);
                });
        }
        ,
        cancel() {
            this.loadUserData();
            console.log('Cancelled');
        },
        loadUserData() {
            if (this.authUser) {
                
                this.form.avatar = this.authUser.avatar;
                this.form.first_name = this.authUser.first_name;
                this.form.last_name = this.authUser.last_name;
                this.form.user_name = this.authUser.user_name;
                this.form.bio = this.authUser.bio;
                this.form.gender = this.authUser.gender;
                this.form.birthdate = this.authUser.birthdate;
                this.form.phone_number = this.authUser.phone_number;
                this.form.address = this.authUser.address;
                this.form.occupation = this.authUser.occupation;
            }
        },
    },
    watch: {
        '$route.params.id': {
            immediate: true,
            handler(newId) {
                this.userId = parseInt(newId);
                this.loadUserData();
            }
        }
    },

}
</script>
