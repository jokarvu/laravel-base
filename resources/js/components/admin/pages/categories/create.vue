<template>
    <div class="container-fluid">
        <div class="row gap-20">
            <div class="col-md-12">
                <div class="bgc-white p-20 bd">
                    <h4 class="c-grey-900 mB-20">Add category</h4>
                    <div class="mT-30">
                        <form class="container" @submit.prevent="addCategory">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label>Name</label>
                                    <input v-model="category.name" v-validate data-vv-rules="required" class="form-control" :class="{'is-invalid': errors.has('name') }" name="name" type="text">
                                    <div class="form-control-feedback text-danger" v-show="errors.has('name')">
                                        {{ errors.first('name') }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label>Description</label>
                                    <input v-model="category.description" v-validate data-vv-rules="required" class="form-control" :class="{'is-invalid': errors.has('des') }" name="des" type="text">
                                    <div class="form-control-feedback text-danger" v-show="errors.has('des')">
                                        {{ errors.first('des') }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label>Category</label>
                                    <select v-model="category.category_id" v-validate data-vv-rules="required" class="form-control" :class="{'is-invalid': errors.has('des') }">
                                        <option value="0" selected>None</option>
                                        <option v-for="category in categories" :key="category.id" :value="category.id">{{category.name}}</option>
                                    </select>
                                    <div class="form-control-feedback text-danger" v-show="errors.has('des')">
                                        {{ errors.first('des') }}
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
                category: {
                    name: '',
                    description: '',
                    category_id: 0
                },
                categories: [],
            }
        },
        created () {
            axios.get('/api/category/create').then(response => {
                this.categories = response.data;
            }).catch(errors => {
                ResponseHelper.error(errors);
            })
        },
        methods: {
            addCategory () {
                var app = this;
                app.$validator.validateAll().then(validated => {
                    if (!validated) {
                        toasr.error('All fields are required');
                    } else {
                        axios.post('/api/category', app.category).then(json => {
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
