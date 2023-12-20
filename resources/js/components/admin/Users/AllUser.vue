<template>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <DataTable :columns="columns" ajax="/data_user.json" class="display nowrap" width="100%">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>username</th>
                            <th>email</th>
                            <th>password</th>
                            <th>avatar</th>
                            <th>roles</th>
                        </tr>
                    </thead>

                </DataTable>
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

import $ from "jquery";

DataTable.use(DataTablesCore);

export default {
    components: {
        DataTable
    },
    data() {
        return {
            columns: [
                { data: 'id' },
                { data: 'username' },
                { data: 'email' },
                { data: 'password' },
                { data: 'avatar' },
                { data: 'roles' },
             
            ],

        }
    },

}
</script>