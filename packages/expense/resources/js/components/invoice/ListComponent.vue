<template>
    <tr v-loading="loading">
        <td class="text-center"> {{ index + 1 }} </td>
        <td class="text-center">
            {{ line.expense_type }}
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
                <button type="button" class="btn btn-sm btn-warning m-1" data-toggle="collapse" @click.prevent="openModal()">
                    แก้ไข
                </button>
                <button type="button" @click.prevent="copy(index)" class="btn btn-sm btn-primary m-1" style="">
                    คัดลอก
                </button>
                <button type="button" @click.prevent="remove(index)" class="btn btn-sm btn-danger m-1" style="">
                    ลบรายการ
                </button>
            </div>
            <editComp
                :requisition="requisition"
                :reqLine="line"
            />
        </td>
    </tr>
</template>
<script>
    import numeral from "numeral";
    import Swal from 'sweetalert2';
    import editComp from "./EditComponent.vue";

    export default {
        components: {
            editComp
        },
        props: ['index', 'attribute', 'requisition'],
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
                    allowOutsideClick: false // ป้องกันการปิด alert เมื่อคลิกนอกกรอบ
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$emit("removeRow", this.index);
                    }
                });
            },
            openModal(){
                $('#modal-edit').modal('show');
            },
            confirm(){
                $('#modal-edit').modal('hide');
            },
        }
    };
</script>

<style>
    .el-popper{
        z-index: 9999 !important;
    }
</style>