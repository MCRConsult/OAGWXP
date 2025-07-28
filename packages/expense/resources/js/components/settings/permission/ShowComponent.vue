<template>
    <div v-loading="loading">
        <form id="update-form">
            <div class="col-12">
                <div class="row">
                    <div class="offset-md-4 col-md-4">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> กลุ่มสิทธิ์เข้าถึงการใช้งาน <span class="text-danger"> * </span></strong>
                            </label><br>
                            <el-select name="group" v-model="permission.group" :value="permission.group" placeholder="" ref="perm_group">
                                <el-option
                                    v-for="group in permissionGroups"
                                    :key="group.value"
                                    :label="group.label"
                                    :value="group.value"
                                >
                                </el-option>
                            </el-select>
                            <div id="el_explode_perm_group" class="text-danger text-left"></div>
                        </div>
                    </div>  
                </div>
                <div class="row">
                    <div class="offset-md-4 col-md-4">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> สิทธิ์เข้าถึงการใช้งาน <span class="text-danger"> * </span></strong>
                            </label><br>
                            <el-select name="code" v-model="permission.code" :value="permission.code" placeholder="" ref="perm_code">
                                <el-option
                                    v-for="code in permissionCodes"
                                    :key="code.value"
                                    :label="code.label"
                                    :value="code.value"
                                >
                                </el-option>
                            </el-select>
                            <div id="el_explode_perm_code" class="text-danger text-left"></div>
                        </div>
                    </div>  
                </div>
                <div class="row">
                    <div class="offset-md-4 col-md-4">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> คำอธิบายสิทธิ์เข้าถึงการใช้งาน <span class="text-danger"> * </span></strong>
                            </label><br>
                            <el-input name="description" 
                                style="width: 100%;"
                                v-model="permission.description" 
                                :value="permission.description" 
                                ref="perm_description"
                            />
                            <div id="el_explode_perm_description" class="text-danger text-left"></div>
                        </div>
                    </div>  
                </div>
                <div class="row">
                    <div class="offset-md-4 col-md-4">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> สถานะ </strong>
                            </label><br>
                            <el-checkbox name="status" 
                                v-model="permission.status" 
                                :value="permission.status"
                            />  ใช้งานอยู่ ?
                        </div>
                    </div>  
                </div>
            </div>
            <div align="center">
                <button type="button" class="btn btn-save btn-sm mr-2" @click.prevent="update"> บันทีก </button>
                <a :href="pFormUrl" type="button" class="btn btn-danger btn-sm"> ยกเลิก </a>
            </div>
        </form>
    </div>
</template>

<script>
import Swal from 'sweetalert2';

export default {
    props: ['pFormUrl', 'pPermission'],
    data() {
        return {
            loading: false,
            permission: {
                group: this.pPermission.permission_group,
                code: '',
                description: this.pPermission.description,
                status: this.pPermission.is_active == 1? true: false,
            },
            permissionGroups: [{
                value: 'requisition',
                label: 'เอกสารส่งเบิก'
            }, {
                value: 'invoice',
                label: 'เอกสารขอเบิก'
            }, {
                value: 'history',
                label: 'ประวัติการอินเตอร์เฟซ'
            }, {
                value: 'report',
                label: 'รายงาน'
            }, {
                value: 'setting',
                label: 'การตั้งค่า'
            }],
            permissionCodes: [{
                value: 'view',
                label: 'ตรวจสอบอย่างเดียว'
            }, {
                value: 'enter',
                label: 'สร้างรายการและตรวจสอบ'
            }, {
                value: 'resubmit',
                label: 'ส่งข้อมูลเข้าระบบ'
            }],
            errors: {
                perm_group: false,
                perm_code: false,
                perm_description: false
            },
        };
    },
    mounted() {
        var code = this.pPermission.permission_code.split('_');
        this.permission.code = code[1].toString();
    },
    watch:{
        errors: {
            handler(val){
                val.perm_group? this.setError('perm_group') : this.resetError('perm_group');
                val.perm_code? this.setError('perm_code') : this.resetError('perm_code');
                val.perm_description? this.setError('perm_description') : this.resetError('perm_description');
            },
            deep: true,
        },
    },
    methods: {
        setError(ref_name){
            let ref =  this.$refs[ref_name].$refs.referenceRef
                    ? this.$refs[ref_name].$refs.referenceRef.$refs.wrapperRef
                    : (this.$refs[ref_name].$refs.textareaRef
                        ? this.$refs[ref_name].$refs.textareaRef
                        : (this.$refs[ref_name].$refs.numeric
                            ? this.$refs[ref_name].$refs.numeric
                            : (this.$refs[ref_name].$refs.wrapperRef.$refs
                                ? this.$refs[ref_name].$refs.wrapperRef.$refs.wrapperRef
                                : this.$refs[ref_name].$refs.wrapperRef )));
            ref.style = "border: 1px solid red;";
        },
        resetError(ref_name){
            let ref = this.$refs[ref_name].$refs.referenceRef
                    ? this.$refs[ref_name].$refs.referenceRef.$refs.wrapperRef
                    : (this.$refs[ref_name].$refs.textareaRef
                        ? this.$refs[ref_name].$refs.textareaRef
                        : (this.$refs[ref_name].$refs.numeric
                            ? this.$refs[ref_name].$refs.numeric
                            : (this.$refs[ref_name].$refs.wrapperRef.$refs
                                ? this.$refs[ref_name].$refs.wrapperRef.$refs.wrapperRef
                                : this.$refs[ref_name].$refs.wrapperRef )));
            ref.style = "";
        },
        async update(){
            let vm = this;
            var form = $('#update-form');
            let errorMsg = '';
            let valid = true;

            vm.errors.perm_group = false;
            vm.errors.perm_code = false;
            vm.errors.perm_description = false;
            $(form).find("div[id='el_explode_perm_group']").html("");
            $(form).find("div[id='el_explode_perm_code']").html("");
            $(form).find("div[id='el_explode_perm_description']").html("");

            if (vm.permission.group == '') {
                vm.errors.perm_group = true;
                valid = false;
                errorMsg = "กรุณาเลือกกลุ่มสิทธิ์เข้าถึงการใช้งาน";
                $(form).find("div[id='el_explode_perm_group']").html(errorMsg);
            }
            if (vm.permission.code == '') {
                vm.errors.perm_code = true;
                valid = false;
                errorMsg = "กรุณาเลือกสิทธิ์เข้าถึงการใช้งาน";
                $(form).find("div[id='el_explode_perm_code']").html(errorMsg);
            }
            if (vm.permission.description == '') {
                vm.errors.perm_description = true;
                valid = false;
                errorMsg = "กรุณากรอกคำอธิบายสิทธิ์เข้าถึงการใช้งาน";
                $(form).find("div[id='el_explode_perm_description']").html(errorMsg);
            }
            if (!valid) {
                return;
            }

            Swal.fire({
                title: 'ระบบกำลังบันทึกข้อมูล',
                type: "success",
                showConfirmButton: false,
                allowOutsideClick: false
            });
            axios.post('/OAGWXP/settings/permission/'+this.pPermission.id+'/update', {
                permission: vm.permission,
            })
            .then(function (res) {
                vm.loading = false;
                if (res.data.message) {
                    Swal.fire({
                        title: "มีข้อผิดพลาด",
                        text: res.data.message,
                        icon: "error",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "ตกลง",
                        allowOutsideClick: false
                    });
                } else {
                    Swal.fire({
                        title: "บันทึกข้อมูลสิทธิ์เข้าถึงการใช้งาน",
                        html: "บันทึกข้อมูลสิทธิ์เข้าถึงการใช้งานเรียบร้อยแล้ว",
                        icon: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "ตกลง",
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            setTimeout(function() {
                                location.href = vm.pFormUrl;
                            }, 500);
                        }
                    });
                }
            }.bind(vm))
            .catch(err => {
                let msg = err.response;
                Swal.fire({
                    title: "มีข้อผิดพลาด",
                    text: msg.message,
                    icon: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "ตกลง",
                    allowOutsideClick: false
                });
            })
            .then(() => {
                vm.loading = false;
            });
        }
    }
};
</script>

<style type="text/css" scope>
    .el-select-dropdown{
        z-index: 9999 !important;
    }
    .el-notification {
        z-index: 9999 !important;
    }
    .card-body {
        -webkit-box-flex: 1;
        flex: 1 1 auto;
        padding: 1.25rem;
    }
</style>
