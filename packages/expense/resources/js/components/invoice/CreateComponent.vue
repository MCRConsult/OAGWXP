<template>
    <div>
        <form id="create-form">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เลขที่เอกสารส่งเบิก </strong>
                            </label><br>
                            <lovRequisition
                                :setData="search.req_number"
                                :error="false"
                                :editFlag="true"
                                @setRequisition="setRequisition"
                            />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ประเภท </strong>
                            </label><br>
                            <el-select v-model="search.invoice_type" placeholder="" style="width: 100%;" ref="invoice_type">
                                <el-option
                                    v-for="type in invoiceTypes"
                                    :key="type.lookup_code"
                                    :label="type.description"
                                    :value="type.lookup_code"
                                />
                            </el-select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> วันที่เอกสาร </strong>
                            </label><br>
                            <el-date-picker
                                v-model="req_date_input"
                                ref="req_date"
                                placeholder=""
                                clearable
                                format="DD-MM-YYYY"
                                style="width: 100%;"
                                @change="changeDateFormat"
                            />
                            <div id="el_explode_req_date" class="text-danger text-left"></div>
                        </div>
                    </div>
                    <div class="col-md-3" align="right">
                        <p><br></p>
                        <button type="button" class="btn btn-primary btn-sm m-1" @click.prevent="getRequisition()">
                            ค้นหา
                        </button>
                        <button type="button" class="btn btn-warning btn-sm m-1" @click.prevent="clear()">
                            ล้างค่า
                        </button>
                    </div>
                </div>
                <hr>
                <template v-if="loading">
                    <div class="mt-4" v-loading="loading"></div>
                </template>
                <template v-else>
                    <div class="row">
                        <template :key="header.id" v-for="(header, index) in headers">
                            <div class="col-12">
                                <div class="card" style="padding: 0px;">  
                                    <div class="card-header" style="background-color: #FCDC94;">
                                        <div class="row col-12" style="padding: 0px;">
                                            <div class="col-md-8">
                                                <el-checkbox
                                                    class="small mb-0 m-0 mr-2"
                                                    :key="header.id"
                                                    v-model="selectedReq[header.source_type == 'REQUISITION'? header.req_number: header.invoice_num]" 
                                                    :name="'check-req_'+ (header.source_type == 'REQUISITION'? header.req_number: header.invoice_num)"
                                                    size="default"
                                                    @change="chooseReq(header)"
                                                    :disabled="header.status == 'ALLOCATE'"
                                                />
                                                <a :href="'/expense/requisition/'+header.id" target="_blank" style="color: black;">
                                                    <h5 class="mb-1 d-inline">
                                                        <template v-if="header.source_type == 'REQUISITION'">
                                                            {{ header.req_number }} {{ header.description? ' : '+header.description: '' }}
                                                        </template>
                                                        <template v-else>
                                                            {{ header.invoice_num }} {{ header.description? ' : '+header.description: '' }}
                                                        </template>
                                                    </h5>
                                                </a>
                                                <hr style="margin: 10px;">
                                                <div class="row" v-if="header.source_type == 'REQUISITION'">
                                                    <span class="col-md-3 text-right text-sm text-grey-dark"> ผู้รับผิดชอบ :</span>
                                                    {{ header.user.name }}
                                                </div>
                                                <div class="row">
                                                    <span class="col-md-3 text-right text-sm text-grey-dark"> ประเภท :</span>
                                                    {{ header.invoice_type.description }}
                                                </div>
                                                <div class="row" v-if="header.source_type == 'REQUISITION'">
                                                    <span class="col-md-3 text-right text-sm text-grey-dark"> สำนักงานผู้เบิกจ่าย :</span>
                                                    {{ header.document_category }}
                                                </div>
                                                <div class="row">
                                                    <span class="col-md-3 text-right text-sm text-grey-dark"> วันที่เอกสาร :</span>
                                                    <template v-if="header.source_type == 'REQUISITION'">
                                                       {{ header.req_date_format }}
                                                    </template>
                                                    <template v-else>
                                                        {{ header.inv_date_format }}
                                                    </template>
                                                </div>
                                                <div class="row">
                                                    <span class="col-md-3 text-right text-sm text-grey-dark"> สถานะรอเบิกจ่าย :</span>
                                                    <strong v-html="header? header.status_icon : ''"></strong>
                                                </div>
                                                <div class="row">
                                                    <span class="col-md-3 text-right text-sm text-grey-dark"> สั่งจ่าย :</span>
                                                    {{ header.supplier.vendor_name }} 
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mr-3 text-right">
                                                    <div class="font-bold text-grey">Total Amount</div>
                                                    <div class="text-2xl">
                                                        <template v-if="header.source_type == 'REQUISITION'">
                                                            {{ numberFormat(header.total_amount) }}
                                                        </template>
                                                        <template v-else>
                                                            {{ numberFormat(header.remaining_amount) }}
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-center mt-4">
                                                <div>
                                                    <button class="btn btn-block btn-sm btn-warning m-1"> รอตรวจสอบ </button>
                                                </div>
                                                <div>
                                                    <button class="btn btn-block btn-sm btn-danger m-1"> ยกเลิก </button>
                                                </div>
                                                <div>
                                                    <!-- RE-INTERFACE -->
                                                    <button v-if="header.status == 'ALLOCATE'"
                                                        class="btn btn-block btn-sm btn-primary m-1"
                                                    > ส่งเบิก </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div class="pull-right">
                            <el-pagination v-if="headers.length > 0"
                                background
                                :page-size="paginate.size"
                                :pager-count="15"
                                layout="prev, pager, next"
                                :total="paginate.total"
                                :current-page="currPage"
                                @current-change="handleChangePage">
                            </el-pagination>
                            <br>
                        </div>
                    </div>
                </template>
                <br>
                <div align="right">
                    <button type="button" class="btn btn-primary" @click.prevent="groupInvoice()"> ถัดไป </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import moment from "moment";
    import numeral from "numeral";
    import Swal from 'sweetalert2';
    import lovRequisition from "./lov/Requisition.vue";

    export default {
        components: {
            lovRequisition
        },
        props: ['invoiceTypes'],
        data() {
            return {
                errors: {
                    invoice_type: false,
                    document_category: false,
                    req_date: false,
                    payment_type: false,
                    supplier: false,
                    supplier_detail: false,
                    supplier_bank: false,
                    expense_type: false,
                    amount: false,
                },
                headers: [],
                req_date_input: '',
                search: {
                    req_number: '',
                    invoice_type: '',
                    req_date: '',
                },
                loading: '',
                loading: false,
                currPage: 1,
                paginate: {
                    size: 0,
                    total: 0,
                },
                selectedReq: {},
                listReq: [],
            };
        },
        mounted(){
            this.getRequisition();
        },
        watch:{
            errors: {
                handler(val){
                    val.invoice_type? this.setError('invoice_type') : this.resetError('invoice_type');
                    val.amount? this.setError('amount') : this.resetError('amount');
                },
                deep: true,
            },
        },
        methods: {
            numberFormat(value) {
                if (!value) return "0.00";
                return numeral(value).format("0,0.00");
            },
            changeDateFormat() {
                const formattedDate = moment(this.req_date_input, "DD-MM-YYYY").format("YYYY-MM-DD");
                this.search.req_date = formattedDate;
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
            setRequisition(res) {
                this.search.req_number = res.requisition;
            },
            async getRequisition(){
                let vm = this;
                vm.loading = true;
                vm.headers = [];
                const url = "/expense/api/invoice/fetch-requisition";
                await axios.get(url, {
                    params: {
                        search: this.search
                    }
                })
                .then(res => {
                    let data = res.data;
                    vm.headers = data.headers.data;
                    vm.paginate = {
                        size: data.headers.per_page,
                        total: data.headers.total,
                    };
                })
                .catch(err => {
                    let msg = err.response.data;
                    Swal.fire({
                        title: 'มีข้อผิดพลาด',
                        text: msg.message,
                        type: "error",
                    });
                })
                .then(() => {
                    vm.loading = false;
                });
            },
            async handleChangePage(page) {
              await this.fetchData(page);
            },
            async fetchData(page = 1) {
                const url = "/expense/api/invoice/index-render-page?page="+page;
                this.loading = true;
                await axios
                    .post(url, {
                        search: this.search
                    })
                    .then((res) => res.data)
                    .then((res) => {
                        this.paginate = {
                            size: res.headers.per_page,
                            total: res.headers.total,
                        };
                    this.headers = res.headers.data;
                    this.currPage = page;
                })
                .catch((error) => {
                    console.error(error);
                });
                this.loading = false;
            },
            clear(){
                this.search = {
                    req_number: '',
                    invoice_type: '',
                    req_date: ''
                }
                this.getRequisition();
            },
            chooseReq(header){
                let vm = this;
                let reqNumber = header.source_type == 'REQUISITION'? header.req_number: header.invoice_num;
                let checked = $('input[name="check-req_'+reqNumber+'"]').prop('checked');
                if(checked){
                    vm.selectedReq[reqNumber] = true;
                    vm.listReq.push(reqNumber);
                }else{
                    vm.selectedReq[reqNumber] = false;
                    vm.listReq = vm.listReq.filter(function(value) {
                        return value != reqNumber
                    });
                }
            },
            async groupInvoice(){
                var vm = this;
                Swal.fire({
                    title: "ยืนยันสร้างเอกสารขอเบิก",
                    html: "ต้องการ <b>ยืนยัน</b> สร้างเอกสารขอเบิกใช่หรือไม่?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ใช่",
                    cancelButtonText: "ไม่",
                    allowOutsideClick: false // ป้องกันการปิด alert เมื่อคลิกนอกกรอบ
                }).then((result) => {
                    if (result.isConfirmed) {
                        vm.loading = true;
                        axios.post('/expense/invoice/group-invoice', {
                            requisitions: this.listReq,
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
                                    title: "ยืนยันสร้างเอกสารขอเบิก",
                                    html: "สร้างเอกสารขอเบิกเรียบร้อยแล้ว",
                                    icon: "success",
                                    showCancelButton: false,
                                    confirmButtonColor: "#3085d6",
                                    confirmButtonText: "ตกลง",
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        setTimeout(function() {
                                            location.href = res.data.redirect_page;
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
    }
</style>