<template>
    <tr v-loading="loading">
        <td class="text-center" style="vertical-align: middle;"> {{ index + 1 }} </td>
        <td class="text-left" style="vertical-align: middle;">
            {{ line.expense_description }}
        </td>
        <td class="text-right bg-primary" style="vertical-align: middle;">
            {{ numberFormat(line.amount) }}
        </td>
        <td class="text-right bg-warning" style="vertical-align: middle;">
            <vue-numeric style="width: 100%;"
                name="actual_amount"
                class="form-control text-right"
                v-model="line.actual_amount"
                v-bind:minus="false"
                v-bind:precision="2"
                :min="0"
                :max="999999999"
                autocomplete="off"
                @change="checkDiffAmount"
            ></vue-numeric>
            <div :id="'el_explode_amount_'+index" class="text-danger text-center pt-2" style="font-weight: bold;"></div>
        </td>
        <td class="text-right bg-danger" style="vertical-align: middle;">
            {{ numberFormat(line.amount - line.actual_amount) }}
        </td>
        <td class="text-center" style="vertical-align: middle;">
            {{ line.supplier_name }}
        </td>
        <td class="text-center" style="vertical-align: middle;">
            {{ line.bank_account_number }}
        </td>
    </tr>
</template>
<script>
    import numeral from "numeral";
    import Swal from 'sweetalert2';
    import {ElNotification} from 'element-plus';

    export default {
        components: {
            //
        },
        props: ['index', 'attribute'],
        emits: ['updatevalid'],
        data() {
            return {
                line: this.attribute,
                loading: false,
            };
        },
        mounted() {
        },
        watch:{
            //
        },    
        methods: {
            numberFormat(value) {
                if (!value) return "0.00";
                return numeral(value).format("0,0.00");
            },
            checkDiffAmount(){
                let errorMsgNoti = '';
                let errorMsg = '';
                let valid = true;
                $("div[id='el_explode_amount_" + this.index + "']").html(errorMsg);
                if(this.line.actual_amount > this.line.amount){
                    valid = false;
                    let errorMsgNoti = 'รายการที่ '+(this.index+1)+' ยอดเงินที่เบิกจริงไม่สามารถเกินจำนวนที่ขอเบิกได้';
                    let errorMsg = 'ยอดเงินที่เบิกจริงไม่สามารถเกินจำนวนที่ขอเบิกได้';
                    $("div[id='el_explode_amount_" + this.index + "']").html(errorMsg);
                    ElNotification({
                        title: 'ข้อผิดผลาด',
                        message: errorMsgNoti,
                        type: 'error',
                    });
                }
                this.$emit("updatevalid", {index: this.index, value: valid});
            }
        }
    };
</script>

<style>
    .el-popper{
        z-index: 9999 !important;
    }
</style>