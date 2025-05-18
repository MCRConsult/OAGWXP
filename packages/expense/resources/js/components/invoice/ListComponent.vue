<template>
    <tr v-loading="loading">
        <td class="text-center"> {{ index + 1 }} </td>
        <td class="text-left">
            {{ line.expense.description }}
        </td>
        <td class="text-left small wrap-text">
            {{ line.expense_account }}
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
                <modalEditComp
                    :index="index"
                    :invoiceLine="line"
                    :defaultSetName="defaultSetName"
                />
                    <!-- @updateRow="updateRow" -->
                <button type="button" @click.prevent="copy(index)" class="btn btn-sm btn-primary m-1" style="">
                    คัดลอก
                </button>
                <button type="button" @click.prevent="remove(index)" class="btn btn-sm btn-danger m-1" style="">
                    ลบรายการ
                </button>
            </div>
        </td>
    </tr>
</template>
<script>
    import numeral from "numeral";
    import Swal from 'sweetalert2';
    import modalEditComp from "./_ModalEditComponent.vue";

    export default {
        components: {
            modalEditComp
        },
        props: ['index', 'attribute', 'defaultSetName'],
        emits: ['updateRow', 'copyRow', 'removeRow'],
        data() {
            return {
                loading: false,
                line: this.attribute,
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










            
            // openModal(index){
            //     this.temp = { ...this.line };
            //     $('.modal-edit'+index).modal('show');
            // },
            // confirm() {
            //     if (this.temp) {
            //         this.line = {...this.temp};
            //         this.temp = null;
            //         console.log(this.line);
            //         $('.modal-edit'+this.index).modal('hide');
            //         this.$emit("updateRow", {index: this.index, line: this.line});
            //     }
            // },
            // cancel() {
            //     this.temp = null;
            //     $('.modal-edit'+this.index).modal('hide');
            // },
            // setSupplierLine(res){
            //     this.line.supplier = res.supplier;
            //     this.line.supplier_name = res.vendor_name;
            // },
            // setSupplierBank(res){
            //     this.line.supplier_bank = res.supplier_bank;
            // },
            // setBudgetPlan(res){
            //     this.line.budget_plan = res.budget_plan;
            // },
            // setBudgetType(res){
            //     this.line.budget_type = res.budget_type;
            // },
            // setExpenseType(res){
            //     this.line.expense_type = res.expense_type;
            //     this.line.expense_description = res.expense_description;
            // },
        }
    };
</script>

<style>
    .el-popper{
        z-index: 9999 !important;
    }
    .wrap-text {
      overflow-wrap: break-word; /* Modern equivalent of word-wrap */
      word-wrap: break-word; /* For older browsers */
      word-break: break-word; /* Optional for certain cases */
      white-space: normal; /* Ensures text wraps */
    }
</style>