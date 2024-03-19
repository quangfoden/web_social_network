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
                <router-link :to="{ name: 'Profile Admin' }" class="btn btn-primary text-white p-2"> <i
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
          <div class="card-body ">
            <h4 class="card-title text-center fs-4 mb-3">Change Profile</h4>
            <div class="col-md-12">
              <form @submit.prevent="updateUser">
                <div class="row">
                  <div class="col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                      <label>First Name</label>
                      <input type="text" placeholder="First Name" class="form-control" v-model="UserForm.first_name" />
                    </div>
                    <div class="form-group mb-3">
                      <label>Last Name</label>
                      <input type="text" placeholder="Last Name" class="form-control" v-model="UserForm.last_name" />
                    </div>
                    <div class="form-group mb-3">
                      <label>Phone Number</label>
                      <input type="text" placeholder="Phone Number" class="form-control"
                        v-model="UserForm.phone_number" />
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                      <label>Email</label>
                      <input type="email" placeholder="Email" class="form-control" disabled v-model="UserForm.email" />
                    </div>

                    <div class="form-group mb-3">
                      <label>Address</label>
                      <input type="text" placeholder="Address" class="form-control" v-model="UserForm.address" />
                    </div>
                  </div>
                </div>
                <div class="d-flex justify-content-center">
                  <button type="submit" class="btn btn-all-add-edit py-2 px-4">Save</button>
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
      // UserForm: {},
      languages: []
    }
  },
  computed: {
    authUser() {
      if (this.$store.getters.getAuthUser.id !== undefined) {
        return this.$store.getters.getAuthUser;
      }
      return JSON.parse(localStorage.getItem('authUser'));
    },
    UserForm() {
      return {
        name: this.authUser.name,
        email: this.authUser.email,
        first_name: this.authUser.first_name,
        last_name: this.authUser.last_name,
        phone_number: this.authUser.phone_number,
        address: this.authUser.address,
      }
    },
    ...mapGetters({
      loginResponse: "getLoginResponse",
    }),
  },
  methods: {
    ...mapActions(["storeUpdateUser"]),
    updateUser() {
      axios
        .post('/api/user/update', this.UserForm)
        .then((response) => {
          if (response.data.status === 200) {
            this.storeUpdateUser();
            this.$swal.fire({
              position: 'top-end',
              icon: 'success',
              title: `Update Profile Success`,
              showConfirmButton: false,
              timer: this.$config.notificationTimer ?? 3000
            })
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
            icon: 'error',
            title: 'Oops...',
            text: `Error ${error.response.status}: ${error.response.data.message}`,
          })
        })
        .finally(() => (this.loading = false));
    }
  }
};
</script>
  
<style></style>
  