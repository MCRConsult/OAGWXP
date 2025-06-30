<template>
    <tr v-loading="loading">
        <td class="text-center"> {{ index + 1 }} </td>
        <td class="text-left">
            {{ line.expense_description }}
        </td>
        <td class="text-center">
            {{ numberFormat(line.actual_amount) }}
        </td>
        <td class="text-center">
            {{ line.supplier_name }}
        </td>
        <td class="text-center">
            {{ line.bank_account_number }}
        </td>
        <td style="padding-top: 5px">
            <div class="row text-center" style="border-collapse: collapse; width: 100px; display:inline-block; flex-direction: row;">
                <modalEditComp :key="index"
                    :index="index"
                    :requisition="requisition"
                    :reqLine="line"
                    :defaultSetName="defaultSetName"
                    @updateRow="updateRow"
                />
                <!-- <button type="button" @click.prevent="copy(index)" class="btn btn-sm btn-primary m-1" style="">
                    คัดลอก
                </button> -->
                <button v-if="line.split_flag == 'Y'" type="button" @click.prevent="remove(index)" class="btn btn-sm btn-danger m-1" style="">
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
        props: ['index', 'requisition', 'attribute', 'defaultSetName'],
        emits: ['updateRow', 'removeRow'],
        data() {
            return {
                line: this.attribute,
                loading: false,
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
            openModal(index){
                // this.copyDataForEdit();
                $('.modal-edit'+index).modal('show');
            },
            numberFormat(value) {
                if (!value) return "0.00";
                return numeral(value).format("0,0.00");
            },
            updateRow(res){
                this.$emit("updateRow", res);
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
                    allowOutsideClick: false // ป้องกันการปิด alert เมื่อคลิกนอกกรอบ
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$emit("removeRow", this.index);
                    }
                });
            },
        }
    };
</script>

<style>
    .el-popper{
        z-index: 9999 !important;
    }
</style>