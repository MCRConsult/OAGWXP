<template>
    <div v-loading="loading">
        <form id="create-form">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> แหล่งเงิน <span class="text-danger"> *</span></strong>
                            </label><br>
                            <budgetSource 
                                :setData="requisition.budget_source"
                                :error="errors.budget_source"
                                :editFlag="true"
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
                                :setData="requisition.payment_type"
                                :error="errors.payment_type"
                                :editFlag="true"
                                @setPaymentType="setPaymentType"
                            ></paymentType>
                            <div id="el_explode_payment_type" class="text-danger text-left"></div>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="requisition.payment_type == 'NON-PAYMENT'">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ธนาคาร <span class="text-danger"> * </span></strong>
                            </label><br>
                            <bankAccount
                                :setData="requisition.cash_bank_account_id"
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
                            <el-select v-model="requisition.invoice_type" placeholder="" style="width: 100%;" ref="invoice_type">
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
                                :setData="requisition.document_category"
                                :error="errors.document_category"
                                :editFlag="true"
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
                                v-model="requisition.req_date"
                                ref="req_date"
                                placeholder=""
                                clearable
                                format="DD-MM-YYYY"
                                style="width: 100%;"
                                readonly
                            />
                            <!-- @change="changeReqDateFormat" -->
                            <div id="el_explode_req_date" class="text-danger text-left"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ชื่อสั่งจ่าย <span class="text-danger"> *</span></strong> &nbsp;
                                    <el-radio v-model="requisition.multiple_supplier" label="ONE" @change="changeSupplierType">
                                        รายเดียว
                                    </el-radio>
                                    <el-radio v-model="requisition.multiple_supplier" label="MORE" @change="changeSupplierType">
                                        หลายราย (กรอกข้อมูลระดับรายการ)
                                    </el-radio>
                            </label><br>
                            <supplier
                                :setData="requisition.supplier_id"
                                :error="errors.supplier"
                                :editFlag="requisition.multiple_supplier == 'ONE'? true: false"
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
                                v-model="requisition.description"
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
                                    <template v-if="requisition.payment_type == 'PAYMENT'"> เลขที่เอกสารส่งเบิก </template>
                                    <template v-else> เลขที่ใบสำคัญ </template>
                                </strong>
                            </label><br>
                            <el-input v-model="requisition.req_number" style="width: 100%;" disabled/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ผู้รับผิดชอบ </strong>
                            </label><br>
                            <el-input v-model="user.full_name" style="width: 100%;" disabled/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> สถานะ </strong>
                            </label><br>
                            <el-input v-model="requisition.status" style="width: 100%;" disabled/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เลขที่ใบกำกับ </strong>
                            </label><br>
                            <el-input v-model="requisition.invioce_number_ref" style="width: 100%;" disabled/>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card row" style="background-color: #8cbbff; border: 1px solid #8cbbff;">
                    <div class="card-header" style="background-color: #8cbbff; padding: 10px;">
                        <strong> รายการเอกสารส่งเบิก </strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label" style="margin-bottom: 0.4rem;">
                                <!-- เพิ่มเงื่อนไขเช็ค multi -->
                                <strong> ชื่อสั่งจ่าย <span class="text-danger"> * </span></strong> &nbsp;
                            </label><br>
                            <supplier
                                :setData="reqLine.supplier_id"
                                :error="errors.supplier_detail"
                                :editFlag="requisition.multiple_supplier == 'MORE'? true: false"
                                @setSupplier="setSupplierLine"
                            ></supplier>
                            <div id="el_explode_supplier_detail" class="text-danger text-left"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เลขที่บัญชีธนาคาร <span class="text-danger"> *</span></strong>
                            </label><br>
                            <!-- เพิ่มเงื่อนไขเช็ค multi -->
                            <supplierBank
                                :parent="reqLine.supplier_id"
                                :setData="reqLine.bank_account_number"
                                :error="errors.supplier_bank"
                                :editFlag="true"
                                @setSupplierBank="setSupplierBank"
                            ></supplierBank>
                            <div id="el_explode_supplier_bank" class="text-danger text-left"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label" style="margin: 3px;">
                                &nbsp; <br>
                            </label><br>
                            <detailComp
                                :requisition="requisition"
                                :reqLine="reqLine"
                                :errors="errors"
                            ></detailComp>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="reqLine.remaining_receipt_flag == 'Y'">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เลขที่ใบเสร็จรับเงินคงเหลือ <span class="text-danger"> * </span> </strong>
                            </label><br>
                            <remainingReceipt
                                :setData="reqLine.remaining_receipt_id"
                                :editFlag="true"
                                :error="errors.remaining_receipt"
                                @setRemainingReceipt="setRemainingReceipt"
                            ></remainingReceipt>
                            <div id="el_explode_remaining_receipt" class="text-danger text-left"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> รายการบัญชีรับเงินคงเหลือ <span class="text-danger"> * </span> </strong>
                            </label><br>
                            <receiptAccount
                                :parent="reqLine.remaining_receipt_id"
                                :setData="reqLine.receipt_account"
                                :editFlag="true"
                                :error="errors.receipt_account"
                                @setReceiptAccount="setReceiptAccount"
                            ></receiptAccount>
                            <div id="el_explode_receipt_account" class="text-danger text-left"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> แผนงาน <span class="text-danger"> * </span></strong>
                            </label><br>
                            <budgetPlan 
                                :setData="reqLine.budget_plan"
                                :error="errors.budget_plan"
                                :editFlag="true"
                                @setBudgetPlan="setBudgetPlan"
                            ></budgetPlan>
                            <div id="el_explode_budget_plan" class="text-danger text-left"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ประเภทรายจ่าย <span class="text-danger"> * </span></strong>
                            </label><br>
                            <budgetType
                                :parent="reqLine.budget_plan"
                                :setData="reqLine.budget_type"
                                :error="errors.budget_type"
                                :editFlag="true"
                                @setBudgetType="setBudgetType"
                            ></budgetType>
                            <div id="el_explode_budget_type" class="text-danger text-left"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ประเภทค่าใช้จ่าย <span class="text-danger"> * </span></strong>
                            </label><br>
                            <expenseType
                                :parent="reqLine.budget_type"
                                :setData="reqLine.expense_type"
                                :budgetSource="requisition.budget_source"
                                :error="errors.expense_type"
                                :editFlag="true"
                                @setExpenseType="setExpenseType"
                            ></expenseType>
                            <div id="el_explode_expense_type" class="text-danger text-left"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">
                            <strong> รายการบัญชี <span class="text-danger"> * </span></strong>
                        </label><br>
                        <el-input v-model="reqLine.expense_account" style="width: 85%;" ref="expense_account" readonly/>
                        <modalAccountComp
                            :reqLine="reqLine"
                            :defaultSetName="defaultSetName"
                            @updateAccount="updateAccount"
                        />
                        <div id="el_explode_expense_account" class="text-danger text-left"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> จำนวนเงิน <span class="text-danger"> * </span></strong>
                            </label><br>
                            <vue-numeric style="width: 100%;"
                                name="amount"
                                class="form-control text-right"
                                v-model="reqLine.amount"
                                v-bind:minus="false"
                                v-bind:precision="2"
                                :min="-999999999"
                                :max="999999999"
                                autocomplete="off"
                                ref="amount"
                            ></vue-numeric>
                            <div id="el_explode_amount" class="text-danger text-left"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> คำอธิบายรายการ </strong>
                            </label><br>
                            <el-input v-model="reqLine.description" type="textarea" size="default" :rows="2" style="width: 100%;" placeholder="" maxlength="240" show-word-limit/>
                        </div>
                    </div>
                </div>
                <div align="right">
                    <button type="submit" class="btn btn-sm btn-success" @click.prevent="addRequisitionLine()">เพิ่มรายการ</button>
                </div>
                <br>
                <!-- TABLE LINE LISTS-->
                <div class="table-responsive" style="max-height: 600px;">
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
                                :requisition="requisition"
                                :attribute="row"
                                :defaultSetName="defaultSetName"
                                @updateRow="updateRow"
                                @copyRow="copyRow"
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
    import budgetSource     from "../lov/BudgetSource.vue";
    import documentCategory from "../lov/DocumentCategory.vue";
    import paymentType      from "../lov/PaymentType.vue";
    import supplier         from "../lov/Supplier.vue";
    import supplierBank     from "../lov/SupplierBank.vue";
    import budgetPlan       from "../lov/BudgetPlan.vue";
    import budgetType       from "../lov/BudgetType.vue";
    import expenseType      from "../lov/ExpenseType.vue";
    import bankAccount      from "../lov/BankAccount.vue";
    import remainingReceipt from "../lov/RemainingReceipt.vue";
    import receiptAccount   from "../lov/ReceiptAccount.vue";
    import detailComp       from "./DetailComponent.vue";
    import listComp         from "./ListComponent.vue";
    import modalAccountComp from "./_ModalAccountComponent.vue";

    export default {
        components: {
            budgetSource, documentCategory, paymentType, supplier, supplierBank, budgetPlan, budgetType, expenseType, bankAccount, detailComp, listComp, modalAccountComp, remainingReceipt, receiptAccount
        },
        props: ['user', 'referenceNo', 'invoiceTypes', 'defaultSetName', 'defaultSupplier'],
        data() {
            return {
                budgetSource: ['510'], //, '520', '530', '540', '550'
                sourceDefault: ['500', '510', '520', '530', '540', '550'],
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
                    cash_bank_account: false,
                    budget_plan: false,
                    budget_type: false,
                    expense_type: false,
                    expense_account: false,
                    amount: false,
                    remaining_receipt: false,
                },
                requisition: {
                    reference_number: this.referenceNo,
                    budget_source: '',
                    invoice_type: 'STANDARD',
                    document_category: '', // หยิบจาก login
                    req_date: new Date(),
                    payment_type: 'PAYMENT',
                    supplier_id: '',
                    supplier_name: '',
                    multiple_supplier: 'ONE',
                    description: '',
                    req_number: '',
                    requester: '',
                    status: '',
                    invioce_number_ref: '',
                    cash_bank_account_id: '',
                },
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
                    receipt_account: '',
                    receipt_amount: '',
                },
                loading: false,
                linelists: [],
                // SEGMENT
                segment1: '', segment2: '', segment3: '', segment4: '', segment5: '', segment6: '',
                segment7: '', segment8: '', segment9: '', segment10: '', segment11: '', segment12: '', segment13: '',
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
        },
        watch:{
            errors: {
                handler(val){
                    val.invoice_type? this.setError('invoice_type') : this.resetError('invoice_type');
                    val.expense_account? this.setError('expense_account') : this.resetError('expense_account');
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
            changeReqDateFormat() {
                const formattedDate = moment(this.requisition.req_date, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.requisition.req_date = formattedDate;
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
                this.requisition.budget_source = res.budget_source;
                this.isARReceipt(res.budget_source);
                this.getDocumentCate(res.budget_source);
                // DEFAULT VALUE LINE
                if(this.sourceDefault.indexOf(this.requisition.budget_source) !== -1){
                    this.reqLine.budget_plan = 'EXP.400.000000.0000000000';
                    this.reqLine.budget_type = 'EXP.400.400000.0000000000';
                }else{
                    this.reqLine.budget_plan = '';
                    this.reqLine.budget_type = '';
                }
            },
            setDocumentCate(res){
                this.requisition.document_category = res.document_category;
            },
            setPaymentType(res){
                this.requisition.payment_type = res.payment_type;
            },
            setSupplierHeader(res){
                this.requisition.supplier_id = res.supplier;
                this.requisition.supplier_name = res.vendor_name;
                if(this.requisition.multiple_supplier == 'ONE'){
                    this.reqLine.supplier_id = res.supplier;
                    this.reqLine.supplier_name = res.vendor_name;
                }
            },
            setSupplierLine(res){
                this.reqLine.supplier_id = res.supplier;
                this.reqLine.supplier_name = res.vendor_name;
            },
            setSupplierBank(res){
                this.reqLine.supplier_site = res.supplier_site;
                this.reqLine.bank_account_number = res.supplier_bank;
            },
            setBankAccount(res){
                this.requisition.cash_bank_account_id = res.cash_bank_account_id;
            },
            setBudgetPlan(res){
                this.reqLine.budget_plan = res.budget_plan;
            },
            setBudgetType(res){
                this.reqLine.budget_type = res.budget_type;
            },
            setExpenseType(res){
                this.reqLine.expense_type = res.expense_type;
                this.reqLine.expense_description = res.expense_description;
                this.reqLine.description = res.expense_description;
                // GET EXPENSE ACCOUNT WHEN CHOOSE EXPENSE_TYPE
                if(this.reqLine.expense_type != ''){
                    this.getExpenseAccount();
                }
            },
            setRemainingReceipt(res){
                this.reqLine.remaining_receipt_id = res.remaining_receipt;
                this.reqLine.receipt_amount = res.receipt_amount;
            },
            setReceiptAccount(res){
                this.reqLine.receipt_account = res.receipt_account;
                this.reqLine.expense_account = res.receipt_account;
                this.reqLine.receipt_amount = res.receipt_amount;
                this.extractAccount();
            },
            changeSupplierType(){
                this.resetValues();
                if(this.requisition.multiple_supplier == 'ONE'){
                    this.requisition.supplier_id = '';
                    this.requisition.supplier_name = '';
                    this.reqLine.supplier_id = this.requisition.supplier_id;
                    this.reqLine.supplier_name = this.requisition.supplier_name;
                }else{
                    this.requisition.supplier_id = this.defaultSupplier.vendor_id;
                    this.requisition.supplier_name = this.defaultSupplier.vendor_name;
                    this.reqLine.supplier_id = '';
                    this.reqLine.supplier_name = '';
                    this.reqLine.bank_account_number = '';
                    this.reqLine.supplier_site = '';
                }
            },
            async getExpenseAccount(){
                var vm = this;
                if(vm.reqLine.remaining_receipt_flag == 'N' && (vm.reqLine.expense_type != '' || vm.reqLine.expense_type != undefined)){
                    axios.post('/expense/api/requisition/get-expense-account', {
                        header: vm.requisition,
                        line: vm.reqLine,
                    })
                    .then(function (res) {
                        vm.loading = false;
                        if (res.data.message) {
                            vm.reqLine.expense_account = '';
                        } else {
                            vm.reqLine.expense_account = res.data.expense_account;
                            this.extractAccount();
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
            },
            updateAccount(res) {
                this.reqLine.expense_account = res.expense_account;
                this.extractAccount();
            },
            addRequisitionLine() {
                var vm = this;
                var form = $('#create-form');
                let errorMsg = '';
                this.resetValues();
                let valid = true;
                if (vm.requisition.supplier_id == '') {
                    vm.errors.supplier = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกผู้สั่งจ่าย";
                    $(form).find("div[id='el_explode_supplier']").html(errorMsg);
                }

                if (vm.requisition.payment_type == 'NON-PAYMENT' && vm.requisition.cash_bank_account_id == '') {
                    vm.errors.cash_bank_account = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกธนาคาร";
                    $(form).find("div[id='el_explode_cash_bank_account']").html(errorMsg);
                }

                if (vm.reqLine.supplier_id == '') {
                    vm.errors.supplier_detail = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกผู้สั่งจ่าย";
                    $(form).find("div[id='el_explode_supplier_detail']").html(errorMsg);
                }
                if (vm.reqLine.bank_account_number == '') {
                    vm.errors.supplier_bank = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกเลขที่บัญชีธนาคาร";
                    $(form).find("div[id='el_explode_supplier_bank']").html(errorMsg);
                }
                if (vm.reqLine.budget_plan == '') {
                    vm.errors.budget_plan = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกแผนงาน";
                    $(form).find("div[id='el_explode_budget_plan']").html(errorMsg);
                }
                if (vm.reqLine.budget_type == '') {
                    vm.errors.budget_type = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกประเภทรายจ่าย";
                    $(form).find("div[id='el_explode_budget_type']").html(errorMsg);
                }
                if (vm.reqLine.expense_type == '') {
                    vm.errors.expense_type = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกประเภทค่าใช้จ่าย";
                    $(form).find("div[id='el_explode_expense_type']").html(errorMsg);
                }
                if (vm.reqLine.amount == '' || vm.reqLine.amount == 0) {
                    vm.errors.amount = true;
                    valid = false;
                    errorMsg = "กรุณากรอกจำนวนเงิน";
                    $(form).find("div[id='el_explode_amount']").html(errorMsg);
                }
                if (vm.reqLine.remaining_receipt_flag == 'Y' && vm.reqLine.remaining_receipt_id == '') {
                    vm.errors.remaining_receipt = true;
                    valid = false;
                    errorMsg = "กรุณาระบุเลขที่ใบเสร็จรับเงินคงเหลือ";
                    $(form).find("div[id='el_explode_remaining_receipt']").html(errorMsg);
                }
                if (vm.reqLine.remaining_receipt_flag == 'Y' && vm.reqLine.receipt_account == '') {
                    vm.errors.receipt_account = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกรายการบัญชีรับเงินคงเหลือ";
                    $(form).find("div[id='el_explode_receipt_account']").html(errorMsg);
                }
                if (vm.reqLine.amount > vm.reqLine.receipt_amount) {
                    vm.errors.amount = true;
                    valid = false;
                    errorMsg = "จำนวนเงินที่ระบุ เกินกว่า งบประมาณที่มี";
                    $(form).find("div[id='el_explode_amount']").html(errorMsg);
                }
                if ((vm.segment6 == '' || vm.segment6 == undefined) || (vm.segment7 == '' || vm.segment7 == undefined) || (vm.segment9 == '' || vm.segment9 == undefined) || (vm.segment10 == '' || vm.segment10 == undefined) || (vm.segment11 == '' || vm.segment11 == undefined)) {
                    vm.errors.expense_account = true;
                    valid = false;
                    errorMsg = "กรุณาตรวจสอบรายการบัญชี";
                    $(form).find("div[id='el_explode_expense_account']").html(errorMsg);
                }
                if (!valid) {
                    return;
                }
                // INSERT RECEIPT TEMP WHEN IN_ARRAY
                if (vm.reqLine.remaining_receipt_flag == 'Y') {
                    axios.post('/expense/requisition/use-ar-receipt', {
                        header: this.requisition,
                        line: this.reqLine,
                        seq: this.linelists.length
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
                            console.log(JSON.stringify(this.reqLine));
                            console.log(JSON.parse(JSON.stringify(this.reqLine)));
                            this.linelists.push(JSON.parse(JSON.stringify(this.reqLine)));
                            let defaultLine = {
                                budget_plan: '',
                                budget_type: '',
                                supplier_id: this.requisition.multiple_supplier == 'ONE' ? this.requisition.supplier_id : '',
                                supplier_name: this.requisition.multiple_supplier == 'ONE' ? this.requisition.supplier_name : '',
                                bank_account_number: this.requisition.multiple_supplier == 'ONE' ? this.reqLine.bank_account_number : '',
                                expense_type: '',
                                expense_account: '',
                                expense_description: '',
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
                                remaining_receipt_flag: this.budgetSource.indexOf(this.requisition.budget_source) !== -1? 'Y': 'N',
                                remaining_receipt_id: '',
                                receipt_account: ''
                            };
                            Object.assign(this.reqLine, defaultLine);
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
                }else{
                    console.log(JSON.stringify(this.reqLine));
                    console.log(JSON.parse(JSON.stringify(this.reqLine)));
                    this.linelists.push(JSON.parse(JSON.stringify(this.reqLine)));
                    let defaultLine = {
                        budget_plan: '',
                        budget_type: '',
                        supplier_id: this.requisition.multiple_supplier == 'ONE' ? this.requisition.supplier_id : '',
                        supplier_name: this.requisition.multiple_supplier == 'ONE' ? this.requisition.supplier_name : '',
                        bank_account_number: this.requisition.multiple_supplier == 'ONE' ? this.reqLine.bank_account_number : '',
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
                        remaining_receipt_flag: this.budgetSource.indexOf(this.requisition.budget_source) !== -1? 'Y': 'N',
                        remaining_receipt_id: '',
                        receipt_account: ''
                    };
                    Object.assign(this.reqLine, defaultLine);
                }
            },
            updateRow(response){
                console.log(response.line);
                var vm = this;
                let index = response.index;
                if (vm.linelists[index].remaining_receipt_flag == 'Y') {
                    axios.post('/expense/requisition/update-ar-receipt', {
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
                                            remaining_receipt_id: valUpdate.remaining_receipt_id
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
                                    remaining_receipt_id: valUpdate.remaining_receipt_id
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
            copyRow(index) {
                var vm = this;
                let copyLine = JSON.parse(JSON.stringify(vm.linelists[index]));
                if (copyLine.remaining_receipt_flag == 'Y') {
                    axios.post('/expense/requisition/use-ar-receipt', {
                        header: vm.requisition,
                        line: copyLine,
                        seq: vm.linelists.length
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
                            vm.linelists.push(JSON.parse(JSON.stringify(copyLine)));
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
                }else{
                    vm.linelists.push(JSON.parse(JSON.stringify(copyLine)));
                }
            },
            removeRow(index) {
                var vm = this;
                if (vm.linelists[index].remaining_receipt_flag == 'Y') {
                    axios.post('/expense/requisition/remove-ar-receipt', {
                        header: vm.requisition,
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
                }else{
                    vm.linelists.splice(index, 1);
                }
            },
            resetValues(){
                var vm = this;
                var form = $('#create-form');
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
                $(form).find("div[id='el_explode_cash_bank_account']").html("");
                $(form).find("div[id='el_explode_budget_plan']").html("");
                $(form).find("div[id='el_explode_budget_type']").html("");
                $(form).find("div[id='el_explode_expense_type']").html("");
                $(form).find("div[id='el_explode_amount']").html("");
                $(form).find("div[id='el_explode_expense_account']").html("");
            },
            async store(){
                var vm = this;
                var form = $('#create-form');
                let errorMsg = '';
                this.resetValues();
                let valid = true;
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
                    errorMsg = "กรุณากรอกคำฮธิบาย";
                    $(form).find("div[id='el_explode_header_desc']").html(errorMsg);
                }
                if (vm.linelists.length == 0) {
                    valid = false;
                    this.$notify({
                        title: 'แจ้งเตือน',
                        message: 'ไม่พบข้อมูลรายการ กรุณาตรวจสอบ',
                        type: 'warning'
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
                        this.importData();
                    }
                });
            },
            async importData(){
                var vm = this;
                Swal.fire({
                    title: 'ระบบกำลังส่งข้อมูลเอกสารส่งเบิก',
                    type: "success",
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                // POST METHOD
                axios.post('/expense/requisition/', {
                    header: this.requisition,
                    lines: this.linelists,
                    totalApply: this.totalApply,
                    refRequisition: null,
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
            },
            isARReceipt(budgetSource) {
                this.reqLine.remaining_receipt_flag = this.budgetSource.indexOf(budgetSource) !== -1? 'Y': 'N';
            },
            getDocumentCate(budgetSource){
                axios.get(`/expense/api/requisition/get-document-category`, {
                    params: {
                        budget_source: budgetSource
                    }
                })
                .then(res => {
                    // if(budgetSource != null && res.data.data == null){
                    //     this.$notify({
                    //         title: 'แจ้งเตือน',
                    //         message: 'ไม่พบข้อมูล กรุณาตรวจสอบ',
                    //         type: 'warning'
                    //     });
                    // }else if(budgetSource != null || budgetSource != ''){
                        // console.log(res.data.data);
                        this.requisition.document_category = res.data.data? res.data.data.tag: '';
                    // }
                })
                .catch((error) => {
                    this.$notify({
                        title: 'แจ้งเตือน',
                        message: error,
                        type: 'warning'
                    });
                })
            },
            extractAccount(){
                var coa = this.reqLine.expense_account.split('.');
                this.segment1 = coa[0];
                this.segment2 = coa[1];
                this.segment3 = coa[2];
                this.segment4 = coa[3];
                this.segment5 = coa[4];
                this.segment6 = coa[5];
                this.segment7 = coa[6];
                this.segment8 = coa[7];
                this.segment9 = coa[8];
                this.segment10 = coa[9];
                this.segment11 = coa[10];
                this.segment12 = coa[11];
                this.segment13 = coa[12];
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