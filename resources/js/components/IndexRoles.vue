<template>
    <div :class="$options.name">

        <div class="table-actions">
            <div class="table-actions__create">
                <el-button icon="el-icon-plus" @click="openForm(form)">{{ __('add_role') }}</el-button>
            </div>
        </div>

        <div class="table-wrapper">
            <el-table
                :data="table"
                v-loading="loading"
                v-infinite-scroll="load"
                infinite-scroll-disabled="disabled"
                @row-click="rowClick"
            >
                <el-table-column v-for="column in columns"
                                :key="column.name"
                                :prop="column.name"
                                :fixed="column.fixed||false"
                                :align="column.align||'left'"
                                :label="__(column.label)"
                                :width="column.width||'auto'"
                                :min-width="column.min_width||''"
                >
                    <template slot-scope="scope">
                        <span v-if="column.name==='roles'">
                            <el-tag v-for="role in scope.row[column.name]" :key="role.id">{{ role.name }}</el-tag>
                        </span>
                        <span v-else>{{ scope.row[column.name] }}</span>
                    </template>
                </el-table-column>
            </el-table>
        </div>

        <el-dialog :visible="!!role"
                   :fullscreen="true"
                   center
                   :title="role && role.id ? role.name : __('add_role')"
                   :before-close="closeForm"
        >
            <el-form ref="form" :model="form" :rules="rules" label-width="120px">
                <template v-for="field in fields">
                    <template v-if="field.type==='input'">
                        <el-form-item :key="field.name" :label="__(field.label)" :prop="field.name">
                            <el-input v-model="form[field.name]" :placeholder="__(field.placeholder||field.label)"></el-input>
                        </el-form-item>
                    </template>
                    <template v-else>
                        <input type="hidden" :key="field.name" :name="field.name" :value="form[field.name]"/>
                    </template>
                </template>

                <el-form-item>
                    <div style="display: flex;">
                        <div>
                            <el-button type="primary" @click="onSubmit">{{ __('save') }}</el-button>
                            <el-button @click="closeForm">{{ __('cancel') }}</el-button>
                        </div>
                        <div v-if="role && role.id" style="margin-left: auto;">
                            <el-popconfirm confirmButtonType="danger"
                                           :confirmButtonText="__('ok')"
                                           :cancelButtonText="__('cancel')"
                                           :title="__('confirm_delete')"
                                           icon="el-icon-info"
                                           iconColor="red"
                                           v-on="{onConfirm:_=>{onDelete(role.id)},onCancel:_=>{return false;}}"
                            >
                                <el-button slot="reference" type="danger" icon="el-icon-delete" size="mini" plain>
                                    {{ __('delete') }}
                                </el-button>
                            </el-popconfirm>
                        </div>
                    </div>
                </el-form-item>

            </el-form>

        </el-dialog>

    </div>
</template>

<script>
    export default {
        name: 'index-roles',
        data() {
            return {
                loading: true,
                path: 'roles',
                table: [],
                columns: [
                    {name: 'id', label: 'id', width: 220, type: 'input'},
                    {name: 'name', label: 'name', type: 'input'},
                    {name: 'created_at', type: 'hidden'},
                ],
                page: 0,
                disabled: false,
                role: null,
                fields: {},
                form: {
                    // id: null,
                    // name: '',
                },
                rules: {
                    id: [
                        { required: true, message: this.__('id_required'), trigger: 'blur' }
                    ],
                    name: [
                        { required: true, message: this.__('name_required'), trigger: 'blur' }
                    ],
                },
                stash: {},
                q: this.$route.query.q || '',
            }
        },
        
        methods: {
            onDelete(id) {
                this.loading = true;
                
                this.$http
                    .delete(this.path + '/' + id)
                    .then(r=>r.data)
                    .then(d=>{
                        if(d.id) {
                            this.$message.success( this.__('deleted') );
                            this.loading = false;
                            location.reload();
                        }
                        // TODO else ERROR
                    })
                    .catch(e=>{
                        console.error(e, e.response);
                        if(e.response) {
                            this.$notify(this.$catch(e));
                        }
                    });
            },

            onSubmit(e) {
                this.loading = true;

                let url = this.path, postData = Object.assign({...this.form}, {_method: 'post'});
                if(postData.created_at) {
                    url += '/' + postData.id;
                    postData._method = 'put';
                    delete postData.created_at;
                }

                this.$http
                    .post(url, postData)
                    .then(r=>r.data)
                    .then(d=>{
                        if(d.id) {
                            this.$message.success( this.__('saved') );
                            this.loading = false;
                            location.reload();
                        }
                        // TODO else ERROR
                    })
                    .catch(e=>{
                        console.error(e, e.response);
                        if(e.response) {
                            this.$notify(this.$catch(e));
                        }
                    });
            },

            closeForm() {
                this.role = null;
                this.setForm(this.stash);
            },

            openForm(role = null) {
                if(role && role.name!==undefined) {
                    this.role = role;
                    this.setForm(role);
                }
                // TODO else ERROR
            },

            load() {
                this.loading = true;

                let params = Object.assign({...this.$route.query}, {page: ++this.page});

                this.$http
                    .get(this.path, {params})
                    .then(r=>r.data)
                    .then(d=>{
                        this.checkMeta(d);//.meta);
                        this.setData(d.data);
                        this.scrollTop();
                        //this.$emit('ready', d);
                    })
                    .catch(e=>{
                        console.error(e, e.response);
                        if(e.response) {
                            this.$notify(this.$catch(e));
                        }
                    });
            },

            checkMeta(m) {
                if(m && m.current_page === m.last_page) {
                    this.disabled = true;
                }
            },

            setData(data) {
                this.table = this.table.concat(data);
                this.loading = false;
            },

            scrollTop() {
                let wrapper = this.$$('.table-wrapper');
                if(wrapper) wrapper.scrollTop -= 1;
            },

            rowClick(role) {
                this.role = role;
                this.setForm(role);
            },

            setForm(form) {
                this.form = Object.assign({}, this.form, {...form}); // reactivity!
            },

            initForm() {
                this.fields = this.columns.filter(col => col.type);
                this.fields.forEach(f => {
                    this.form[f.name] = f.type==="hidden" ? null : (f.type==="select" ? [] : "");
                });
                this.stash = {...this.form};
            },
        },
        mounted() {
            this.initForm();
        }
    }
</script>