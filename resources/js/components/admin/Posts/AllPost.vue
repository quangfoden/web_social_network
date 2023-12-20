<template>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <DataTable :columns="columns" ajax="/data_post.json" class="display nowrap" width="100%">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>title</th>
                            <th>content</th>
                            <th>author</th>
                            <th>thumbnail</th>
                            <th>status</th>
                            <th>likes</th>
                            <th>comments</th>
                            <th>created_date</th>
                            <th>updated_date</th>
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
                { data: 'title' },
                { data: 'content' },
                { data: 'author' },
                { data: 'thumbnail' },
                { data: 'status' },
                { data: 'likes' },
                { data: 'comments' },
                { data: 'created_date' },
                { data: 'updated_date' },
             
            ],

        }
    },

}
</script>