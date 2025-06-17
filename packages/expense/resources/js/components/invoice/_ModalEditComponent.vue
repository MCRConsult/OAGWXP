<template>
    <button type="button" class="btn btn-sm btn-warning m-1" data-toggle="collapse" @click.prevent="openModal(index)">
        แก้ไข
    </button>
    <div :class="'modal fade modal-edit'+index" aria-labelledby="myModalLabel" tabindex="-1" role="dialog" 
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> แก้ไขรายการ </h4>
                </div>
                <div class="modal-body m-2">
                    <form :class="'edit-form'+index">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group text-left" style="padding: 5px;">
                                    <label class="control-label" style="margin-bottom: 0.4rem;">
                                        <strong> ชื่อสั่งจ่าย </strong> &nbsp;
                                    </label><br>
                                    <el-input v-model="temp.supplier_name" style="width: 100%;" disabled/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group text-left" style="padding: 5px;">
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
                                    <div id="_el_explode_supplier_bank" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group text-left" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> แผนงาน </strong>
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
                            <div class="col-md-3">
                                <div class="form-group text-left" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> ประเภทรายจ่าย </strong>
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
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group text-left" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> ประเภทค่าใช้จ่าย </strong>
                                    </label><br>
                                    <expenseType
                                        :parent="temp.budget_type"
                                        :setData="temp.expense_type"
                                        :error="errors.expense_type"
                                        :editFlag="false"
                                        @setExpenseType="setExpenseType"
                                    ></expenseType>
                                    <div id="_el_explode_expense_type" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group text-left" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> รายการบัญชี </strong>
                                    </label><br>
                                    <el-input name="expense_account" placeholder="" v-model="line.expense_account" @click="accounrCollp == !accounrCollp"
                                        size="default" style="width: 100%" readonly data-toggle="collapse" href="#expense_account_collp"
                                    > </el-input>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group text-left" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> จำนวนเงิน <span class="text-danger"> * </span></strong>
                                    </label><br>
                                    <vue-numeric style="width: 100%;"
                                        name="amount"
                                        v-bind:minus="false"
                                        v-bind:precision="2"
                                        :min="-999999999"
                                        :max="999999999"
                                        class="form-control text-right"
                                        v-model="temp.amount"
                                        ref="amount"
                                        autocomplete="off"
                                    ></vue-numeric>
                                    <div id="_el_explode_amount" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group text-left" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> เลขที่ใบเสร็จรับเงิน </strong>
                                    </label><br>
                                    <arReceipt
                                        :setData="temp.ar_receipt_id"
                                        :editFlag="true"
                                        @setArReceipt="setArReceipt"
                                    ></arReceipt>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group text-left" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> ภาษีมูลค่าเพิ่ม </strong>
                                    </label><br>
                                    <tax
                                        :setData="temp.tax_code"
                                        :editFlag="true"
                                        @setTax="setTax"
                                    ></tax>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group text-left" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> จำนวนเงินภาษีมูลค่าเพิ่ม </strong>
                                    </label><br>
                                    <vue-numeric style="width: 100%;"
                                        name="tax_amount"
                                        v-bind:minus="false"
                                        v-bind:precision="2"
                                        :min="-999999999"
                                        :max="999999999"
                                        class="form-control text-right"
                                        v-model="temp.tax_amount"
                                        ref="amount"
                                        autocomplete="off"
                                    ></vue-numeric>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group text-left" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> ภาษีหัก ณ ที่จ่าย </strong>
                                    </label><br>
                                    <wht
                                        :setData="temp.wht_code"
                                        :editFlag="true"
                                        @setWht="setWht"
                                    ></wht>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group text-left" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> จำนวนเงินภาษีหัก ณ ที่จ่าย </strong>
                                    </label><br>
                                    <vue-numeric style="width: 100%;"
                                        name="wht_amount"
                                        v-bind:minus="false"
                                        v-bind:precision="2"
                                        :min="-999999999"
                                        :max="999999999"
                                        class="form-control text-right"
                                        v-model="temp.wht_amount"
                                        ref="amount"
                                        autocomplete="off"
                                    ></vue-numeric>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group text-left" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> เลขที่ใบเสร็จรับเงินคงเหลือ </strong>
                                    </label><br>
                                    <el-input v-model="temp.remaining_receipt_number" style="width: 100%;" disabled/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group text-left" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> คำอธิบายรายการ </strong>
                                    </label><br>
                                    <el-input v-model="temp.description" type="textarea" :rows="2" style="width: 100%;" placeholder="" maxlength="240" show-word-limit/>
                                </div>
                            </div>
                        </div>
                        <!-- EXPENSE ACCOUNT -->
                        <div :class="accounrCollp? 'collapse show ': 'collapse'" id="expense_account_collp">
                            <div class="col-12">
                                <div class="card row text-left" style="background-color: #ddd; border: 1px solid #ddd;">
                                    <div class="card-header" style="background-color: #ddd; padding: 5px;">
                                        <strong> รายการบัญชี </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;">
                                        <strong> หน่วยเบิกจ่าย </strong>
                                    </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment1"
                                        :set-data="segment1"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment1"
                                        placeholder=""
                                        ref="segment1"
                                    >
                                    </coaComponent>
                                    <div id="_el_explode_acc_1" class="text-danger text-left"></div>
                                </div>
                               <div class="col-sm-3 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;">
                                        <strong> ศูนย์ต้นทุน </strong>
                                    </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment2"
                                        :set-data="segment2"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment2"
                                        placeholder=""
                                        ref="segment2"
                                    >
                                    </coaComponent>
                                    <div id="_el_explode_acc_2" class="text-danger text-left"></div>
                                </div>
                                <div class="col-sm-3 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;">
                                        <strong> ปีงบประมาณ </strong>
                                    </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment3"
                                        :set-data="segment3"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment3"
                                        placeholder=""
                                        ref="segment3"
                                    >
                                    </coaComponent>
                                    <div id="_el_explode_acc_3" class="text-danger text-left"></div>
                                </div>
                                <div class="col-sm-3 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;">
                                        <strong> แหล่งเงิน </strong>
                                    </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment4"
                                        :set-data="segment4"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment4"
                                        placeholder=""
                                        ref="segment4"
                                    >
                                    </coaComponent>
                                    <div id="_el_explode_acc_4" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;">
                                        <strong> แผนงาน </strong>
                                    </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment5"
                                        :set-data="segment5"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment5"
                                        placeholder=""
                                        ref="segment5"
                                    >
                                    </coaComponent>
                                    <div id="_el_explode_acc_5" class="text-danger text-left"></div>
                                </div>
                                <div class="col-sm-3 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;">
                                        <strong> ผลผลิต/แผนงานรอง/โครงการ </strong> 
                                    </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment6"
                                        :set-data="segment6"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment6"
                                        placeholder=""
                                        ref="segment6"
                                    >
                                    </coaComponent>
                                    <div id="_el_explode_acc_6" class="text-danger text-left"></div>
                                </div>
                                <div class="col-sm-3 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;">
                                        <strong> กิจกรรม </strong>
                                    </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment7"
                                        :set-data="segment7"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment7"
                                        placeholder=""
                                        ref="segment7"
                                    >
                                    </coaComponent>
                                    <div id="_el_explode_acc_7" class="text-danger text-left"></div>
                                </div>
                                <div class="col-sm-3 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;">
                                        <strong> ประเภทรายจ่าย </strong>
                                    </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment8"
                                        :set-data="segment8"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment8"
                                        placeholder=""
                                        ref="segment8"
                                    >
                                    </coaComponent>
                                    <div id="_el_explode_acc_8" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;">
                                        <strong> รหัสงบประมาณ </strong>
                                    </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment9"
                                        :set-data="segment9"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment9"
                                        placeholder=""
                                        ref="segment9"
                                    >
                                    </coaComponent>
                                    <div id="_el_explode_acc_9" class="text-danger text-left"></div>
                                </div>
                                <div class="col-sm-3 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;">
                                        <strong> รหัสบัญชีหลัก </strong>
                                    </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment10"
                                        :set-data="segment10"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment10"
                                        placeholder=""
                                        ref="segment10"
                                    >
                                    </coaComponent>
                                    <div id="_el_explode_acc_10" class="text-danger text-left"></div>
                                </div>
                                <div class="col-sm-3 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;">
                                        <strong> รหัสบัญชีย่อย/รายจ่ายย่อย </strong>
                                    </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment11"
                                        :parent="segment10"
                                        :set-data="segment11"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment11"
                                        placeholder=""
                                        ref="segment11"
                                    >
                                    </coaComponent>
                                    <div id="_el_explode_acc_11" class="text-danger text-left"></div>
                                </div>
                                <div class="col-sm-3 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;">
                                        <strong> สำรอง 1 </strong>
                                    </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment12"
                                        :set-data="segment12"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment12"
                                        placeholder=""
                                        ref="segment12"
                                    >
                                    </coaComponent>
                                    <div id="_el_explode_acc_12" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;">
                                        <strong> สำรอง 2 </strong>
                                    </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment13"
                                        :set-data="segment13"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment13"
                                        placeholder=""
                                        ref="segment13"
                                    >
                                    </coaComponent>
                                    <div id="_el_explode_acc_13" class="text-danger text-left"></div>
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
    import coaComponent from './InputCOAComponent.vue';

    import supplier         from "../lov/Supplier.vue";
    import supplierBank     from "../lov/SupplierBank.vue";
    import budgetPlan       from "../lov/BudgetPlan.vue";
    import budgetType       from "../lov/BudgetType.vue";
    import expenseType      from "../lov/ExpenseType.vue";
    import arReceipt        from "../lov/ARReceipt.vue";
    import tax              from "../lov/Tax.vue";
    import wht              from "../lov/Wht.vue";

    export default {
        components: {
            coaComponent, supplier, supplierBank, budgetPlan, budgetType, expenseType, arReceipt, tax, wht
        },
        props: ['index', 'invoiceLine', 'defaultSetName'],
        emits: ['updateRow'],
        data() {
            return {
                line: this.invoiceLine,
                temp: {},
                loading: false,
                accounrCollp: false,
                errors: {
                    supplier_bank: false,
                    budget_plan: false,
                    budget_type: false,
                    expense_type: false,
                    amount: false,
                    segment2: false,
                    segment3: false,
                    segment6: false,
                    segment7: false,
                    segment9: false,
                    segment10: false,
                    segment11: false,
                },
                // Segment
                segment1: '', segment2: '', segment3: '', segment4: '', segment5: '', segment6: '',
                segment7: '', segment8: '', segment9: '', segment10: '', segment11: '', segment12: '', segment13: '',
            };
        },
        mounted() {
            this.extractAccount();
        },
        watch: {
            errors: {
                handler(val){
                    val.amount? this.setError('amount') : this.resetError('amount');
                },
                deep: true,
            },
        },
        methods: {
            openModal(index){
                // this.temp = { ...this.line };
                this.temp = JSON.parse(JSON.stringify(this.line));
                $('.modal-edit'+index).modal('show');
            },
            confirm(index) {
                let vm = this;
                let errorMsg = '';
                let valid = true;
                var form = $('.edit-form'+index);
                vm.errors.supplier_bank = false;
                vm.errors.budget_plan = false;
                vm.errors.budget_type = false;
                vm.errors.expense_type = false;
                vm.errors.amount = false;
                vm.errors.segment2 = false;
                vm.errors.segment3 = false;
                vm.errors.segment6 = false;
                vm.errors.segment7 = false;
                vm.errors.segment9 = false;
                vm.errors.segment10 = false;
                vm.errors.segment11 = false;
                $(form).find("div[id='_el_explode_supplier_bank']").html("");
                $(form).find("div[id='_el_explode_budget_plan']").html("");
                $(form).find("div[id='_el_explode_budget_type']").html("");
                $(form).find("div[id='_el_explode_expense_type']").html("");
                $(form).find("div[id='_el_explode_amount']").html("");
                $(form).find("div[id='_el_explode_remaining_receipt']").html("");
                $(form).find("div[id='_el_explode_acc_2']").html("");
                $(form).find("div[id='_el_explode_acc_3']").html("");
                $(form).find("div[id='_el_explode_acc_6']").html("");
                $(form).find("div[id='_el_explode_acc_7']").html("");
                $(form).find("div[id='_el_explode_acc_9']").html("");
                $(form).find("div[id='_el_explode_acc_10']").html("");
                $(form).find("div[id='_el_explode_acc_11']").html("");

                if (vm.temp.bank_account_number == '' || vm.temp.bank_account_number == undefined) {
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
                if (vm.temp.amount == '' || vm.temp.amount == undefined) {
                    vm.errors.amount = true;
                    valid = false;
                    errorMsg = "กรุณากรอกจำนวนเงิน";
                    $(form).find("div[id='_el_explode_amount']").html(errorMsg);
                }
                if (vm.segment2 == '' || vm.segment2 == undefined) {
                    vm.errors.segment2 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุศูนย์ต้นทุน";
                    $(form).find("div[id='_el_explode_acc_2']").html(errorMsg);
                }
                if (vm.segment3 == '' || vm.segment3 == undefined) {
                    vm.errors.segment3 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุปีงบประมาณ";
                    $(form).find("div[id='_el_explode_acc_3']").html(errorMsg);
                }
                if (vm.segment6 == '' || vm.segment6 == undefined) {
                    vm.errors.segment6 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุผลผลิต/แผนงานรอง/โครงการ";
                    $(form).find("div[id='_el_explode_acc_6']").html(errorMsg);
                }
                if (vm.segment7 == '' || vm.segment7 == undefined) {
                    vm.errors.segment7 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุกิจกรรม";
                    $(form).find("div[id='_el_explode_acc_7']").html(errorMsg);
                }
                if (vm.segment9 == '' || vm.segment9 == undefined) {
                    vm.errors.segment9 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุรหัสงบประมาณ";
                    $(form).find("div[id='_el_explode_acc_9']").html(errorMsg);
                }
                if (vm.segment10 == '' || vm.segment10 == undefined) {
                    vm.errors.segment10 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุรหัสบัญชีหลัก";
                    $(form).find("div[id='_el_explode_acc_10']").html(errorMsg);
                }
                if (vm.segment11 == '' || vm.segment11 == undefined) {
                    vm.errors.segment11 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุรหัสบัญชีย่อย/รายจ่ายย่อย";
                    $(form).find("div[id='_el_explode_acc_11']").html(errorMsg);
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
                $('.modal-edit'+this.index).modal('hide');
                // this.temp = null;
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
            setBudgetPlan(res){
                this.temp.budget_plan = res.budget_plan;
            },
            setBudgetType(res){
                this.temp.budget_type = res.budget_type;
            },
            setExpenseType(res){
                this.temp.expense_type = res.expense_type;
                this.temp.expense_description = res.expense_description;
            },
            setArReceipt(res){
                this.temp.ar_receipt_id = res.receipt;
                this.temp.ar_receipt_number = res.receipt_number;
            },
            setTax(res){
                this.temp.tax_code = res.tax_code;
            },
            setWht(res){
                this.temp.wht_code = res.wht_code;
            },
            updateCoa(res){
                if (res.name == this.defaultSetName.segment1) { 
                    this.segment1 = res.segment1 == undefined? '': res.segment1;
                }
                if (res.name == this.defaultSetName.segment2) {
                    this.segment2 = res.segment2 == undefined? '': res.segment2; 
                }
                if (res.name == this.defaultSetName.segment3) {
                    this.segment3 = res.segment3 == undefined? '': res.segment3;
                }
                if (res.name == this.defaultSetName.segment4) {
                    this.segment4 = res.segment4 == undefined? '': res.segment4;
                }
                if (res.name == this.defaultSetName.segment5) {
                    this.segment5 = res.segment5 == undefined? '': res.segment5;
                }
                if (res.name == this.defaultSetName.segment6) {
                    this.segment6 = res.segment6 == undefined? '': res.segment6; 
                }
                if (res.name == this.defaultSetName.segment7) {
                    this.segment7 = res.segment7 == undefined? '': res.segment7;
                }
                if (res.name == this.defaultSetName.segment8) {
                    this.segment8 = res.segment8 == undefined? '': res.segment8;
                }
                if (res.name == this.defaultSetName.segment9) {
                    this.segment9 = res.segment9 == undefined? '': res.segment9;
                }
                if (res.name == this.defaultSetName.segment10) {
                    this.segment10 = res.segment10 == undefined? '': res.segment10;
                    this.segment11 = '';
                }
                if (res.name == this.defaultSetName.segment11) {
                    this.segment11 = res.segment11 == undefined? '': res.segment11;
                }
                if (res.name == this.defaultSetName.segment12) {
                    this.segment12 = res.segment12 == undefined? '': res.segment12;
                }
                if (res.name == this.defaultSetName.segment13) {
                    this.segment13 = res.segment13 == undefined? '': res.segment13;
                }

                let expenseAcc = this.segment1+'.'+this.segment2+'.'+this.segment3+'.'+this.segment4+'.'+this.segment5+'.'+this.segment6+'.'+this.segment7+'.'+this.segment8+'.'+this.segment9+'.'+this.segment10+'.'+this.segment11+'.'+this.segment12+'.'+this.segment13;
                this.temp.expense_account = expenseAcc;
            },
            extractAccount(){
                var coa = this.line.expense_account.split('.');
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
            }
        }
    };
</script>

<style>
    .el-popper{
        z-index: 9999 !important;
    }
</style>