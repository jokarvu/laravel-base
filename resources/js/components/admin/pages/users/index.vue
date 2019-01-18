<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="bgc-white bd bdrs-3 p-20 mB-20">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="c-grey-900 mB-20">User</h4>
                        </div>
                        <div class="col-md-6">
                            <router-link to="/admin/user/create" class="btn btn-primary float-right"><i class="fa fa-plus"></i> Add user</router-link>
                        </div>
                    </div>

                    <table id="user-index" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th scope="col">#ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Created</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users" :key="user.id" :id="'user-'+user.id">
                                <th scope="row" class="text-center">{{user.id}}</th>
                                <td>{{user.name}}</td>
                                <td>{{user.email}}</td>
                                <td>{{user.role.name}}</td>
                                <td>{{user.created_at}}</td>
                                <td class="text-center">
                                    <router-link :to="{name: 'UserView', params: {slug: user.name}}" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></router-link>
                                    <router-link :to="{name: 'UserUpdate', params: {slug: user.name}}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></router-link>
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" :data-target="'#usermodal-'+user.id">
                                        <i class="fa fa-trash-alt"></i>
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" :id="'usermodal-'+user.id" tabindex="-1" user="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" user="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Delete user</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure?
                                                </div>
                                                    <div class="modal-footer">
                                                    <button type="button" @click="DeleteUser(user.id)" class="btn btn-danger" data-dismiss="modal">Yes</button>
                                                    <button type="button" class="btn btn-info" data-dismiss="modal">No</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    export default {
        data() {
            return {
                vue_updated: 0,
                users: [],
                // table: null
            };
        },
        created () {
            var app = this;
            // Get product data
            axios.get("/api/user").then(function(json) {
                app.users = json.data;
            }).catch(function(errors) {
                ResponseHelper.error(errors);
            });
        },
        updated() {
            var app = this;
            app.table = $("#user-index").DataTable({
                stateSave: true,
            });
        },
        methods : {
            DeleteUser (id) {
                var app = this;
                axios.delete('/api/user/' + id).then(response => {
                    toastr.success(response.data.message);
                    app.table.rows('#user-'+id).remove().draw(false);
                }).catch(errors => {
                    ResponseHelper.error(errors);
                });
            },
        }
    };
</script>
