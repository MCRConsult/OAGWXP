<template>
    <div v-loading="loading">
        <div class="card" style="border: 2px solid #ff6d6d;">
            <div class="card-body pt-2 pb-1">
               <div class="row">
                    <div class="col-md-10">
                        <h3 style="font-weight: bold;">
                            <template v-if="header.req_number">
                                เลขที่เอกสารเคลียร์เงินยืม : {{ header.req_number }}
                            </template>
                            <template v-else>
                                เลขที่เอกสารส่งเบิก : {{ requisition.req_number }}
                            </template>
                        </h3>
                    </div>
                    <div class="col-md-2">
                        <h3 style="font-weight: bold;">
                            สกุลเงิน : THB
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6 col-xl-4 col-xxl-3">
                <div class="card overflow-hidden">
                    <div class="card-body p-0 d-flex align-items-center">
                        <div class="bg-primary text-white py-4 px-5 me-3">
                            <i class="fa fa-shopping-basket fa-2x"></i>
                        </div>
                        <div>
                            <div class="fs-6 fw-semibold font-weight-bold">
                                <h4> {{ numberFormat(totalApply) }} </h4>
                            </div>
                            <div class="font-weight-bold mt-2"> จำนวนเงินที่ขอเบิก </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4 col-xxl-3">
                <div class="card overflow-hidden">
                    <div class="card-body p-0 d-flex align-items-center">
                        <div class="bg-warning text-white py-4 px-5 me-3">
                            <i class="fa fa-credit-card fa-2x"></i>
                        </div>
                        <div>
                            <div class="fs-6 fw-semibold font-weight-bold">
                                <h4> {{ numberFormat(totalActualApply) }} </h4>
                            </div>
                            <div class="font-weight-bold mt-2"> จำนวนเงินที่เบิกจริง </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4 col-xxl-3">
                <div class="card overflow-hidden">
                    <div class="card-body p-0 d-flex align-items-center">
                        <div class="bg-danger text-white py-4 px-5 me-3">
                            <i class="fa fa-balance-scale fa-2x"></i>
                        </div>
                        <div>
                            <div class="fs-6 fw-semibold font-weight-bold">
                                <h4> {{ numberFormat(totalApply-totalActualApply) }} </h4>
                            </div>
                            <div class="font-weight-bold mt-2"> จำนวนเงินผลต่าง </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- TABLE LINE LISTS-->
        <div class="table-responsive mt-3" style="max-height: 600px;">
            <table class="table text-nowrap table-hover">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">
                            รายการที่ 
                        </th>
                        <th class="text-left" width="15%">
                            ประเภทค่าใช้จ่าย 
                        </th>
                        <th class="text-center bg-primary" width="10%">
                            จำนวนเงินที่ขอเบิก
                        </th>
                        <th class="text-center bg-warning" width="10%">
                            จำนวนเงินที่เบิกจริง
                        </th>
                        <th class="text-center bg-danger" width="10%">
                            จำนวนเงินผลต่าง
                        </th>
                        <th class="text-center" width="12%">
                            ชื่อสั่งจ่าย 
                        </th>
                        <th class="text-center" width="12%">
                            เลขที่บัญชีธนาคาร 
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <listComp
                        v-for="(row, index) in linelists"
                        :key="index"
                        :index="index"
                        :attribute="row"
                        @updatevalid="updatevalid"
                    />
                    <tr>
                        <th class="text-center" colspan="2"> รวมทั้งสิ้น </th>
                        <th class="text-right"> {{ numberFormat(totalApply) }} </th>
                        <th class="text-right"> {{ numberFormat(totalActualApply) }} </th>
                        <th class="text-right"> {{ numberFormat(totalApply-totalActualApply) }} </th>
                        <th class="text-center" colspan="2"> </th>
                    </tr>
                </tbody>
            </table>
        </div>
        <div align="center">
            <button v-if="vFlag" type="button" class="btn btn-primary" @click.prevent="update()"> บันทึก </button>
        </div>
    </div>
</template>

<script>
    import moment   from "moment";
    import numeral  from "numeral";
    import Swal     from 'sweetalert2';
    //========================================================
    import listComp  from "./ListComponent.vue";

    export default {
        components: {
            listComp
        },
        props: ['requisition', 'clearReq'],
        data() {
            return {
                header: this.clearReq,
                linelists: this.clearReq.lines,
                valid: {},
                vFlag: true,
                loading: false,
            };
        },
        mounted(){
        },
        computed: {
            totalApply() {
                return this.linelists.reduce((accumulator, line) => {
                    this.totalApplyAmount = accumulator + parseFloat(line.amount);
                    return accumulator + parseFloat(line.amount);
                }, 0);
            },
            totalActualApply() {
                return this.linelists.reduce((accumulator, line) => {
                    this.totalApplyAmount = accumulator + parseFloat(line.actual_amount);
                    return accumulator + parseFloat(line.actual_amount);
                }, 0);
            },
        },
        watch:{
            //
        },
        methods: {
            numberFormat(value) {
                if (!value) return "0.00";
                return numeral(value).format("0,0.00");
            },
            updatevalid(res) {
                this.valid[res.index] = res.value;
                let result = true;
                Object.values(this.valid).forEach(function(item) {
                    if(item == false){ result = false; }
                });
                this.vFlag = result;
            },
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
                var vm = this;
                if (!this.valid) {
                    return;
                }
                Swal.fire({
                    title: "บันทึกเอกสารเคลียร์เงินยืม",
                    html: "ต้องการ <b>ยืนยัน</b> บันทึกเอกสารเคลียร์เงินยืมใช่หรือไม่?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ใช่",
                    cancelButtonText: "ไม่",
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.importData();
                    }
                });
            },
            async importData(){
                var vm = this;
                Swal.fire({
                    title: 'ระบบกำลังบันทึกเอกสารเคลียร์เงินยืม',
                    type: "success",
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                // POST METHOD
                axios.post('/expense/requisition/'+vm.header.id+'/update', {
                    header: this.header,
                    lines: this.linelists,
                    totalApply: this.totalActualApply,
                    refRequisition: this.requisition.id
                })
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
                            title: "บันทึกเอกสารเคลียร์เงินยืม",
                            html: "บันทึกเอกสารเคลียร์เงินยืมเรียบร้อยแล้ว",
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
            },
        },
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