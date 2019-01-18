<template>
    <div class="container-fluid">
        <div class="row gap-20">
            <div class="col-md-12">
                <div class="bgc-white p-20 bd">
                    <h4 class="c-grey-900 mB-20">Add user</h4>
                    <div class="mT-30">
                        <form class="container" @submit.prevent="addUser">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label>Name</label>
                                    <input v-model="user.name" v-validate data-vv-rules="required" class="form-control" :class="{'is-invalid': errors.has('name') }" name="name" type="text">
                                    <div class="form-control-feedback text-danger" v-show="errors.has('name')">
                                        {{ errors.first('name') }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label>Email</label>
                                    <input v-model="user.email" v-validate data-vv-rules="required|email" class="form-control" :class="{'is-invalid': errors.has('email') }" name="email" type="email">
                                    <div class="form-control-feedback text-danger" v-show="errors.has('email')">
                                        {{ errors.first('email') }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Password</label>
                                    <input v-model="user.password" v-validate data-vv-rules="required" ref="password" class="form-control" :class="{'is-invalid': errors.has('password') }" name="password" type="password">
                                    <div class="form-control-feedback text-danger" v-show="errors.has('password')">
                                        {{ errors.first('password') }}
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Re-password</label>
                                    <input v-validate data-vv-rules="required|confirmed:password" class="form-control" :class="{'is-invalid': errors.has('repassword') }" name="repassword" type="password" data-vv-as="password">
                                    <div class="form-control-feedback text-danger" v-show="errors.has('repassword')">
                                        {{ errors.first('repassword') }}
                                    </div>
                                </div>
                            </div>
                            <label>Role</label>
                            <div class="row pl-5">
                                <div class="col-md-4 mt-2 mb-2" v-for="role in roles" :key="role.id">
                                    <div class="peer">
                                        <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                                        <input :id="'role-'+role.id" type="checkbox" :value="role.id" class="peer" v-model="user.role_id">
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
                user: {
                    name: '',
                    email: '',
                    role_id: '',
                    password: ''
                },
                roles: [],
            }
        },
        created () {
            axios.get('/api/user/create').then(response => {
                this.roles = response.data;
            }).catch(errors => {
                ResponseHelper.error(errors);
            })
        },
        methods: {
            addUser () {
                var app = this;
                app.$validator.validateAll().then(validated => {
                    if (!validated) {
                        toasr.error('All fields are required');
                    } else {
                        axios.post('/api/user', app.user).then(json => {
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
