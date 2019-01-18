<template>
    <div class="container-fluid">
        <div class="row gap-20">
            <div class="col-md-12">
                <div class="bgc-white p-20 bd">
                    <h4 class="c-grey-900 mB-20">Update Permission</h4>
                    <div class="mT-30">
                        <form class="container" @submit.prevent="updatePermission">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label>Name</label>
                                    <input v-model="permission.name" v-validate data-vv-rules="required" class="form-control" :class="{'is-invalid': errors.has('name') }" name="name" type="text">
                                    <div class="form-control-feedback text-danger" v-show="errors.has('name')">
                                        {{ errors.first('name') }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label>Description</label>
                                    <input v-model="permission.description" v-validate data-vv-rules="required" class="form-control" :class="{'is-invalid': errors.has('des') }" name="des" type="text">
                                    <div class="form-control-feedback text-danger" v-show="errors.has('des')">
                                        {{ errors.first('des') }}
                                    </div>
                                </div>
                            </div>
                            <label>Role</label>
                            <div class="row pl-5">
                                <div class="col-md-4 mt-2 mb-2" v-for="role in roles" :key="role.id">
                                    <div class="peer">
                                        <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                                        <input :id="'role-'+role.id" type="checkbox" :value="role.id" class="peer" v-model="checked">
                                        <label :for="'role-'+role.id" class=" peers peer-greed js-sb ai-c">
                                            <span class="peer peer-greed">{{role.name}}</span>
                                        </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-row-reverse clearfix">
                                    <button class="btn btn-primary mR-5" type="submit"><i class="fa fa-download"></i> Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import 'tinymce';
    export default {
        data () {
            return {
                permission: {
                    id: '',
                    name: '',
                    description: ''
                },
                roles: [],
                checked: []
            }
        },
        created () {
            var slug = this.$route.params.slug;
            axios.get('/api/permission/'+slug+'/edit').then(response => {
                this.permission = response.data.permission;
                this.roles = response.data.roles;
                response.data.checked.forEach(role => {
                    this.checked.push(role.id);
                });
            }).catch(error => {
                ResponseHelper.error(error);
            })
        },
        methods: {
            updatePermission () {
                var app = this;
                app.$validator.validateAll().then(validated => {
                    if (!validated) {
                        toasr.error('All fields are required');
                    } else {
                        axios.put('/api/permission/' + app.permission.id, {'name': app.permission.name, 'description': app.permission.description, 'checked': app.checked}).then(json => {
                            toastr.success(json.data.message);
                            app.$router.go(-1);
                        }).catch(errors => {
                            ResponseHelper.error(errors);
                        })
                    }
                })
            }      
        }
    }
</script>
