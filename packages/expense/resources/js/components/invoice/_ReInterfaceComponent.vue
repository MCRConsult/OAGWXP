<template>
    <div>
        <div align="center">
            <button type="button" class="btn btn-danger" @click.prevent="reInterface()"> ส่งเข้าระบบใหม่ </button>
        </div>
    </div>
</template>

<script>
    import moment   from "moment";
    import numeral  from "numeral";
    import Swal     from 'sweetalert2';

    export default {
        props: ['pFormUrl'],
        data() {
            return {
                //
            };
        },
        mounted(){
        },
        methods: {
            async reInterface(){
                var vm = this;
                Swal.fire({
                    title: "ขอเบิกเอกสาร",
                    html: "ต้องการ <b>ยืนยัน</b> ขอเบิกเอกสารใหม่อีกครั้งใช่หรือไม่?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ใช่",
                    cancelButtonText: "ไม่",
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'ระบบกำลังขอเบิกเอกสารใหม่อีกครั้ง',
                            type: "success",
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                        // POST METHOD
                        axios.get(vm.pFormUrl)
                        .then(function (res) {
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
                                    title: "ขอเบิกเอกสาร",
                                    html: "ส่งเบิกเอกสารเรียบร้อยแล้ว",
                                    icon: "success",
                                    showCancelButton: false,
                                    confirmButtonColor: "#3085d6",
                                    confirmButtonText: "ตกลง",
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        setTimeout(function() {
                                            location.href = res.data.redirect_show_page;
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
                        });
                    }
                });
            },
        }
    }
</script>

<style type="text/css" scope>
    .el-select__wrapper {
        font-size: 12px;
    }
    .el-input__wrapper {
        font-size: 12px;
        /*padding: 0px;*/
    }
    .sticky-col {
        position: sticky !important;
        background-color: #FFF;
        z-index: 9999;
        top:0px;
    }
</style>