<template>
    <div>
        <form id="create-form">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ข้อมูลต้นทาง </strong>
                            </label><br>
                            <el-select v-model="search.source_data" placeholder="" name="source_data" @change="changeSourchDT">
                                <el-option
                                    v-for="source in sourceDatas"
                                    :key="source.value"
                                    :label="source.label"
                                    :value="source.value"
                                >
                                </el-option>
                            </el-select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เลขที่เอกสารส่งเบิก </strong>
                            </label><br>
                            <lovRequisition
                                :sourceType="search.source_data"
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
                                <strong> ประเภทการขอเบิก </strong>
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
                                <strong> สำนักงานผู้เบิกจ่าย </strong>
                            </label><br>
                            <lovDocumentCategory
                                :setData="search.document_category"
                                :error="false"
                                :editFlag="true"
                                @setDocumentCate="setDocumentCate"
                            ></lovDocumentCategory>
                        </div>
                    </div>
                </div>
                <div class="row">
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
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ชื่อผู้สั่งจ่าย </strong>
                            </label><br>
                            <supplier
                                :setData="search.supplier_id"
                                :error="false"
                                :editFlag="true"
                                @setSupplier="setSupplier"
                            ></supplier>
                        </div>
                    </div>
                    <div class="col-md-6" align="right">
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
                        <div class="row col-12 pr-0">
                            <div class="col-md-9">
                                <el-checkbox v-model="isAllSelected"
                                    label="เลือกทั้งหมด"
                                    name="selectAll"
                                    @change="toggleSelectAll"
                                    border
                                    size="default"
                                    style="margin: 1rem;" 
                                > </el-checkbox>
                            </div>
                            <div class="col-md-3 mt-3 pr-0" align="right">
                                <button v-if="listReq.length" type="button" class="btn btn-primary" @click.prevent="groupInvoice()">
                                    ถัดไป 
                                </button>
                            </div>
                        </div>
                        <template :key="header.id" v-for="(header, index) in headers">
                            <div class="col-12">
                                <div class="card" style="padding: 0px;">  
                                    <div class="card-header" style="background-color: #FCDC94;">
                                        <div class="row col-12" style="padding: 0px;">
                                            <div class="col-md-8">
                                                <el-checkbox
                                                    class="small mb-0 m-0 mr-2"
                                                    :key="header.req_number"
                                                    v-model="selectedReq[header.req_number]" 
                                                    :name="'check-req_'+header.req_number"
                                                    size="default"
                                                    @change="chooseReq(header)"
                                                    :disabled="header.status == 'PENDING'"
                                                />
                                                <h5 class="mb-1 d-inline">
                                                    <template v-if="header.source_type == 'REQUISITION'">
                                                        {{ header.req_number }} {{ header.description? ' : '+header.description: '' }}
                                                        <a class="btn btn-check btn-sm" style="font-size: 12px; padding: 3px;" 
                                                            :href="'/expense/requisition/'+header.id" target="_blank">
                                                            ตรวจสอบ
                                                        </a>
                                                    </template>
                                                    <template v-else>
                                                        {{ header.req_number }}
                                                    </template>
                                                </h5>
                                                <hr style="margin: 10px;">
                                                <div class="row" v-if="header.source_type == 'REQUISITION'">
                                                    <span class="col-md-3 text-right text-sm text-grey-dark"> ผู้รับผิดชอบ :</span>
                                                    {{ header.user.hr_employee.full_name }}
                                                </div>
                                                <div class="row">
                                                    <span class="col-md-3 text-right text-sm text-grey-dark"> ประเภทการขอเบิก :</span>
                                                    {{ header.invoice_type.description }}
                                                </div>
                                                <div class="row" v-if="header.source_type == 'REQUISITION'">
                                                    <span class="col-md-3 text-right text-sm text-grey-dark"> สำนักงานผู้เบิกจ่าย :</span>
                                                    {{ header.document_category }}
                                                </div>
                                                <div class="row">
                                                    <span class="col-md-3 text-right text-sm text-grey-dark"> วันที่เอกสาร :</span>
                                                       {{ header.req_date_format }}
                                                </div>
                                                <div class="row">
                                                    <span class="col-md-3 text-right text-sm text-grey-dark"> สถานะ :</span>
                                                    <strong v-html="header? header.status_icon : ''"></strong>
                                                </div>
                                                <div class="row">
                                                    <span class="col-md-3 text-right text-sm text-grey-dark"> ชื่อสั่งจ่าย :</span>
                                                    {{ header.supplier.vendor_name }} 
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mr-3 text-right">
                                                    <div class="font-bold text-grey"> จำนวนเงิน </div>
                                                    <div class="text-2xl">
                                                        {{ numberFormat(header.total_amount) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-center mt-4">
                                                <template v-if="header.source_type == 'REQUISITION'">
                                                    <div>
                                                        <modalHoldComp
                                                            :header="header"
                                                            :index="index"
                                                            @updateActionReq="updateActionReq"
                                                        />
                                                    </div>
                                                    <div>
                                                        <modalCancelComp
                                                            :header="header"
                                                            :index="index"
                                                            @updateActionReq="updateActionReq"
                                                        />
                                                    </div>
                                                    <div>
                                                        <!-- RE-INTERFACE -->
                                                        <button v-if="header.status == 'PENDING'" class="btn btn-block btn-sm btn-primary m-1"
                                                            @click.prevent="reInterface(header.id)">
                                                            ส่งเบิก
                                                        </button>
                                                    </div>
                                                </template>
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
                    <button v-if="listReq.length" type="button" class="btn btn-primary" @click.prevent="groupInvoice()"> ถัดไป </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import moment   from "moment";
    import numeral  from "numeral";
    import Swal     from 'sweetalert2';
    import lovRequisition       from "./lov/Requisition.vue";
    import lovDocumentCategory  from "../lov/DocumentCategory.vue";
    import modalHoldComp        from "./_ModalHoldComponent.vue";
    import modalCancelComp      from "./_ModalCancelComponent.vue";
    import supplier             from "../lov/Supplier.vue";

    export default {
        components: {
            lovRequisition, lovDocumentCategory, modalHoldComp, modalCancelComp, supplier
        },
        props: ['invoiceTypes'],
        data() {
            return {
                sourceDatas: [{
                    value: 'REQUISITION',
                    label: 'เอกสารส่งเบิก'
                }, {
                    value: 'RECEIPT',
                    label: 'นำส่งรายได้แผ่นดิน'
                }],
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
                    source_data: 'REQUISITION',
                    req_number: '',
                    invoice_type: '',
                    req_date: '',
                    document_category: '',
                    supplier: '',
                },
                loading: false,
                currPage: 1,
                paginate: {
                    size: 0,
                    total: 0,
                },
                isAllSelected: false,
                selectedReq: {},
                reqAmount: {},
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
                const formattedDate = moment(this.req_date_input, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.search.req_date = formattedDate;
            },
            setSupplier(res){
                this.search.supplier = res.supplier;
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
            changeSourchDT(){
                this.search.req_number = '';
            },
            setRequisition(res) {
                this.search.req_number = res.requisition;
            },
            setDocumentCate(res){
                this.search.document_category = res.document_category;
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
                this.headers = [];
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
                    source_data: 'REQUISITION',
                    req_number: '',
                    invoice_type: '',
                    req_date: '',
                    supplier: ''
                }
                this.getRequisition();
            },
            toggleSelectAll(event) {
                let checked = $('input[name="selectAll"]').prop('checked');
                if(checked){
                    if(this.listReq.length){
                        this.headers.forEach(header => {
                            if (!this.listReq.includes(header.req_number)) {
                                this.selectedReq[header.req_number] = true;
                                this.reqAmount[header.req_number] = header.total_amount;
                                this.listReq.push(header.req_number);
                            }
                        });
                    }else{
                        this.headers.forEach(header => {
                            this.selectedReq[header.req_number] = true;
                            this.reqAmount[header.req_number] = header.total_amount;
                            this.listReq.push(header.req_number);
                        });
                    }
                }else{
                    this.headers.forEach(header => {
                        this.selectedReq[header.req_number] = false;
                        this.reqAmount[header.req_number] = 0;
                        this.listReq.push(header.req_number);
                        this.listReq = this.listReq.filter(function(value) {
                            return value != header.req_number
                        });
                    });                    
                }                
            },
            chooseReq(header){
                let vm = this;
                let reqNumber = header.req_number;
                let checked = $('input[name="check-req_'+reqNumber+'"]').prop('checked');
                if(checked){
                    vm.selectedReq[reqNumber] = true;
                    vm.reqAmount[header.req_number] = header.total_amount;
                    vm.listReq.push(reqNumber);
                }else{
                    vm.selectedReq[reqNumber] = false;
                    vm.reqAmount[header.req_number] = 0;
                    vm.listReq = vm.listReq.filter(function(value) {
                        return value != reqNumber
                    });
                }
            },
            listsAsComma() {
                return this.listReq.join(", ");
            },
            totalAmount() {
                let total = 0;
                this.listReq.filter(req => {
                    if(this.reqAmount[req]){
                        total += Number(this.reqAmount[req]);
                    }
                });
                return this.numberFormat(total);
            },
            async groupInvoice(){
                var vm = this;
                Swal.fire({
                    title: "",
                    html: '<div style="font-size: 16px; text-align: left;"> <b>เลขที่เอกสารส่งเบิก : </b>'+this.listsAsComma()
                        +'<br><br> <b>จำนวนเงิน : </b>'+this.totalAmount()+'</div>',
                    icon: "",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ยืนยัน",
                    cancelButtonText: "ยกเลิก",
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // vm.loading = true;
                        Swal.fire({
                            title: 'ระบบกำลังสร้างเอกสารขอเบิก',
                            type: "success",
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        axios.post('/expense/invoice/group-invoice', {
                            requisitions: this.listReq,
                        })
                        .then(function (res) {
                            // vm.loading = false;
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
                                    title: "สร้างเอกสารขอเบิก",
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
                        });
                    }
                });
            },
            updateActionReq(){
                this.getRequisition();
            },
            async reInterface(reqId){
                var vm = this;
                Swal.fire({
                    title: "ส่งเบิกเอกสาร",
                    html: "ต้องการ <b>ยืนยัน</b> ส่งเบิกเอกสารใหม่อีกครั้งใช่หรือไม่?",
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
                            title: 'ระบบกำลังส่งเบิกเอกสารใหม่อีกครั้ง',
                            type: "success",
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        // POST METHOD
                        axios.get('/expense/requisition/'+reqId+'/req-resubmit')
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
                                    title: "ส่งเบิกเอกสาร",
                                    html: "ส่งเบิกเอกสารเรียบร้อยแล้ว",
                                    icon: "success",
                                    showCancelButton: false,
                                    confirmButtonColor: "#3085d6",
                                    confirmButtonText: "ตกลง",
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        setTimeout(function() {
                                            location.reload();
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