<template>
    <button type="button" class="btn btn-sm btn-warning m-1" data-toggle="collapse" @click.prevent="openModal(index)">
        แก้ไข
    </button>
    <div :class="'modal fade modal-edit'+index" aria-labelledby="myModalLabel" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> แก้ไขรายการ </h4>
                </div>
                <div class="modal-body m-2">
                    <form :class="'edit-form'+index">
                        <div class="row">
                            <div class="col-md-3 text-left">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label" style="margin-bottom: 0.4rem;">
                                        <strong> ชื่อสั่งจ่าย <span class="text-danger"> * </span></strong> &nbsp;
                                    </label><br>
                                    <supplier
                                        :setData="temp.supplier_id"
                                        :error="errors.supplier_detail"
                                        :editFlag="requisition.multiple_supplier == 'MORE'? true: false"
                                        @setSupplier="setSupplierLine"
                                    ></supplier>
                                    <div id="_el_explode_supplier_detail" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-md-3 text-left">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> เลขที่บัญชีธนาคาร <span class="text-danger"> *</span></strong>
                                    </label><br>
                                    <supplierBank
                                        :parent="temp.supplier_id"
                                        :setData="temp.bank_account_number"
                                        :error="errors.supplier_bank"
                                        :editFlag="true"
                                        @setSupplierBank="setSupplierBank"
                                    ></supplierBank>
                                    <!-- :editFlag="requisition.multiple_supplier == 'MORE'? true: false" -->
                                    <div id="_el_explode_supplier_bank" class="text-danger text-left"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-left">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> แผนงาน <span class="text-danger"> * </span></strong>
                                    </label><br>
                                    <budgetPlan 
                                        :setData="temp.budget_plan"
                                        :error="errors.budget_plan"
                                        :editFlag="false"
                                        @setBudgetPlan="setBudgetPlan"
                                    ></budgetPlan>
                                    <div id="_el_explode_budget_plan" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-md-3 text-left">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> ประเภทรายจ่าย <span class="text-danger"> * </span></strong>
                                    </label><br>
                                    <budgetType
                                        :parent="temp.budget_plan"
                                        :setData="temp.budget_type"
                                        :error="errors.budget_type"
                                        :editFlag="false"
                                        @setBudgetType="setBudgetType"
                                    ></budgetType>
                                    <div id="_el_explode_budget_type" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-md-3 text-left">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> ประเภทค่าใช้จ่าย <span class="text-danger"> * </span></strong>
                                    </label><br>
                                    <expenseType
                                        :parent="temp.budget_type"
                                        :setData="temp.expense_type"
                                        :budgetSource="requisition.budget_source"
                                        :error="errors.expense_type"
                                        :editFlag="false"
                                        @setExpenseType="setExpenseType"
                                    ></expenseType>
                                    <div id="_el_explode_expense_type" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-md-3 text-left">
                                <div class="form-group text-left" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> รายการบัญชี </strong>
                                    </label><br>
                                    <el-input name="expense_account" placeholder="" v-model="temp.expense_account" size="default" style="width: 100%" readonly
                                    > </el-input>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-left">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> จำนวนเงิน <span class="text-danger"> * </span></strong>
                                    </label><br>
                                    <vue-numeric style="width: 100%;"
                                        name="amount"
                                        v-bind:minus="true"
                                        v-bind:precision="2"
                                        :min="-999999999"
                                        :max="999999999"
                                        class="form-control text-right"
                                        v-model="temp.actual_amount"
                                        ref="amount"
                                        autocomplete="off"
                                    ></vue-numeric>
                                    <div id="_el_explode_amount" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-md-6 text-left">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> คำอธิบายรายการ </strong>
                                    </label><br>
                                    <el-input v-model="temp.description" type="textarea" :rows="2" style="width: 100%;" placeholder="" maxlength="240" show-word-limit/>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3 text-left">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> ทะเบียนรถยนต์ </strong>
                                    </label><br>
                                    <el-input v-model="temp.vehicle_number" style="width: 100%;" placeholder=""/>
                                </div>
                            </div>
                            <div class="col-md-3 text-left">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> เลขที่กรมธรรม์ </strong>
                                    </label><br>
                                    <el-input v-model="temp.policy_number" style="width: 100%;" placeholder=""/>
                                </div>
                            </div>
                            <div class="col-md-3 text-left">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> ประเภทน้ำมัน </strong>
                                    </label><br>
                                    <vehicleOilType
                                        :setData="temp.vehicle_oil_type"
                                        :editFlag="true"
                                        @setVehicleOilType="setVehicleOilType"
                                    ></vehicleOilType>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-left">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> ประเภทค่าสาธารณูปโภค </strong>
                                    </label><br>
                                    <utilityType
                                        :setData="temp.utility_type"
                                        :editFlag="true"
                                        @setUtilityType="setUtilityType"
                                    ></utilityType>
                                </div>
                            </div>
                            <div class="col-md-3 text-left">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> อาคาร/รหัสลูกค้า/ธพส. </strong>
                                    </label><br>
                                    <utilityDetail
                                        :parent="temp.utility_type"
                                        :setData="temp.utility_detail"
                                        :editFlag="true"
                                        @setUtilityDetail = "setUtilityDetail"
                                    ></utilityDetail>
                                </div>
                            </div>
                            <div class="col-md-3 text-left">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> เลขที่ใบแจ้งหนี้ </strong>
                                    </label><br>
                                    <el-input v-model="temp.invoice_number" style="width: 100%;" placeholder=""/>
                                </div>
                            </div>
                            <div class="col-md-3 text-left">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> วันที่ใบแจ้งหนี้ </strong>
                                    </label><br>
                                    <el-date-picker
                                        v-model="temp.invoice_date"
                                        type="date"
                                        placeholder=""
                                        clearable
                                        format="DD-MM-YYYY"
                                        style="width: 100%;;"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-left">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> จำนวนหน่วยที่ใช้ </strong>
                                    </label><br>
                                    <el-input v-model="temp.unit_quantity" style="width: 100%;" placeholder=""/>
                                </div>
                            </div>
                            <div class="col-md-3 text-left">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> วันที่รับ </strong>
                                    </label><br>
                                    <el-date-picker
                                        v-model="temp.receipt_date"
                                        type="date"
                                        placeholder=""
                                        clearable
                                        format="DD-MM-YYYY"
                                        style="width: 100%;"
                                    />
                                </div>
                            </div>
                            <div class="col-md-3 text-left">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> เลขที่หนังสือ </strong>
                                    </label><br>
                                    <el-input v-model="temp.receipt_number" style="width: 100%;" placeholder=""/>
                                </div>
                            </div>
                            <div v-if="line.remaining_receipt_flag == 'Y'" class="col-md-3 text-left">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> เลขที่ใบเสร็จรับเงินคงเหลือ <span class="text-danger"> * </span> </strong>
                                    </label><br>
                                    <remainingReceipt
                                        :setData="temp.remaining_receipt_id"
                                        :editFlag="true"
                                        :error="errors.remaining_receipt"
                                        @setRemainingReceipt="setRemainingReceipt"
                                    ></remainingReceipt>
                                    <div id="_el_explode_remaining_receipt" class="text-danger text-left"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer pt-2">
                    <button type="button" class="btn btn-primary btn-sm" @click.private="confirm(index)"
                        style="color: #fff; background-color: #01b471; border-color: #01b471;">
                        ตกลง
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" @click.private="cancel">
                        ยกเลิก
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import supplier         from "../../../lov/Supplier.vue";
    import supplierBank     from "../../../lov/SupplierBank.vue";
    import vehicleOilType   from "../../../lov/vehicleOilType.vue";
    import utilityType      from "../../../lov/UtilityType.vue";
    import utilityDetail    from "../../../lov/UtilityDetail.vue";
    import budgetPlan       from "../../../lov/BudgetPlan.vue";
    import budgetType       from "../../../lov/BudgetType.vue";
    import expenseType      from "../../../lov/ExpenseType.vue";
    import remainingReceipt from "../../../lov/RemainingReceipt.vue";
    import coaComponent     from '../../InputCOAComponent.vue';

    export default {
        components: {
            supplier, supplierBank, vehicleOilType, utilityType, utilityDetail, budgetPlan, budgetType, expenseType, remainingReceipt, coaComponent
        },
        props: ['index', 'requisition', 'reqLine', 'defaultSetName'],
        emits: ['updateRow'],
        data() {
            return {
                line: this.reqLine,
                loading: false,
                accounrCollp: false,
                temp: {},
                errors: {
                    supplier_detail: false,
                    supplier_bank: false,
                    cash_bank_account: false,
                    budget_plan: false,
                    budget_type: false,
                    expense_type: false,
                    amount: false,
                    remaining_receipt: false,
                },
            };
        },
        mounted() {
            this.line = this.reqLine;
            this.copyDataForEdit();
        },
        watch: {
            errors: {
                handler(val){
                    val.amount? this.setError('amount') : this.resetError('amount');
                },
                deep: true,
            },
            reqLine() {
                return this.line = this.reqLine;
            },
        },
        methods: {
            openModal(index){
                this.copyDataForEdit();
                $('.modal-edit'+index).modal('show');
            },
            copyDataForEdit() {
                this.temp = JSON.parse(JSON.stringify(this.line));
            },
            confirm(index) {
                let vm = this;
                let errorMsg = '';
                let valid = true;
                var form = $('.edit-form'+index);
                vm.errors.supplier_detail = false;
                vm.errors.supplier_bank = false;
                vm.errors.budget_plan = false;
                vm.errors.budget_type = false;
                vm.errors.expense_type = false;
                vm.errors.amount = false;
                vm.errors.remaining_receipt = false;

                $(form).find("div[id='_el_explode_supplier_detail']").html("");
                $(form).find("div[id='_el_explode_supplier_bank']").html("");
                $(form).find("div[id='_el_explode_cash_bank_account']").html("");
                $(form).find("div[id='_el_explode_budget_plan']").html("");
                $(form).find("div[id='_el_explode_budget_type']").html("");
                $(form).find("div[id='_el_explode_expense_type']").html("");
                $(form).find("div[id='_el_explode_amount']").html("");
                $(form).find("div[id='_el_explode_remaining_receipt']").html("");
                
                if ((vm.temp.supplier_id == '' || vm.temp.supplier_id == undefined) && vm.requisition.multiple_supplier == 'MORE') {
                    vm.errors.supplier_detail = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกผู้สั่งจ่าย";
                    $(form).find("div[id='_el_explode_supplier_detail']").html(errorMsg);
                }
                if ((vm.temp.bank_account_number == '' || vm.temp.bank_account_number == undefined) && vm.requisition.multiple_supplier == 'MORE') {
                    vm.errors.supplier_bank = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกเลขที่บัญชีธนาคาร";
                    $(form).find("div[id='_el_explode_supplier_bank']").html(errorMsg);
                }
                if (vm.temp.budget_plan == '' || vm.temp.budget_plan == undefined) {
                    vm.errors.budget_plan = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกแผนงาน";
                    $(form).find("div[id='_el_explode_budget_plan']").html(errorMsg);
                }
                if (vm.temp.budget_type == '' || vm.temp.budget_type == undefined) {
                    vm.errors.budget_type = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกประเภทรายจ่าย";
                    $(form).find("div[id='_el_explode_budget_type']").html(errorMsg);
                }
                if (vm.temp.expense_type == '' || vm.temp.expense_type == undefined) {
                    vm.errors.expense_type = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกประเภทค่าใช้จ่าย";
                    $(form).find("div[id='_el_explode_expense_type']").html(errorMsg);
                }
                if (vm.temp.actual_amount == '' || vm.temp.actual_amount == 0 || vm.temp.actual_amount == undefined) {
                    vm.errors.amount = true;
                    valid = false;
                    errorMsg = "กรุณากรอกจำนวนเงิน";
                    $(form).find("div[id='_el_explode_amount']").html(errorMsg);
                }
                if (vm.temp.remaining_receipt_flag == 'Y' && (vm.temp.remaining_receipt_id == '' || vm.temp.remaining_receipt_id == undefined)) {
                    vm.errors.remaining_receipt = true;
                    valid = false;
                    errorMsg = "กรุณาระบุเลขที่ใบเสร็จรับเงินคงเหลือ";
                    $(form).find("div[id='_el_explode_remaining_receipt']").html(errorMsg);
                }
                if (!valid) {
                    return;
                }

                if(this.temp){
                    // this.line = { ...this.temp };
                    // this.line = JSON.parse(JSON.stringify(this.temp));
                    $('.modal-edit'+this.index).modal('hide');
                    this.$emit("updateRow", {index: this.index, line: this.temp});
                    // this.temp = null;
                }
            },
            cancel() {
                // this.temp = null;
                $('.modal-edit'+this.index).modal('hide');
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
            setSupplierLine(res){
                this.temp.supplier_id = res.supplier;
                this.temp.supplier_name = res.vendor_name;
            },
            setSupplierBank(res){
                this.temp.bank_account_number = res.supplier_bank;
                this.temp.supplier_site = res.supplier_site;
            },
            setVehicleOilType(res){
                this.temp.vehicle_oil_type = res.vehicle_oil_type;
            },
            setUtilityType(res){
                this.temp.utility_type = res.utility_type;
            },
            setUtilityDetail(res){
                this.temp.utility_detail = res.utility_detail;
            },
            setBudgetPlan(res){
                this.temp.budget_plan = res.budget_plan;
            },
            setBudgetType(res){
                this.temp.budget_type = res.budget_type;
            },
            setExpenseType(res){
                this.temp.expense_type = res.expense_type;
                this.temp.expense_description = res.expense_description;
                this.temp.description = res.expense_description;
                // GET EXPENSE ACCOUNT WHEN CHOOSE EXPENSE_TYPE
                if(this.temp.expense_type != this.line.expense_type){
                    this.getExpenseAccount();
                }
            },
            setRemainingReceipt(res){
                this.temp.remaining_receipt_id = res.remaining_receipt;
            },
            async getExpenseAccount(){
                var vm = this;
                if(vm.temp.expense_type != ''){
                    axios.post('/expense/api/requisition/get-expense-account', {
                        header: vm.requisition,
                        line: vm.temp,
                    })
                    .then(function (res) {
                        vm.loading = false;
                        if (res.data.message) {
                            vm.temp.expense_account = '';
                        } else {
                            vm.temp.expense_account = res.data.expense_account;
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
        }
    };
</script>

<style>
    .el-popper{
        z-index: 9999 !important;
    }
</style>