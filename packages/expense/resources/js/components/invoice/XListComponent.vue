<template>
    <tr v-loading="loading">
        <td class="text-center"> {{ index + 1 }} </td>
        <td class="text-left">
            {{ line.expense.description }}
        </td>
        <td class="text-center">
            {{ line.account_segment }}
        </td>
        <td class="text-center">
            {{ numberFormat(line.amount) }}
        </td>
        <td class="text-center">
            {{ line.supplier_name }}
        </td>
        <td class="text-center">
            {{ line.supplier_bank }}
        </td>
        <td style="padding-top: 5px">
            <div class="row text-center" style="border-collapse: collapse; width: 250px; display:inline-block; flex-direction: row;">
                <button type="button" class="btn btn-sm btn-warning m-1" data-toggle="collapse" @click.prevent="openModal(index)">
                    แก้ไข
                </button>
                <button type="button" @click.prevent="copy(index)" class="btn btn-sm btn-primary m-1" style="">
                    คัดลอก
                </button>
                <button type="button" @click.prevent="remove(index)" class="btn btn-sm btn-danger m-1" style="">
                    ลบรายการ
                </button>
            </div>
        </td>
    </tr>
    <div :class="'modal fade modal-edit'+index" aria-labelledby="myModalLabel" tabindex="-1" role="dialog" 
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> แก้ไขรายละเอียด </h4>
                </div>
                <div class="modal-body m-2">
                    <form id='edit-form'>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label" style="margin-bottom: 0.4rem;">
                                        <strong> ชื่อสั่งจ่าย <span class="text-danger"> * </span></strong> &nbsp;
                                    </label><br>
                                    <!-- <supplier
                                        :setData="temp.supplier"
                                        :error="errors.supplier_detail"
                                        @setSupplier="setSupplierLine"
                                    ></supplier> -->
                                    <div id="el_explode_supplier_detail" class="text-danger text-left"></div>
                                        <!-- :editFlag="requisition.multiple_supplier == 'MORE'? true: false" -->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> เลขที่บัญชีธนาคาร <span class="text-danger"> *</span></strong>
                                    </label><br>
                                    <!-- <supplierBank
                                        :parent="temp.supplier"
                                        :setData="temp.supplier_bank"
                                        :error="errors.supplier_bank"
                                        @setSupplierBank="setSupplierBank"
                                    ></supplierBank> -->
                                    <div id="el_explode_supplier_bank" class="text-danger text-left"></div>
                                        <!-- :editFlag="requisition.multiple_supplier == 'MORE'? true: false" -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> แผนงาน <span class="text-danger"> * </span></strong>
                                    </label><br>
                                    <!-- <budgetPlan 
                                        :setData="temp.budget_plan"
                                        :error="errors.budget_plan"
                                        :editFlag="true"
                                        @setBudgetPlan="setBudgetPlan"
                                    ></budgetPlan> -->
                                    <div id="el_explode_budget_plan" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> ประเภทรายจ่าย <span class="text-danger"> * </span></strong>
                                    </label><br>
                                    <!-- <budgetType
                                        :parent="temp.budget_plan"
                                        :setData="temp.budget_type"
                                        :error="errors.budget_type"
                                        :editFlag="true"
                                        @setBudgetType="setBudgetType"
                                    ></budgetType> -->
                                    <div id="el_explode_budget_type" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> ประเภทค่าใช้จ่าย <span class="text-danger"> * </span></strong>
                                    </label><br>
                                   <!--  <expenseType
                                        :parent="temp.budget_type"
                                        :setData="temp.expense_type"
                                        :error="errors.expense_type"
                                        :editFlag="true"
                                        @setExpenseType="setExpenseType"
                                    ></expenseType> -->
                                    <div id="el_explode_expense_type" class="text-danger text-left"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> จำนวนเงิน <span class="text-danger"> * </span></strong>
                                    </label><br>
                                    <vue-numeric style="width: 100%;"
                                        name="amount"
                                        v-bind:minus="false"
                                        v-bind:precision="2"
                                        :min="0"
                                        :max="999999999"
                                        class="form-control text-right"
                                        v-model="temp.amount"
                                        ref="amount"
                                        autocomplete="off"
                                    ></vue-numeric>
                                    <div id="el_explode_amount" class="text-danger text-left"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" style="padding: 5px;">
                                    <label class="control-label">
                                        <strong> คำอธิบายรายการ </strong>
                                    </label><br>
                                    <el-input v-model="temp.description" type="textarea" :rows="2" style="width: 100%;" placeholder="" maxlength="240" show-word-limit/>
                                </div>
                            </div>
                        </div>                      
                    </form>
                </div>
                <div class="modal-footer pt-2">
                    <button type="button" class="btn btn-primary btn-sm" @click.private="confirm"
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
    import numeral from "numeral";
    import Swal from 'sweetalert2';
    // import editComp from "./EditComponent.vue";

    import supplier         from "../lov/Supplier.vue";
    import supplierBank     from "../lov/SupplierBank.vue";
    import budgetPlan       from "../lov/BudgetPlan.vue";
    import budgetType       from "../lov/BudgetType.vue";
    import expenseType      from "../lov/ExpenseType.vue";

    export default {
        components: {
            supplier, supplierBank, budgetPlan, budgetType, expenseType
        },
        props: ['index', 'attribute'],
        emits: ['updateRow', 'copyRow', 'removeRow'],
        data() {
            return {
                loading: false,
                line: this.attribute,
                temp: {},
                errors: {
                    supplier_detail: false,
                    supplier_bank: false,
                    expense_type: false,
                    amount: false,
                },
            };
        },
        mounted() {
        },
        watch:{
            attribute() {
                return this.line = this.attribute;
            },
        },    
        methods: {
            numberFormat(value) {
                if (!value) return "0.00";
                return numeral(value).format("0,0.00");
            },
            copy(){
                this.$emit("copyRow", this.index);
            },
            remove(){
                Swal.fire({
                    title: "ยืนยันลบรายการ",
                    html: "ต้องการ <b>ยืนยัน</b> ลบรายการใช่หรือไม่?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "ใช่",
                    cancelButtonText: "ไม่",
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$emit("removeRow", this.index);
                    }
                });
            },
            openModal(index){
                this.temp = { ...this.line };
                $('.modal-edit'+index).modal('show');
            },
            confirm() {
                if (this.temp) {
                    this.line = {...this.temp};
                    this.temp = null;
                    console.log(this.line);
                    $('.modal-edit'+this.index).modal('hide');
                    this.$emit("updateRow", {index: this.index, line: this.line});
                }
            },
            cancel() {
                this.temp = null;
                $('.modal-edit'+this.index).modal('hide');
            },
            setSupplierLine(res){
                this.line.supplier = res.supplier;
                this.line.supplier_name = res.vendor_name;
            },
            setSupplierBank(res){
                this.line.supplier_bank = res.supplier_bank;
            },
            setBudgetPlan(res){
                this.line.budget_plan = res.budget_plan;
            },
            setBudgetType(res){
                this.line.budget_type = res.budget_type;
            },
            setExpenseType(res){
                this.line.expense_type = res.expense_type;
                this.line.expense_description = res.expense_description;
            },
        }
    };
</script>

<style>
    .el-popper{
        z-index: 9999 !important;
    }
</style>