<template>
    <div v-loading="loading">
        <form id="edit-form">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> แหล่งเงิน <span class="text-danger"> *</span></strong>
                            </label><br>
                            <budgetSource 
                                :setData="header.budget_source"
                                :error="errors.budget_source"
                                :editFlag="false"
                                @setBudgetSource="setBudgetSource"
                            ></budgetSource>
                            <div id="el_explode_budget_source" class="text-danger text-left"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ประเภทการขอเบิก <span class="text-danger"> *</span></strong>
                            </label><br>
                            <paymentType
                                :setData="header.payment_type"
                                :error="errors.payment_type"
                                :editFlag="false"
                                @setPaymentType="setPaymentType"
                            ></paymentType>
                            <div id="el_explode_payment_type" class="text-danger text-left"></div>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="header.payment_type == 'NON-PAYMENT'">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ธนาคาร <span class="text-danger"> * </span></strong>
                            </label><br>
                            <bankAccount
                                :setData="header.cash_bank_account_id"
                                :error="errors.cash_bank_account"
                                :editFlag="true"
                                @setBankAccount="setBankAccount"
                            ></bankAccount>
                            <div id="el_explode_cash_bank_account" class="text-danger text-left"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ประเภท <span class="text-danger"> *</span></strong>
                            </label><br>
                            <el-select v-model="header.invoice_type" placeholder="" style="width: 100%;" ref="invoice_type" disabled>
                                <el-option
                                    v-for="type in invoiceTypes"
                                    :key="type.lookup_code"
                                    :label="type.description"
                                    :value="type.lookup_code"
                                />
                            </el-select>
                            <div id="el_explode_invoice_type" class="text-danger text-left"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> สำนักงานผู้เบิกจ่าย <span class="text-danger"> *</span></strong>
                            </label><br>
                            <documentCategory
                                :setData="header.document_category"
                                :error="errors.document_category"
                                :editFlag="false"
                                @setDocumentCate="setDocumentCate"
                            ></documentCategory>
                            <div id="el_explode_document_category" class="text-danger text-left"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> วันที่เอกสารส่งเบิก <span class="text-danger"> *</span></strong>
                            </label><br>
                            <el-date-picker
                                v-model="req_date"
                                ref="req_date"
                                placeholder=""
                                clearable
                                format="DD-MM-YYYY"
                                style="width: 100%;"
                                disabled
                            />
                            <!-- v-model="header.req_date"
                            @change="changeReqDateFormat" -->
                            <div id="el_explode_req_date" class="text-danger text-left"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ชื่อสั่งจ่าย <span class="text-danger"> *</span></strong> &nbsp;
                                    <el-radio v-model="header.multiple_supplier" label="ONE" @change="changeSupplierType" disabled>
                                        รายเดียว
                                    </el-radio>
                                    <el-radio v-model="header.multiple_supplier" label="MORE" @change="changeSupplierType" disabled>
                                        หลายราย (กรอกข้อมูลระดับรายการ)
                                    </el-radio>
                            </label><br>
                            <supplier
                                :setData="header.supplier_id"
                                :error="errors.supplier"
                                :editFlag="false"
                                @setSupplier="setSupplierHeader"
                            ></supplier>
                            <div id="el_explode_supplier" class="text-danger text-left"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> คำอธิบาย <span class="text-danger"> *</span></strong>
                            </label><br>
                            <el-input :style="errors.header_desc? 'border: 1px solid red; border-radius: 5px;': ''"
                                v-model="header.description"
                                type="textarea"
                                :rows="2"
                                style="width: 100%;"
                                placeholder=""
                                maxlength="240"
                                show-word-limit
                            />
                            <div id="el_explode_header_desc" class="text-danger text-left"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> 
                                    <template v-if="header.payment_type == 'PAYMENT'"> เลขที่เอกสารส่งเบิก </template>
                                    <template v-else> เลขที่ใบสำคัญ </template>
                                </strong>
                            </label><br>
                            <el-input v-model="header.req_number" style="width: 100%;" disabled/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ผู้รับผิดชอบ </strong>
                            </label><br>
                            <el-input v-model="header.user.hr_employee.full_name" style="width: 100%;" disabled/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> สถานะ </strong>
                            </label><br>
                            <el-input v-model="header.status_text" style="width: 100%;" disabled/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เลขที่ใบกำกับ </strong>
                            </label><br>
                            <el-input v-model="header.invioce_number_ref" style="width: 100%;" disabled/>
                        </div>
                    </div>
                </div>
                <!-- TABLE LINE LISTS-->
                <div class="table-responsive mt-3" style="max-height: 600px;">
                    <table class="table text-nowrap table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    รายการที่ 
                                </th>
                                <th class="text-left">
                                    ประเภทค่าใช้จ่าย 
                                </th>
                                <th class="text-center">
                                    จำนวนเงิน 
                                </th>
                                <th class="text-center">
                                    ชื่อสั่งจ่าย 
                                </th>
                                <th class="text-center">
                                    เลขที่บัญชีธนาคาร 
                                </th>
                                <th class="text-center">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <listComp
                                v-for="(row, index) in linelists"
                                :key="index"
                                :index="index"
                                :requisition="header"
                                :attribute="row"
                                :defaultSetName="defaultSetName"
                                @updateRow="updateRow"
                                @removeRow="removeRow"
                            />
                        </tbody>
                    </table>
                </div>
                <div class="row m-t-sm">
                    <div class="col-sm-9"> </div>
                    <div class="col-sm-3 text-right">
                        <div class="card">
                            <table class="table" style="margin: 0px;">
                                <tbody>
                                    <tr>
                                        <td class="mb-0 tw-text-grey-darker tw-text-bold" style="width:40%; font-size:15px !important;">
                                            <strong> รวมทั้งสิ้น : </strong>
                                        </td>
                                        <td class="mb-0 tw-text-grey-darker tw-text-bold" style="width:60%; font-size:15px !important;" >
                                            {{ numberFormat(totalApply) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div align="center">
                    <button type="button" class="btn btn-save mr-2" @click.prevent="update()" style="color: #FFF;"> บันทึกรายการ </button>
                    <button type="button" class="btn btn-primary" @click.prevent="store()"> ส่งเบิก </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import moment   from "moment";
    import numeral  from "numeral";
    import Swal     from 'sweetalert2';
    import {ElNotification} from 'element-plus';
    //========================================================
    import budgetSource     from "../../../lov/BudgetSource.vue";
    import documentCategory from "../../../lov/DocumentCategory.vue";
    import paymentType      from "../../../lov/PaymentType.vue";
    import supplier         from "../../../lov/Supplier.vue";
    import supplierBank     from "../../../lov/SupplierBank.vue";
    import budgetPlan       from "../../../lov/BudgetPlan.vue";
    import budgetType       from "../../../lov/BudgetType.vue";
    import expenseType      from "../../../lov/ExpenseType.vue";
    import bankAccount      from "../../../lov/BankAccount.vue";

    import listComp         from "./ListComponent.vue";

    export default {
        components: {
            budgetSource, documentCategory, paymentType, supplier, supplierBank, budgetPlan, budgetType, expenseType, bankAccount, listComp
        },
        props: ['requisition', 'invoiceTypes', 'defaultSetName'],
        data() {
            return {
                budgetSource: ['510'],
                errors: {
                    budget_source: false,
                    invoice_type: false,
                    document_category: false,
                    req_date: false,
                    payment_type: false,
                    supplier: false,
                    header_desc: false,
                    supplier_detail: false,
                    supplier_bank: false,
                    budget_plan: false,
                    budget_type: false,
                    expense_type: false,
                    expense_account: false,
                    amount: false,
                    remaining_receipt: false,
                },
                req_date: new Date(),
                header: this.requisition,
                linelists: this.requisition.lines,
                reqLine: {
                    supplier_id: '',
                    supplier_name: '',
                    bank_account_number: '',
                    supplier_site: '',
                    budget_plan: '',
                    budget_type: '',
                    expense_type: '',
                    expense_description: '',
                    expense_account: '',
                    amount: '',
                    description: '',
                    vehicle_number: '',
                    policy_number: '',
                    vehicle_oil_type: '',
                    utility_type: '',
                    utility_detail: '',
                    unit_quantity: '',
                    invoice_number: '',
                    invoice_date: '',
                    receipt_number: '',
                    receipt_date: '',
                    remaining_receipt_flag: 'N',
                    remaining_receipt_id: '',
                },
                loading: false,
                // SEGMENT
                segment1: '', segment2: '', segment3: '', segment4: '', segment5: '', segment6: '',
                segment7: '', segment8: '', segment9: '', segment10: '', segment11: '', segment12: '', segment13: '',
            };
        },
        mounted(){
            this.changeReqDateFormat();
        },
        computed: {
            totalApply() {
                return this.linelists.reduce((accumulator, line) => {
                    this.totalApplyAmount = accumulator + parseFloat(line.actual_amount);
                    return accumulator + parseFloat(line.actual_amount);
                }, 0);
            },
        },
        watch:{
            errors: {
                handler(val){
                    val.invoice_type? this.setError('invoice_type') : this.resetError('invoice_type');
                },
                deep: true,
            },
        },
        methods: {
            numberFormat(value) {
                if (!value) return "0.00";
                return numeral(value).format("0,0.00");
            },
            changeReqDateFormat() {
                const formattedDate = moment(this.req_date, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.header.req_date = formattedDate;
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
            setBudgetSource(res){
                this.header.budget_source = res.budget_source;
                this.isARReceipt(res.budget_source);
                this.getDocumentCate(res.budget_source);
            },
            setDocumentCate(res){
                this.header.document_category = res.document_category;
            },
            setPaymentType(res){
                this.header.payment_type = res.payment_type;
            },
            setSupplierHeader(res){
                this.header.supplier_id = res.supplier;
                this.header.supplier_name = res.vendor_name;
                if(this.header.multiple_supplier == 'ONE'){
                    this.reqLine.supplier_id = res.supplier;
                    this.reqLine.supplier_name = res.vendor_name;
                }
            },
            setBankAccount(res){
                this.header.cash_bank_account_id = res.cash_bank_account_id;
            },
            changeSupplierType(){
                this.resetValues();
                if(this.requisition.multiple_supplier == 'ONE'){
                    this.reqLine.supplier_id = this.requisition.supplier;
                    this.reqLine.supplier_name = this.requisition.supplier_name;
                }else{
                    this.reqLine.supplier_id = '';
                    this.reqLine.supplier_name = '';
                    this.reqLine.bank_account_number = '';
                    this.reqLine.supplier_site = '';
                }
            },
            isARReceipt(budgetSource) {
                this.reqLine.remaining_receipt_flag = this.budgetSource.indexOf(budgetSource) !== -1? 'Y': 'N';
            },
            getDocumentCate(budgetSource){
                axios.get(`/OAGWXP/api/requisition/get-document-category`, {
                    params: {
                        budget_source: budgetSource
                    }
                })
                .then(res => {
                    this.requisition.document_category = res.data.data? res.data.data.tag: '';
                })
                .catch((error) => {
                    Swal.fire({
                        title: "มีข้อผิดพลาด",
                        text: error,
                        icon: "warning",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "ตกลง",
                        allowOutsideClick: false
                    });
                })
            },
            updateRow(response){
                console.log(response.line);
                var vm = this;
                let index = response.index;
                if (vm.linelists[index].remaining_receipt_flag == 'Y') {
                    axios.post('/OAGWXP/requisition/update-ar-receipt', {
                        header: vm.requisition,
                        line: response.line,
                        seq: index,
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
                            let valUpdate = response.line;
                            if (typeof this.linelists === 'object' && this.linelists !== null && response.index in this.linelists) {
                                const currentItem = this.linelists[response.index];
                                if (currentItem) {
                                    if (valUpdate && typeof valUpdate === 'object') {
                                        // Object.assign(currentItem, valUpdate);
                                        Object.assign(currentItem, {
                                            supplier_id: valUpdate.supplier_id,
                                            supplier_name: valUpdate.supplier_name,
                                            bank_account_number: valUpdate.bank_account_number,
                                            budget_plan: valUpdate.budget_plan,
                                            budget_type: valUpdate.budget_type,
                                            expense_type: valUpdate.expense_type,
                                            expense_description: valUpdate.expense_description,
                                            expense_account: valUpdate.expense_account,
                                            amount: valUpdate.amount,
                                            actual_amount: valUpdate.actual_amount,
                                            description: valUpdate.description,
                                            vehicle_number: valUpdate.vehicle_number,
                                            policy_number: valUpdate.policy_number,
                                            vehicle_oil_type: valUpdate.vehicle_oil_type,
                                            utility_type: valUpdate.utility_type,
                                            utility_detail: valUpdate.utility_detail,
                                            unit_quantity: valUpdate.unit_quantity,
                                            invoice_number: valUpdate.invoice_number,
                                            invoice_date: valUpdate.invoice_date,
                                            receipt_number: valUpdate.receipt_number,
                                            receipt_date: valUpdate.receipt_date,
                                            remaining_receipt_flag: valUpdate.remaining_receipt_flag,
                                            remaining_receipt_id: valUpdate.remaining_receipt_id,
                                            contract_number: valUpdate.contract_number
                                        });
                                    } else {
                                        console.error('valUpdate is invalid:', valUpdate);
                                    }
                                } else {
                                    console.error(`No item found at index ${response.index}`);
                                }
                            } else {
                                console.error('Invalid response or linelists object.');
                            }
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
                }else{
                    let valUpdate = response.line;
                    if (typeof this.linelists === 'object' && this.linelists !== null && response.index in this.linelists) {
                        const currentItem = this.linelists[response.index];
                        if (currentItem) {
                            if (valUpdate && typeof valUpdate === 'object') {
                                // Object.assign(currentItem, valUpdate);
                                Object.assign(currentItem, {
                                    supplier_id: valUpdate.supplier_id,
                                    supplier_name: valUpdate.supplier_name,
                                    bank_account_number: valUpdate.bank_account_number,
                                    budget_plan: valUpdate.budget_plan,
                                    budget_type: valUpdate.budget_type,
                                    expense_type: valUpdate.expense_type,
                                    expense_description: valUpdate.expense_description,
                                    expense_account: valUpdate.expense_account,
                                    amount: valUpdate.amount,
                                    actual_amount: valUpdate.actual_amount,
                                    description: valUpdate.description,
                                    vehicle_number: valUpdate.vehicle_number,
                                    policy_number: valUpdate.policy_number,
                                    vehicle_oil_type: valUpdate.vehicle_oil_type,
                                    utility_type: valUpdate.utility_type,
                                    utility_detail: valUpdate.utility_detail,
                                    unit_quantity: valUpdate.unit_quantity,
                                    invoice_number: valUpdate.invoice_number,
                                    invoice_date: valUpdate.invoice_date,
                                    receipt_number: valUpdate.receipt_number,
                                    receipt_date: valUpdate.receipt_date,
                                    remaining_receipt_flag: valUpdate.remaining_receipt_flag,
                                    remaining_receipt_id: valUpdate.remaining_receipt_id,
                                    contract_number: valUpdate.contract_number
                                });
                            } else {
                                console.error('valUpdate is invalid:', valUpdate);
                            }
                        } else {
                            console.error(`No item found at index ${response.index}`);
                        }
                    } else {
                        console.error('Invalid response or linelists object.');
                    }
                }
            },
            resetValues(){
                var vm = this;
                var form = $('#edit-form');
                vm.errors.invoice_type = false;
                vm.errors.document_category = false;
                vm.errors.req_date = false;
                vm.errors.payment_type = false;
                vm.errors.supplier = false;
                vm.errors.header_desc = false;
                vm.errors.supplier_detail = false;
                vm.errors.supplier_bank = false;
                vm.errors.budget_plan = false;
                vm.errors.budget_type = false;
                vm.errors.expense_type = false;
                vm.errors.amount = false;
                vm.errors.expense_account = false;
                $(form).find("div[id='el_explode_invoice_type']").html("");
                $(form).find("div[id='el_explode_document_category']").html("");
                $(form).find("div[id='el_explode_req_date']").html("");
                $(form).find("div[id='el_explode_payment_type']").html("");
                $(form).find("div[id='el_explode_supplier']").html("");
                $(form).find("div[id='el_explode_header_desc']").html("");
                $(form).find("div[id='el_explode_supplier_detail']").html("");
                $(form).find("div[id='el_explode_supplier_bank']").html("");
                $(form).find("div[id='el_explode_budget_plan']").html("");
                $(form).find("div[id='el_explode_budget_type']").html("");
                $(form).find("div[id='el_explode_expense_type']").html("");
                $(form).find("div[id='el_explode_amount']").html("");
                $(form).find("div[id='el_explode_expense_account']").html("");
            },
            async update(){
                var vm = this;
                var form = $('#edit-form');
                let errorMsg = '';
                vm.resetValues();
                let valid = true;
                if (vm.requisition.total_amount != vm.totalApply) {
                    valid = false;
                    errorMsg = "กรุณาตรวจสอบข้อมูล และจำนวนเงินให้ถูกต้อง";
                    ElNotification({
                        title: 'ข้อผิดผลาด',
                        message: errorMsg,
                        type: 'error',
                    });
                }
                if (vm.requisition.budget_source == '') {
                    vm.errors.budget_source = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกแหล่งเงิน";
                    $(form).find("div[id='el_explode_budget_source']").html(errorMsg);
                }
                if (vm.requisition.invoice_type == '') {
                    vm.errors.invoice_type = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกประเภท";
                    $(form).find("div[id='el_explode_invoice_type']").html(errorMsg);
                }
                if (vm.requisition.document_category == '') {
                    vm.errors.document_category = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกสำนักงานผู้เบิกจ่าย";
                    $(form).find("div[id='el_explode_document_category']").html(errorMsg);
                }
                if (vm.requisition.req_date == '') {
                    vm.errors.req_date = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกวันที่เอกสาร";
                    $(form).find("div[id='el_explode_req_date']").html(errorMsg);
                }
                if (vm.requisition.payment_type == '') {
                    vm.errors.payment_type = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกประเภทการขอเบิก";
                    $(form).find("div[id='el_explode_payment_type']").html(errorMsg);
                }
                if (vm.requisition.supplier_id == '') {
                    vm.errors.supplier = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกผู้สั่งจ่าย";
                    $(form).find("div[id='el_explode_supplier']").html(errorMsg);
                }
                if (vm.requisition.description == '' || vm.requisition.description == null) {
                    vm.errors.header_desc = true;
                    valid = false;
                    errorMsg = "กรุณากรอกคำอธิบาย";
                    $(form).find("div[id='el_explode_header_desc']").html(errorMsg);
                }
                // VALIDATE ACCOUNT LINE LEVEL
                vm.linelists.forEach((item, index) => {
                    var coa = item.expense_account.split('.');
                    if ((coa[5] == '' || coa[5] == undefined) || (coa[6] == '' || coa[6] == undefined) || (coa[8] == '' || coa[8] == undefined) || (coa[9] == '' || coa[9] == undefined) || (coa[10] == '' || coa[10] == undefined)) {
                        valid = false;
                        Swal.fire({
                            title: "แจ้งเตือน",
                            text: 'กรุณาตรวจสอบรายการบัญชีแต่ละรายการอีกครั้ง',
                            icon: "warning",
                            showCancelButton: false,
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "ตกลง",
                            allowOutsideClick: false
                        });
                    }
                });
                if (vm.linelists.length == 0) {
                    valid = false;
                    Swal.fire({
                        title: "แจ้งเตือน",
                        text: 'ไม่พบข้อมูลรายการ กรุณาตรวจสอบ',
                        icon: "warning",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "ตกลง",
                        allowOutsideClick: false
                    });
                }
                if (!valid) {
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
                        Swal.fire({
                            title: '<span style="font-size: 28px;">ระบบกำลังบันทึกเอกสารเคลียร์เงินยืม</span>',
                            type: "success",
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        // POST METHOD
                        axios.post('/OAGWXP/requisition/'+vm.header.id+'/clear-update', {
                            header: this.header,
                            lines: this.linelists,
                            totalApply: this.totalApply
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
                    }
                });
            },
            async store(){
                var vm = this;
                var form = $('#edit-form');
                let errorMsg = '';
                vm.resetValues();
                let valid = true;
                if (vm.requisition.total_amount != vm.totalApply) {
                    valid = false;
                    errorMsg = "กรุณาตรวจสอบข้อมูล และจำนวนเงินให้ถูกต้อง";
                    ElNotification({
                        title: 'ข้อผิดผลาด',
                        message: errorMsg,
                        type: 'error',
                    });
                }
                if (vm.requisition.budget_source == '') {
                    vm.errors.budget_source = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกแหล่งเงิน";
                    $(form).find("div[id='el_explode_budget_source']").html(errorMsg);
                }
                if (vm.requisition.invoice_type == '') {
                    vm.errors.invoice_type = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกประเภท";
                    $(form).find("div[id='el_explode_invoice_type']").html(errorMsg);
                }
                if (vm.requisition.document_category == '') {
                    vm.errors.document_category = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกสำนักงานผู้เบิกจ่าย";
                    $(form).find("div[id='el_explode_document_category']").html(errorMsg);
                }
                if (vm.requisition.req_date == '') {
                    vm.errors.req_date = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกวันที่เอกสาร";
                    $(form).find("div[id='el_explode_req_date']").html(errorMsg);
                }
                if (vm.requisition.payment_type == '') {
                    vm.errors.payment_type = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกประเภทการขอเบิก";
                    $(form).find("div[id='el_explode_payment_type']").html(errorMsg);
                }
                if (vm.requisition.supplier_id == '') {
                    vm.errors.supplier = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกผู้สั่งจ่าย";
                    $(form).find("div[id='el_explode_supplier']").html(errorMsg);
                }
                if (vm.requisition.description == '') {
                    vm.errors.header_desc = true;
                    valid = false;
                    errorMsg = "กรุณากรอกคำอธิบาย";
                    $(form).find("div[id='el_explode_header_desc']").html(errorMsg);
                }
                // VALIDATE ACCOUNT LINE LEVEL
                vm.linelists.forEach((item, index) => {
                    var coa = item.expense_account.split('.');
                    if ((coa[5] == '' || coa[5] == undefined) || (coa[6] == '' || coa[6] == undefined) || (coa[8] == '' || coa[8] == undefined) || (coa[9] == '' || coa[9] == undefined) || (coa[10] == '' || coa[10] == undefined)) {
                        valid = false;
                        Swal.fire({
                            title: "แจ้งเตือน",
                            text: 'กรุณาตรวจสอบรายการบัญชีแต่ละรายการอีกครั้ง',
                            icon: "warning",
                            showCancelButton: false,
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "ตกลง",
                            allowOutsideClick: false
                        });
                    }
                });
                if (vm.linelists.length == 0) {
                    valid = false;
                    Swal.fire({
                        title: "แจ้งเตือน",
                        text: 'ไม่พบข้อมูลรายการ กรุณาตรวจสอบ',
                        icon: "warning",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "ตกลง",
                        allowOutsideClick: false
                    });
                }
                if (!valid) {
                    return;
                }
                Swal.fire({
                    title: "ส่งเบิกเอกสาร",
                    html: "ต้องการ <b>ยืนยัน</b> ส่งเบิกเอกสารใช่หรือไม่?",
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
                            title: 'ระบบกำลังส่งข้อมูลเอกสารส่งเบิก',
                            type: "success",
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        // POST METHOD
                        axios.get('/OAGWXP/requisition/'+vm.header.id+'/clear-submit')
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
            async removeRow(index) {
                var vm = this;
                axios.post('/OAGWXP/requisition/'+vm.header.id+'/clear-remove', {
                    line: vm.linelists[index],
                    seq: index,
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
                        vm.linelists.splice(index, 1);
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