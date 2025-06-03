<template>
    <div v-loading="loading">
        <div id="user_form">
            <div class="col-12 row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> ชื่อผู้ใช้งาน </strong>
                                </label><br>
                                <el-input v-model="user.hr_employee.full_name" style="width: 100%;" readonly/>
                            </div>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> ชื่อผู้ใช้งาน (ORACLE) </strong>
                                </label><br>
                                <el-input v-model="user.name" style="width: 100%;" readonly/>
                            </div>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> องค์กร </strong>
                                </label><br>
                                <el-input v-model="user.organization_v.name" style="width: 100%;" readonly/>
                            </div>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> สถานที่ </strong>
                                </label><br>
                                <el-input v-model="user.location.location_code" style="width: 100%;" readonly/>
                            </div>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> สถานะ </strong>
                                </label><br>
                                <el-checkbox v-model="status" name="status" :value="status"/>  ใช้งานอยู่ ?
                            </div>
                        </div>  
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" >
                                <label class="control-label">
                                    <strong> <i class="fa fa-user-secret"></i> &nbsp; สิทธิ์การเข้าถึงการใช้งาน </strong>
                                </label>
                            </div>
                        </div>   
                    </div>
                </div>
            </div>
            <div align="center">
                <button type="button" class="btn btn-primary btn-sm mr-2" @click.prevent="update()"> บันทีก </button>
                <a :href="pFormUrl" type="button" class="btn btn-danger btn-sm"> ยกเลิก </a>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2';

export default {
    props: ['pFormUrl', 'user'],
    components: {
        //
    },
    data() {
        return {
            loading: false,
            status: this.user.is_active == 1? true: false,
            permissions: {},
        };
    },
    mounted() {
        
    },
    methods: {
        async update(){
            let vm = this;
            vm.loading = true;
            axios.post('/expense/settings/user/'+vm.user.id+'/update', {
                status: vm.status,
                permissions: vm.permissions,
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
                        title: "บันทึกข้อมูลผู้ใช้งาน",
                        html: "บันทึกข้อมูลผู้ใช้งานเรียบร้อยแล้ว",
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
