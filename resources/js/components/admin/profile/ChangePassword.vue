<template>
  <div class="profile">
    <div class="row">
      <div class="col-md-4">
        <div class="card show border border-0 profile-card-body">
          <div class="card-body p-0">
            <div class="row col-md-12 m-0 p-0 overflow-hidden background-avatar mb-3">
              <img src="../../../../assets/images/avatar.gif" alt="" class="p-0 rounded-top">
            </div>
            <div class="avatar row col-md-12 m-0 p-0 d-flex justify-content-center position-absolute">
              <img src="" alt="">
              <!-- <h6 class="text-center py-2">{{ authUser.name }}</h6> -->
            </div>
            <div class="description-profile d-flex gap-2">
              <div class="form-group">
                <router-link :to="{ name: 'Profile User' }" class="btn btn-primary text-white p-2"> <i
                    class="fas fa-user px-2"></i>Profile</router-link>
              </div>
              <div class="form-group pb-3">

                <router-link :to="{ name: 'Change Password' }" class="btn btn-danger text-white p-2"> <i
                    class="fas fa-lock px-2"></i>Change Password</router-link>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <div class="card show border border-0 profile-card-body">
          <div class="card-body">
            <h4 class="card-title text-center fs-4">Change Password</h4>
            <div class="col-md-12">
              <form @submit.prevent="updatePassword">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="label-item">Old Password</label>
                      <input type="password" class="form-control" required v-model="userForm.password" />
                    </div>
                    <div class="form-group">
                      <label class="label-item">New Password</label>
                      <input type="password" class="form-control" required v-model="userForm.password_new" />
                    </div>
                    <div class="form-group">
                      <label class="label-item">Re-enter password</label>
                      <input type="password" class="form-control" required v-model="userForm.repassword_new" />
                    </div>
                  </div>
                </div>
                <div class="d-flex justify-content-center">
                  <button type="submit" class="btn btn-all-add-edit py-2 px-4">
                    Save
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
  
<script>
import { mapGetters, mapMutations, mapActions } from "vuex";
export default {
  data() {
    return {
      userForm: {},
    };
  },
  methods: {
    ...mapActions(["logout"]),
    updatePassword() {
      axios
        .post(`/api/user/change-password`, this.userForm)
        .then((response) => {
          if (response.data.status === 200) {
            if (response.data.success == true) {
              this.$swal.fire({
                position: "top-end",
                icon: "success",
                title: `${response.data.message}`,
                showConfirmButton: false,
                timer: this.$config.notificationTimer ?? 1000,
              });
            }
            else{
              this.$swal.fire({
                position: "top-end",
                icon: "error",
                title: `${response.data.message}`,
                showConfirmButton: false,
                timer: this.$config.notificationTimer ?? 1000,
              });
            }
          }
        })
        .catch((error) => {
          if (error.response.status == 403) {
            this.logout();
          }
          if (error.response.status == 401) {
            this.logout();
          }
          this.$swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Error ${error.response.status}: ${error.response.data.message}`,
          });
          // alert(`Error ${error.response.status}: ${error.response.data.message}`);
        })
        .finally(() => (this.loading = false));
    },
  }
};
</script>
  