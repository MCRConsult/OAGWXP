<template>
    <span>
        <button type="button" class="btn btn-block btn-sm btn-warning m-1" data-toggle="collapse" @click.prevent="openModal()">
            รอตรวจสอบ
        </button>
        <div :class="'modal fade modal_hold'+header.id" aria-labelledby="myModalLabel" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-md">
                <div class="modal-content" v-loading="loading">
                    <div class="modal-header">
                        <h4 style="font-size:22px; font-weight:400;" class="modal-title text-left">
                            เหตุผลในการรอตรวจสอบ
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true"> &times; </span> <span class="sr-only"> Close </span>
                        </button>
                    </div>
                    <div class="modal-body text-left" style="padding: 15px;">
                        <form :class="'hold-form'+header.id" onkeydown="return event.key != 'Enter';">
                            <div class="row col-12 m-0">
                                <div class="form-group pl-0 pr-2 mb-0 col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-2" >
                                    <div class="control-label mb-2" style="padding-right: 25px;">
                                        <strong> เหตุผลในการรอตรวจสอบ <span class="text-danger"> * </span> </strong>
                                    </div>
                                    <el-input type="textarea" :rows="4"
                                        :style="errors.reason? 'border: 1px solid red; border-radius: 5px;': ''"
                                        name="reason"
                                        ref="reason"
                                        placeholder=""
                                        v-model="reason"
                                        size="default"
                                        maxlength="250"
                                        show-word-limit>
                                    </el-input>
                                    <div id="el_explode_reason" class="error_msg text-left text-danger"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click.prevate="setStatus()" class="btn btn-primary btn-sm">
                            ตกลง
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" @click="closeModal()"> ยกเลิก </button>
                    </div>
                </div>
            </div>
        </div>
    </span>
</template>
<script>
    import Swal from 'sweetalert2';
    export default {
        props:['header', 'index'],
        emits: ['updateActionReq'],
        data() {
            return {
                loading: false,
                reason: '',
                errors: {
                    reason: false
                },
            }
        },
        mounted() {
            //
        },
        watch:{
            //
        },
        methods: {
            openModal() {
                $('.modal_hold'+this.header.id).modal('show');
            },
            closeModal() {
                this.reason = '',
                $('.modal_hold'+this.header.id).modal('hide');
            },
            async setStatus(){
                var vm = this;
                let valid = true;
                let errorMsg = '';
                var form = $('.hold-form'+this.header.id);
                if (vm.reason == '' || vm.reason == null){
                    vm.errors.reason = true;
                    valid = false;
                    errorMsg = "กรุณาระบุเหตุผลในการรอตรวจสอบ";
                    $(form).find("div[id='el_explode_reason']").html(errorMsg);
                }
                if (!valid) {
                    return;
                }
                Swal.fire({
                    title: 'ยืนยันรอตรวจสอบรายการ',
                    html: 'ต้องการ <b>ยืนยัน</b> รอตรวจสอบรายการใช่หรือไม่?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ยืนยัน",
                    cancelButtonText: "ยกเลิก",
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        vm.loading = true;
                        axios.post('/expense/invoice/'+this.header.id+'/set-status', {
                            activity: 'HOLD_REQUISITION',
                            reason: vm.reason,
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
                                vm.reason = '',
                                $('.modal_hold'+vm.header.id).modal('hide');
                                Swal.fire({
                                    title: 'ยืนยันรอตรวจสอบรายการ',
                                    html: 'ส่งรอตรวจสอบรายการเรียบร้อยแล้ว',
                                    icon: "success",
                                    showCancelButton: false,
                                    confirmButtonColor: "#3085d6",
                                    confirmButtonText: "ตกลง",
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        this.$emit("updateActionReq");
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
                });
            },
        }
    }
</script>