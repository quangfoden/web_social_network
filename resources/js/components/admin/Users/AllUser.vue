<template>
    <div class="card shadow mb-4">
        <router-link :to="{ name: 'Add User' }" class="btn btn-all-add-edit my-3 mx-3 position-absolute"> Add user
        </router-link>
        <div class="card-body">
            <h4 class="card-title text-md-center fs-4 my-3 text-right"> User Manager </h4>
            <div class="table-responsive-lg">
                <table ref="myTable" class="table table-bordered table-striped table-hover display nowrap"></table>
            </div>
        </div>
    </div>
</template>
<style>
@import 'datatables.net-dt';
</style>
<script>
import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net";
import axios from 'axios';
import $ from "jquery";

DataTable.use(DataTablesCore);

export default {
    // components: {
    //     DataTable
    // },
    data() {
        return {
            dataTableData: [],
        }
    },
    mounted() {
        this.fetchData()
    },
    methods: {
        fetchData() {
            this.axios
                .get("/alluser")
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
                    if (error.response.status == 403) {
                        this.logout();
                    }
                    if (error.response.status == 401) {
                        this.logout();
                    }

                });
        },
    }

}
</script>