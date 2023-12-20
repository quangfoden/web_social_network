<template>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <DataTable :columns="columns" ajax="/datacomment.json" class="display nowrap" width="100%">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Post ID</th>
                            <th>Content Comment</th>
                            <th>Likes Comment</th>
                            <th>status Comment</th>
                            <th>Author Avatar</th>
                            <th>Parent Comment ID</th>
                            <th>Created Date</th>
                            <th>Updated Date</th>
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
                { data: 'post_id' },
                { data: 'content_comment' },
                { data: 'likes_comment' },
                { data: 'status_comment' },
                { data: 'author_avatar' },
                { data: 'parent_comment_id' },
                { data: 'created_date' },
                { data: 'updated_date' },
            ],

        }
    },

}
</script>