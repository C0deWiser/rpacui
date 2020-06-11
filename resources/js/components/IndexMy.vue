<template>
    <div :class="$options.name">

        <div v-loading="loading">
            <h4 v-if="user && user.id">{{ user.name }}</h4>
            <el-form v-if="form.id" status-icon ref="form" :model="form" :rules="rules" label-position="top">
                    <template v-for="field in fields">
                        <template v-if="field.type==='input'">
                            <el-form-item :key="field.name" :label="__(field.label)" :prop="field.name">
                                <el-input v-model="form[field.name]" :placeholder="__(field.placeholder||field.label)"></el-input>
                            </el-form-item>
                        </template>
                        <template v-else-if="field.type==='select'">
                            <el-form-item :key="field.name" :label="__(field.label)" v-loading="!options[field.name]">
                                <el-select v-model="form[field.name]" 
                                        multiple
                                        collapse-tags 
                                        value-key="id"
                                        :placeholder="__(field.placeholder||field.label)"
                                        style="width: 100%;"
                                >
                                    <el-option v-for="option in options[field.name]" 
                                                :key="option['id']" 
                                                :label="option['name']" 
                                                :value="option"
                                    >
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </template>
                        <template v-else-if="field.type==='password'">
                            <el-form-item :key="field.name" :label="__(field.label)" prop="password">
                                <el-input :type="showPass?'text':'password'" v-model="form.password" autocomplete="off">
                                    <i v-show="!!form.password" slot="suffix" class="el-input__icon" :class="{'el-icon-lock': showPass, 'el-icon-view': !showPass}" @click="showPass=!showPass"></i>
                                </el-input>
                            </el-form-item>
                            <el-form-item :key="'confirm_'+field.name" :label="__('confirm_'+field.label)" prop="confirmPassword">
                                <el-input :type="showConfirm?'text':'password'" v-model="form.confirmPassword" autocomplete="off">
                                    <i v-show="!!form.confirmPassword" slot="suffix" class="el-input__icon" :class="{'el-icon-lock': showConfirm, 'el-icon-view': !showConfirm}" @click="showConfirm=!showConfirm"></i>
                                </el-input>
                            </el-form-item>
                        </template>
                        <template v-else>
                            <input type="hidden" :key="field.name" :name="field.name" :value="form[field.name]"/>
                        </template>
                    </template>


                    <el-form-item>
                        <el-button type="primary" @click="onSubmit">{{ __('save') }}</el-button>
                    </el-form-item>
                </el-form>
            </div>

    </div>
</template>

<script>
    export default {
        name: 'index-my',
        data() {

            var validatePass = (rule, value, callback) => {
                // if (value === '') {
                //     callback(new Error( this.__('password_empty') ));
                // } else {
                    if (this.form.confirmPassword !== '') {
                        this.$refs.form.validateField('confirmPassword');
                    }
                    callback();
                // }
            };

            var validateConfirm = (rule, value, callback) => {
                if (this.form.password !== '' && value === '') {
                // if (value === '') {
                    callback(new Error ( this.__('confirm_empty') ));
                } else if (value !== this.form.password) {
                    callback(new Error( this.__('password_mismatch') ));
                } else {
                    callback();
                }
            };

            return {
                loading: true,
                path: 'users/' + this.$user.id, // see axios.create in rpac.js
                fields: [
                    {name: 'id', label: 'id', width: 64, type: 'hidden'},
                    {name: 'name', label: 'username', type: 'input'},
                    {name: 'email', label: 'email', type: 'input'},
                    {name: 'roles', label: 'roles', type: 'select'},
                    {name: 'password', label: 'password', type: 'password'},
                ],
                user: null,
                form: {
                    // id: null,
                    // name: '',
                    // email: '',
                    // roles: [],
                },
                rules: {
                    name: [
                        { required: true, message: this.__('username_required'), trigger: 'blur' }
                    ],
                    email: [
                        { required: true, message: this.__('email_required'), trigger: 'blur' }
                    ],
                    password: [
                        { validator: validatePass, trigger: 'blur' }
                    ],
                    confirmPassword: [
                        { validator: validateConfirm, trigger: 'blur' }
                    ],
                },
                stash: {},
                options: {},
                showPass: false,
                showConfirm: false,
            }
        },
        
        methods: {
            onSubmit(e) {
                this.loading = true;

                this.$refs.form.validate((valid) => {
                    if (valid) {
                        let url = this.path, postData = Object.assign({...this.form}, {_method: 'put'});
          
                        this.$http
                            .post(url, postData)
                            .then(r=>r.data)
                            .then(d=>{
                                if(d.id) {
                                    this.$message.success(this.__('saved'));
                                    this.loading = false;
                                }                                
                            })
                            .catch(e=>{
                                console.error(e, e.response);
                                if(e.response) {
                                    this.$notify(this.$catch(e));
                                }
                            });

                    } else {
                        console.error('error submit!!');
                        this.loading = false;
                        return false;
                    }
                });
                
            },

            getUser() {
                this.loading = true;
                this.$http
                    .get(this.path)
                    .then(r=>r.data)
                    .then(d=>{
                        this.user = d;
                        this.setForm(d);
                        this.loading = false;
                    })
                    .catch(e=>{
                        console.error(e, e.response);
                        if(e.response) {
                            this.$notify(this.$catch(e));
                        }
                    });
            },

            getRoles(){
                this.$http
                    .get('roles')
                    .then(r=>r.data)
                    .then(d=>{
                        this.setOptions('roles', d.data);
                    })
                    .catch(e=>{
                        console.error(e, e.response);
                        if(e.response) {
                            this.$notify(this.$catch(e));
                        }
                    });
            },

            setOptions(name, options) {
                this.options[name] = options;
            },

            setForm(form) {
                this.form = Object.assign({}, this.form, {...form}); // for reactivity!
            },

            initForm() {
                let form = {};
                this.fields.forEach(f => {
                    form[f.name] = f.type==="hidden" ? null : (f.type==="select" ? [] : "");
                    if(f.type==="password") form["confirmPassword"] = "";
                });
                this.setForm(form);
                this.stash = {...this.form};
            },
        },
        mounted() {
            this.getRoles();
            this.initForm();
            this.getUser();
        }
    }
</script>