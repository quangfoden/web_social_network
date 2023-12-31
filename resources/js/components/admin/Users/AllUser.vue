<template>
    <div class="card shadow mb-4">
        <button ref="myModalAddUserBtn" type="button" class="btn btn-all-add-edit btn-primary my-3 mx-3 position-absolute"
            data-bs-toggle="modal" data-bs-target="#ModalAddUser">
            <i class="ri-user-add-fill mr-1"></i> Add user
        </button>
        <div class="card-body">
            <h4 class="card-title text-md-center fs-4 my-3 text-right"> User Manager </h4>
            <div class="table-responsive-lg">
                <table ref="myTable" class="table table-bordered table-striped table-hover display nowrap"></table>
            </div>
        </div>
    </div>
    <!-- update user  -->
    <div class="row">
        <button ref="myModalBtn" type="button" class="btn btn-primary d-none" data-bs-toggle="modal"
            data-bs-target="#exampleModal">
            Launch demo modal
        </button>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog p-lg-5 p-1 pt-5 pt-lg-5 d-flex justify-content-center" role="document">
                <div class="modal-content col-md-7">
                    <div class="word_default p-4">
                        <h3 class="text-center">Edit User</h3>
                        <button ref="btnCloseEditUser" type="button" class="close" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="col-md-12 d-flex flex-column align-items-center">
                            <form @submit.prevent="updateUser" class="col-md-12 p-0 p-md-4">
                                <div class="row">
                                    <div class="col-md-12 p-0 p-md-4">
                                        <div class="form-group mb-3">
                                            <label>Email</label>
                                            <input type="email" placeholder="Enter description" class="form-control"
                                                v-model="userForm.email" disabled required />
                                        </div>
                                        <div class="row">
                                            <div class="form-group mb-3 col-12 col-lg-6">
                                                <label>First Name</label>
                                                <input type="text" placeholder="Enter description" class="form-control"
                                                    v-model="userForm.first_name" required disabled />
                                            </div>
                                            <div class="form-group mb-3 col-12 col-lg-6">
                                                <label>Last Name</label>
                                                <input type="text" placeholder="Enter description" class="form-control"
                                                    v-model="userForm.last_name" required disabled />
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label>Status</label>
                                                    <select class="form-select" v-model="userForm.status" required>
                                                        <option :value="1">OPEN</option>
                                                        <option :value="0">CLOSE</option>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <label>Lock Status</label>
                                                    <select class="form-select" v-model="userForm.is_lock" required>
                                                        <option :value="0">UNLOCK</option>
                                                        <option :value="1">LOCK</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="form-check-label">Roles </label>
                                        <div v-if="showCheckbox" class="form-check" v-for="(role, index) in roles"
                                            :key="`checkbox_${role.id}`">
                                            <input type="checkbox" class="form-check-input" value=""
                                                :key="`checkbox_${role.id}`" :checked="isRolesChecked(userRoles, role.id)"
                                                @click="handleCheckboxClick(role.id, $event.target.checked)
                                                    " />{{ role.name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-all-add-edit py-2 px-5">
                                        Change
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- myModalToProfile  -->
    <div class="row">
        <button ref="myModalToProfile" type="button" class="btn btn-primary d-none" data-bs-toggle="modal"
            data-bs-target="#ModalViewProfile">
            Launch demo modal
        </button>
        <!-- Modal -->
        <div class="modal fade" id="ModalViewProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog p-lg-5 p-1 pt-5 pt-lg-5 d-flex justify-content-center" role="document">
                <div class="modal-content col-md-7">
                    <div class="word_default p-4">
                        <h3 class="text-center mb-4">View profile Now <i class="fas fa-chevron-down"></i></h3>
                        <button ref="btnCloseEditUser" type="button" class="close" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="col-md-12 d-flex gap-2 align-items-center justify-content-center">
                            <router-link :to="{ name: 'Profile User' }" class="btn btn-primary" @click="closeModal">Go to Profile </router-link>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- change password  -->
    <div class="row">
        <button ref="myModalBtnchangepass" type="button" class="btn btn-primary d-none" data-bs-toggle="modal"
            data-bs-target="#ModalChangepass">
            Launch demo modal
        </button>
        <!-- Modal -->
        <div class="modal fade" id="ModalChangepass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog p-lg-5 p-1 pt-5 pt-lg-5 d-flex justify-content-center" role="document">
                <div class="modal-content col-md-7">
                    <div class="word_default p-4">
                        <h3 class="text-center">Change password</h3>
                        <button ref="btnCloseEditUser" type="button" class="close" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="col-md-12 d-flex flex-column align-items-center">
                            <form @submit.prevent="changepassword" class="col-md-12 p-0 p-md-4">
                                <div class="row">
                                    <div class="col-md-12 p-0 p-md-4">
                                        <div class="form-group mb-3">
                                            <label>Email</label>
                                            <input type="email" placeholder="Enter description" class="form-control"
                                                v-model="userPasswwordForm.email" disabled required />
                                        </div>
                                        <div class="row">
                                            <div class="form-group mb-3 col-12 col-lg-6">
                                                <label>First Name</label>
                                                <input type="text" placeholder="Enter description" class="form-control"
                                                    v-model="userPasswwordForm.first_name" required disabled />
                                            </div>
                                            <div class="form-group mb-3 col-12 col-lg-6">
                                                <label>Last Name</label>
                                                <input type="text" placeholder="Enter description" class="form-control"
                                                    v-model="userPasswwordForm.last_name" required disabled />
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>New Password</label>
                                            <input type="password" placeholder="Enter new password..." class="form-control"
                                                v-model="userPasswwordForm.password_new" required />
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Repassword</label>
                                            <input type="password" placeholder="Enter repassword..." class="form-control"
                                                v-model="userPasswwordForm.repassword_new" required />
                                        </div>

                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-all-add-edit py-2 px-5">
                                        Change
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal add user  -->
    <div class="row">
        <!-- Modal -->
        <div class="modal fade" id="ModalAddUser" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered d-flex justify-content-center" role="document">
                <div class="modal-content p-3">
                    <div class="word_default p-4">
                        <h3 class="text-center">Create New User</h3>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="row">
                            <div class="col-md-12 d-flex flex-column align-items-center">
                                <form @submit.prevent="createNewUser" class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="form-group mb-3 co-12 col-lg-6">
                                                    <label>First Name</label>
                                                    <input type="text" placeholder="Enter first name..."
                                                        class="form-control" v-model="newUser.first_name" required />
                                                </div>
                                                <div class="form-group mb-3 col-12 col-lg-6">
                                                    <label>Last Name</label>
                                                    <input type="text" placeholder="Enter last name..." class="form-control"
                                                        v-model="newUser.last_name" required />
                                                </div>
                                                <div class="form-group mb-3 col-12 col-lg-6">
                                                    <label>User Name</label>
                                                    <input type="text" placeholder="Enter username..." class="form-control"
                                                        v-model="newUser.user_name" required />
                                                </div>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label>Email</label>
                                                <input type="email" placeholder="Enter email..." class="form-control"
                                                    v-model="newUser.email" required />
                                            </div>
                                            <div class="form-group mb-3">
                                                <label>Password</label>
                                                <input type="password" placeholder="Enter password..." class="form-control"
                                                    v-model="newUser.password" required />
                                            </div>
                                            <div class="form-group mb-3">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label>Status</label>
                                                        <select class="form-select" v-model="newUser.status" required>
                                                            <option :value="1" selected>OPEN</option>
                                                            <option :value="0">CLOSE</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label>Lock Status</label>
                                                        <select class="form-select" v-model="newUser.is_lock" required>
                                                            <option :value="0" selected>UNLOCK</option>
                                                            <option :value="1">LOCK</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label>Role</label>
                                                <select class="form-select" aria-label="Default select example"
                                                    v-model="newUser.role" required>
                                                    <option value="" selected disabled>Select roles</option>
                                                    <option v-for="role in roles" :key="role.id" :value="`${role.id}`">
                                                        {{ role.name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center mt-2">
                                        <button type="submit" class="btn btn-all-add-edit btn-primary py-2 px-5">
                                            Add User
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style></style>
<script>
import router from "../../../router/index"
import { mapGetters, mapMutations, mapActions } from "vuex";
import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net";
import { createApp, h } from "vue";
import $ from "jquery";

DataTable.use(DataTablesCore);

export default {
    data() {
        return {
            users: [],
            showUserForm: false, // biến đánh dấu hiển thị form thêm mới người dùng
            newUser: {
                // đối tượng người dùng mới
                first_name: "",
                last_name: "",
                user_name: "",
                email: "",
                password: "",
                role: "",
                status: 1,
                is_lock: 0
            },
            userForm: [],
            userPasswwordForm: [],
            userRoles: [],
            roles: [],
            dataTableData: [],
            showCheckbox: true,
        }
    },
    created() {
        axios
            .get("/api/user/roles")
            .then((response) => {
                if (response.data.message === "success") {
                    this.roles = response.data.data;
                }
            })
            .catch((error) => {
                // Nếu không thành công, hiển thị thông báo lỗi
                if (error.response.status == 403) {
                    console.log('403', error)
                    logout();
                }
                if (error.response.status == 401) {
                    console.log('401', error)
                    logout();
                }
                this.$swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: `Error ${error.response.status}: ${error.response.data.message}`,
                });

            });
        this.fetchData()
    },
    methods: {
        ...mapActions(["logout"]),
        closeModal() {
            // Đóng modal khi chuyển đến trang 
            $(".modal-backdrop").remove();
        },
        createNewUser() {
            axios
                .post("/api/user/create-new-user", this.newUser)
                .then((response) => {
                    if (response.data.status === 200 && response.data.success == true) {
                        const newUser = response.data.data.user_created;
                        if (newUser) {
                            this.addRowData(newUser);
                        }
                        this.$swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: `Thêm người dùng mới ${this.newUser.last_name} ${this.newUser.first_name} thành công`,
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                        this.newUser = {
                            first_name: "",
                            last_name: "",
                            email: "",
                            password: "",
                            role: "",
                        };
                        // alert(response.data.message);
                        this.reloadTable()
                    } else {
                        this.$swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 1000,
                        });
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
                })
                .finally(() => (this.loading = false));
        },
        updateUser() {
            axios
                .post(`/api/user/change-role-user/${this.userForm.id}`, this.userForm)
                .then((response) => {
                    if (response.data.status === 200) {
                        this.updateRowData(
                            this.userForm.id,
                            response.data.data.user_update
                        );
                        this.$swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: `Change user ${this.userForm.last_name} ${this.userForm.first_name} Success`,
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                        this.$refs.btnCloseEditUser.click();
                        this.reloadTable();
                    }
                })
                .catch((error) => {
                    if (error.response.status === 403) {
                        this.logout();
                    }
                    if (error.response.status === 401) {
                        this.logout();
                    }
                    this.$swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: `Error ${error.response.status}: ${error.response.data.message}`,
                        timer: this.$config.notificationTimer ?? 3000,
                    });
                })
                .finally(() => (this.loading = false));
        },
        changepassword() {
            axios.post(`/api/user/admin-change-user-pass/${this.userPasswwordForm.id}`, this.userPasswwordForm)
                .then((response) => {
                    if (response.data.status === 200 && response.data.success === true) {
                        this.$swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: `${response.data.message}`,
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                    }
                    else {
                        this.$swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: `${response.data.message}`,
                            showConfirmButton: false,
                            timer: this.$config.notificationTimer ?? 3000,
                        });
                    }
                })
                .catch((error) => {
                    console.log("lỗi changepass rồi", error)
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
                })
                .finally(() => (this.loading = false));
        },
        setColumns() {
            const self = this;
            this.columns = [
                { data: "id", title: "ID" },
                { data: "first_name", title: "First Name" },
                { data: "last_name", title: "Last Name" },
                { data: "email", title: "Email" },
                {
                    data: "id",
                    title: "Roles",
                    class: "columns-list",
                    createdCell: function (cell, cellData, rowData, rowIndex, colIndex) {
                        const app = createApp({
                            render() {
                                return h(
                                    "ul",
                                    { class: 'ul-list mb-0' },
                                    rowData.roles.map((role) => {
                                        return h("li",
                                            {},
                                            [h("p",
                                                { class: "mb-2" },
                                                role.name
                                            )]
                                        )
                                    })
                                )
                            },
                            data() {
                                return {
                                    rowData: rowData,
                                }
                            }
                        })
                        app.mount(cell)
                    }
                },
                {
                    data: "status",
                    title: "Status",
                    render: function (data, type, row) {
                        let statusClass = row.status === 1 ? 'success' : 'danger';
                        let statusText = row.status === 1 ? 'Opening' : 'Closed';
                        return `<span style='cursor:not-allowed' class="btn btn-${statusClass} p-2 mb-0 mt-1">${statusText}</span>`;
                    }
                },
                {
                    data: "is_lock",
                    title: "Lock Status",
                    render: function (data, type, row) {
                        let lockClass = row.is_lock === 1 ? 'danger' : 'success';
                        let lockText = row.is_lock === 1 ? 'Locked' : 'Unlock';
                        return `<span style='cursor:not-allowed' class="btn btn-${lockClass} no-cursor p-2 mb-0 mt-1">${lockText}</span>`;
                    }
                },
                {
                    data: "id",
                    title: "Action",
                    createdCell: function (cell, cellData, rowData, rowIndex, colIndex) {
                        const dataAuth = localStorage.getItem('authUser');
                        const authUserArray = JSON.parse(dataAuth);
                        console.log(authUserArray.id)
                        if (rowData.id !== authUserArray.id) {
                            const app = createApp({
                                render() {
                                    return [
                                        h(
                                            "a",
                                            {
                                                class: "btn btn-all-add-edit me-2",
                                                onClick: () => {
                                                    self.userForm = {
                                                        first_name: rowData.first_name,
                                                        last_name: rowData.last_name,
                                                        email: rowData.email,
                                                        id: rowData.id,
                                                        status: rowData.status,
                                                        is_lock: rowData.is_lock,
                                                    };
                                                    self.showCheckbox = false;
                                                    setTimeout(() => {
                                                        self.showCheckbox = true;
                                                        self.userRoles = rowData.roles;
                                                        self.$refs.myModalBtn.click();
                                                    }, 10); // xử lý chờ 10 ms để userRoles kịp xóa list checkbox cũ
                                                },
                                            },
                                            "Edit"
                                        ),
                                        h(
                                            "a",
                                            {
                                                class: "btn btn-all-add-edit me-2",
                                                onClick: () => {
                                                    self.userPasswwordForm = {
                                                        first_name: rowData.first_name,
                                                        last_name: rowData.last_name,
                                                        password: rowData.password,
                                                        password_new: '',
                                                        repassword_new: '',
                                                        email: rowData.email,
                                                        id: rowData.id,
                                                    };
                                                    self.$refs.myModalBtnchangepass.click();

                                                }
                                            },
                                            "Change password"
                                        )
                                    ];
                                },
                                data() {
                                    return {
                                        rowData: rowData,
                                    };
                                },
                            })
                            app.mount(cell);
                        }
                        else {
                            const app = createApp({
                                render() {
                                    return h(
                                        "a",
                                        {
                                            class: 'btn btn-all-add-edit me-2',
                                            onClick: () => {
                                                self.$refs.myModalToProfile.click();

                                            }
                                        },
                                        "View profile"
                                    )
                                },
                                data() {
                                    return {
                                        rowData: rowData,
                                    };
                                },
                            })
                            app.use(router);
                            app.mount(cell);
                        };
                    },
                },
            ]

        },
        fetchData() {
            axios
                .get("/api/user/allusers")
                .then((response) => {
                    if (
                        response.data.message === "success" &&
                        response.data.status == 200
                    ) {
                        this.roles = response.data.data.roles;
                        this.setColumns();

                        this.dataTableData = response.data.data.users;
                        this.table = $(this.$refs.myTable).DataTable({
                            data: this.dataTableData,
                            columns: this.columns,
                            scrollX: true,
                        });
                    }
                })
                .catch((error) => {
                    console.log("lỗi rồi", error)
                    if (error.response.status == 403) {
                        this.logout();
                    }
                    if (error.response.status == 401) {
                        this.logout();
                    }

                });
        },
        isRolesChecked(roles, roleId) {
            if (Array.isArray(roles)) {
                const isCheck = roles.some((role) => role.id === roleId);
                this.userForm["role_" + roleId] = isCheck;
                return isCheck;
            } else {
                return false;
            }
        },
        handleCheckboxClick(roleId, checked) {
            this.userForm["role_" + roleId] = checked;
        },
        updateRowData(id, userUpdate) {
            let elementToUpdate = this.dataTableData.find((item) => item.id === id);
            if (elementToUpdate) {
                elementToUpdate.roles = userUpdate.roles;
            }

            $(this.$refs.myTable).DataTable().destroy();
            this.table = $(this.$refs.myTable).DataTable({
                data: this.dataTableData,
                columns: this.columns,
                scrollX: true,
            });
        },
        addRowData(userUpdate) {
            this.dataTableData.push(userUpdate);

            $(this.$refs.myTable).DataTable().destroy();
            this.table = $(this.$refs.myTable).DataTable({
                data: this.dataTableData,
                columns: this.columns,
                scrollX: true,
            });
        },
        reloadTable() {
            $(this.$refs.myTable).DataTable().destroy();
            this.fetchData();
        }
    }

}
</script>