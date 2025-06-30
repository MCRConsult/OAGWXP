<template>
    <div v-loading="loading">
        <form id="edit-form">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เลขที่ใบสำคัญ </strong>
                            </label><br>
                            <el-input v-model="header.voucher_number" style="width: 100%;" placeholder="" disabled/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ผู้รับผิดชอบ </strong>
                            </label><br>
                            <el-input v-model="header.user.hr_employee.full_name" style="width: 100%;" placeholder="" disabled/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> สถานะ </strong>
                            </label><br>
                            <el-input v-model="header.status_text" style="width: 100%;" placeholder="" disabled/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ประเภทการขอเบิก </strong>
                            </label><br>
                            <el-select v-model="header.invoice_type" placeholder="" style="width: 100%;" disabled>
                                <el-option
                                    v-for="type in invoiceTypes"
                                    :key="type.lookup_code"
                                    :label="type.description"
                                    :value="type.lookup_code"
                                />
                            </el-select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> สำนักงานผู้เบิกจ่าย </strong>
                            </label><br>
                            <documentCategory
                                :setData="header.document_category"
                                :error="errors.document_category"
                                :editFlag="header.source_type == 'REQUISITION'? false: true"
                                @setDocumentCate="setDocumentCate"
                            ></documentCategory>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> วันที่เอกสารขอเบิก <span class="text-danger"> *</span></strong>
                            </label><br>
                            <el-date-picker
                                v-model="header.invoice_date"
                                ref="invoice_date"
                                placeholder=""
                                clearable
                                format="DD-MM-YYYY"
                                style="width: 100%;"
                                @change="changeInvDateFormat"
                            />
                            <div id="el_explode_invoice_date" class="text-danger text-left"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ชื่อผู้สั่งจ่าย </strong>
                            </label><br>
                            <supplier
                                :setData="header.supplier_id"
                                :error="errors.supplier"
                                :editFlag="false"
                                @setSupplier="setSupplier"
                            ></supplier>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เลขที่ใบกำกับ </strong>
                            </label><br>
                            <el-input v-model="header.invoice_number" style="width: 100%;" placeholder="" disabled/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> สกุลเงิน <span class="text-danger"> *</span></strong>
                            </label><br>
                            <currency
                                :setData="header.currency"
                                :error="errors.currency"
                                :editFlag="true"
                                @setCurrency="setCurrency"
                            ></currency>
                            <div id="el_explode_currency" class="text-danger text-left"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> วิธีการจ่ายเงิน <span class="text-danger"> *</span></strong>
                            </label><br>
                            <paymentMethod
                                :setData="header.payment_method"
                                :error="errors.payment_method"
                                :editFlag="true"
                                @setPaymentMethod="setPaymentMethod"
                            />
                            <div id="el_explode_payment_method" class="text-danger text-left"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เทอมการชำระเงิน <span class="text-danger"> *</span></strong>
                            </label><br>
                            <paymentTerm
                                :setData="header.payment_term"
                                :error="errors.payment_term"
                                :editFlag="true"
                                @setPaymentTerm="setPaymentTerm"
                            />
                            <div id="el_explode_payment_term" class="text-danger text-left"></div>
                        </div>
                    </div>
                     <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> วันที่เคลียร์เงินยืม </strong>
                            </label><br>
                            <el-date-picker
                                v-model="header.clear_date"
                                placeholder=""
                                clearable
                                format="DD-MM-YYYY"
                                style="width: 100%;"
                                @change="changeClearDateFormat"
                            />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> วันที่สัญญายืมเงิน </strong>
                            </label><br>
                            <el-date-picker
                                v-model="header.contact_date"
                                placeholder=""
                                clearable
                                format="DD-MM-YYYY"
                                style="width: 100%;"
                                @change="changeContactDateFormat"
                            />
                        </div>
                    </div>
                    <div class="col-md-3" v-if="budgetSource.indexOf(header.budget_source)">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ใบโอนล้างถึงที่สุด (บอ.) </strong>
                            </label><br>
                            <yesnoType
                                :setData="header.final_judgment"
                                :error="errors.final_judgment"
                                :editFlag="true"
                                @setFinalJudgment="setFinalJudgment"
                            ></yesnoType>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เลขที่เอกสาร JV/KL (GFMIS) </strong>
                            </label><br>
                            <el-input v-model="header.gfmis_document_number" style="width: 100%;" placeholder=""/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> คำอธิบาย </strong>
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
                    <div class="col-md-6">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> หมายเหตุ </strong>
                            </label><br>
                            <el-input v-model="header.note" type="textarea" :rows="2" style="width: 100%;" placeholder="" maxlength="150" show-word-limit/>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card row" style="background-color: #8cbbff; border: 1px solid #8cbbff;">
                    <div class="card-header" style="background-color: #8cbbff; padding: 10px;">
                        <strong> ข้อมูลรายการขอเบิก </strong>
                    </div>
                </div>
                <!-- TABLE LINE LISTS-->
                <div class="table-responsive" style="max-height: 600px;">
                    <table class="table text-nowrap table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">
                                   <div style="width: 70px;"> รายการที่ </div> 
                                </th>
                                <th class="text-left">
                                    <div style="width: 170px;"> ประเภทค่าใช้จ่าย </div>
                                </th>
                                <th class="text-left">
                                    <div style="width: 300px;"> รายการบัญชี </div>
                                </th>
                                <th class="text-center">
                                    <div style="width: 120px;"> จำนวนเงิน </div>
                                </th>
                                <th class="text-center">
                                    <div style="width: 170px;"> ชื่อสั่งจ่าย </div>
                                </th>
                                <th class="text-center">
                                    <div style="width: 170px;"> เลขที่บัญชีธนาคาร </div>
                                </th>
                                <th class="text-center">
                                    <div style="width: 50px;"> </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <listComp
                                v-for="(row, index) in linelists"
                                :key="index"
                                :index="index"
                                :attribute="row"
                                :defaultSetName="defaultSetName"
                                @updateRow="updateRow"
                                @copyRow="copyRow"
                                @removeRow="removeRow"
                            />
                        </tbody>
                    </table>
                </div>
                <div class="row m-t-sm mt-3">
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
                <br>
                <div align="center">
                    <button type="button" class="btn btn-save" @click.prevent="update('UPDATE')" style="color: #FFF;"> บันทึกรายการ </button>
                    <button type="button" class="btn btn-danger ml-1" @click.prevent="cancel()"> ยกเลิกรายการ </button>
                    <button v-if="confirm_flag" type="button" class="btn btn-primary ml-1" 
                        @click.prevent="interface('INTERFACE')">
                        ขอเบิก
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import moment           from "moment";
    import numeral          from "numeral";
    import Swal             from 'sweetalert2';

    import documentCategory from "../lov/DocumentCategory.vue";
    import supplier         from "../lov/Supplier.vue";
    import supplierBank     from "../lov/SupplierBank.vue";
    import budgetPlan       from "../lov/BudgetPlan.vue";
    import budgetType       from "../lov/BudgetType.vue";
    import expenseType      from "../lov/ExpenseType.vue";
    import paymentMethod    from "../lov/PaymentMethod.vue";
    import paymentTerm      from "../lov/PaymentTerm.vue";
    import currency         from "../lov/Currency.vue";
    import yesnoType        from "../lov/YesNoType.vue";
    import listComp         from "./ListComponent.vue";

    export default {
        components: {
            documentCategory, supplier, supplierBank, budgetPlan, budgetType, expenseType, paymentMethod, paymentTerm, currency, yesnoType, listComp
        },
        props: ['invoice', 'invoiceTypes', 'defaultSetName'],
        data() {
            return {
                budgetSource: ['510'],
                errors: {
                    invoice_date: false,
                    currency: false,
                    payment_method: false,
                    payment_term: false,
                    header_desc: false,
                },
                loading: false,
                header: this.invoice,
                linelists: this.invoice.lines,
                confirm_flag: false,
            };
        },
        mounted(){
            //
        },
        computed: {
            totalApply() {
                return this.linelists.reduce((accumulator, line) => {
                    this.totalApplyAmount = accumulator + parseFloat(line.amount);
                    return accumulator + parseFloat(line.amount);
                }, 0);
            },
        },
        methods: {
            numberFormat(value) {
                if (!value) return "0.00";
                return numeral(value).format("0,0.00");
            },
            changeInvDateFormat() {
                const formattedDate = moment(this.header.invoice_date, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.header.invoice_date = formattedDate;
            },
            changeClearDateFormat() {
                const formattedDate = moment(this.header.clear_date, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.header.clear_date = formattedDate;
            },
            changeContactDateFormat() {
                const formattedDate = moment(this.header.contact_date, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.header.contact_date = formattedDate;
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
            setDocumentCate(res){
                this.header.document_category = res.document_category;
            },
            setPaymentType(res){
                this.requisition.payment_type = res.payment_type;
            },
            setSupplier(res){
                this.header.supplier = res.supplier;
                this.header.supplier_name = res.vendor_name;
            },
            setPaymentMethod(res){
                this.header.payment_method = res.payment_method;
            },
            setPaymentTerm(res){
                this.header.payment_term = res.payment_term;
            },
            setCurrency(res){
                this.header.currency = res.currency;
            },
            setFinalJudgment(res){
                this.header.final_judgment = res.final_judgment;
            },
            updateRow(response){
                var vm = this;
                let index = response.index;
                if (vm.linelists[index].remaining_receipt_flag == 'Y') {
                    axios.post('/expense/requisition/update-ar-receipt', {
                        header: vm.invoice,
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
                                        Object.assign(currentItem, {
                                            bank_account_number: valUpdate.bank_account_number,
                                            budget_plan: valUpdate.budget_plan,
                                            budget_type: valUpdate.budget_type,
                                            expense_type: valUpdate.expense_type,
                                            expense_description: valUpdate.expense_description,
                                            expense_account: valUpdate.expense_account,
                                            amount: valUpdate.amount,
                                            description: valUpdate.description,
                                            tax_code: valUpdate.tax_code,
                                            tax_amount: valUpdate.tax_amount,
                                            wht_code: valUpdate.wht_code,
                                            wht_amount: valUpdate.wht_amount,
                                            ar_receipt_id: valUpdate.ar_receipt_id,
                                            ar_receipt_number: valUpdate.ar_receipt_number,
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
                                    bank_account_number: valUpdate.bank_account_number,
                                    budget_plan: valUpdate.budget_plan,
                                    budget_type: valUpdate.budget_type,
                                    expense_type: valUpdate.expense_type,
                                    expense_description: valUpdate.expense_description,
                                    expense_account: valUpdate.expense_account,
                                    amount: valUpdate.amount,
                                    description: valUpdate.description,
                                    tax_code: valUpdate.tax_code,
                                    tax_amount: valUpdate.tax_amount,
                                    wht_code: valUpdate.wht_code,
                                    wht_amount: valUpdate.wht_amount,
                                    ar_receipt_id: valUpdate.ar_receipt_id,
                                    ar_receipt_number: valUpdate.ar_receipt_number,
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
                let copyLine = JSON.parse(JSON.stringify(this.linelists[index]));
                this.linelists.push(JSON.parse(JSON.stringify(copyLine)));
            },
            removeRow(index) {
                this.linelists.splice(index, 1);
            },
            async update(activity){
                var vm = this;
                var form = $('#edit-form');
                let errorMsg = '';
                let valid = true;
                vm.errors.invoice_date = false;
                vm.errors.currency = false;
                vm.errors.invoice_date = false;
                vm.errors.payment_term = false;
                vm.errors.header_desc = false;
                $(form).find("div[id='el_explode_invoice_date']").html(errorMsg);
                $(form).find("div[id='el_explode_currency']").html(errorMsg);
                $(form).find("div[id='el_explode_payment_method']").html(errorMsg);
                $(form).find("div[id='el_explode_payment_term']").html(errorMsg);
                $(form).find("div[id='el_explode_header_desc']").html(errorMsg);
                if (vm.header.currency == '' || vm.header.currency == null) {
                    vm.errors.currency = true;
                    valid = false;
                    errorMsg = "กรุณาระบุสกลเงิน";
                    $(form).find("div[id='el_explode_currency']").html(errorMsg);
                }
                if (vm.header.payment_method == '' || vm.header.payment_method == null) {
                    vm.errors.payment_method = true;
                    valid = false;
                    errorMsg = "กรุณาระบุวิธีการจ่ายเงิน";
                    $(form).find("div[id='el_explode_payment_method']").html(errorMsg);
                }
                if (vm.header.payment_term == '' || vm.header.payment_term == null) {
                    vm.errors.payment_term = true;
                    valid = false;
                    errorMsg = "กรุณาระบุเทอมการชำระเงิน";
                    $(form).find("div[id='el_explode_payment_term']").html(errorMsg);
                }
                if (vm.header.description == '' || vm.header.description == null) {
                    vm.errors.header_desc = true;
                    valid = false;
                    errorMsg = "กรุณากรอกคำอธิบาย";
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
                    title: 'ระบบกำลังบันทึกเอกสารขอเบิก',
                    type: "success",
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                axios.post('/expense/invoice/'+vm.header.id+'/update', {
                    header: vm.header,
                    lines: vm.linelists,
                    totalApply: vm.totalApply,
                    activity: activity,
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
                        vm.confirm_flag = true;
                        Swal.fire({
                            title: "บันทึกเอกสารขอเบิก",
                            html: "บันทึกเอกสารขอเบิกเรียบร้อยแล้ว",
                            icon: "success",
                            showCancelButton: false,
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "ตกลง",
                            allowOutsideClick: false
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
                    // vm.loading = false;
                });
            },
            async interface(activity){
                var vm = this;
                Swal.fire({
                    title: "ขอเบิกเอกสาร",
                    html: "ต้องการขอเบิกเอกสารใช่หรือไม่?",
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
                            title: 'ระบบกำลังส่งข้อมูลเอกสารขอเบิก',
                            type: "success",
                            showConfirmButton: false
                        });
                        axios.post('/expense/invoice/'+vm.header.id+'/update', {
                            header: vm.header,
                            lines: vm.linelists,
                            totalApply: vm.totalApply,
                            activity: activity,
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
                                    title: "ขอเบิกเอกสาร",
                                    html: "ขอเบิกเอกสารเรียบร้อยแล้ว",
                                    icon: "success",
                                    showCancelButton: false,
                                    confirmButtonColor: "#3085d6",
                                    confirmButtonText: "ตกลง",
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        setTimeout(function() {
                                            location.href = '/expense/invoice/'+vm.header.id;
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
            async cancel(){
                var vm = this;
                Swal.fire({
                    title: "ยกเลิกเอกสารขอเบิก",
                    html: "ต้องการยกเลิกเอกสารขอเบิกใช่หรือไม่?",
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
                            title: 'ระบบกำลังยกเลิกเอกสารขอเบิก',
                            type: "success",
                            showConfirmButton: false
                        });
                        axios.post('/expense/invoice/'+vm.header.id+'/cancel', {
                            header: vm.header
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
                                    title: "ยกเลิกเอกสารขอเบิก",
                                    html: "ยกเลิกเอกสารขอเบิกเรียบร้อยแล้ว",
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
                            // vm.loading = false;
                        });
                    }
                });
            },
        },
    }
</script>

<style scope>
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
        top:0px;
    }
</style>