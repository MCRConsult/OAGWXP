<template>
    <div class="el_select">
        <el-select v-model="value"
                name="receipt_account"
                placeholder=""
                :remote-method="getDataRows"
                :loading="loading"
                remote-show-suffix
                style="width: 100%"
                ref="input"
                @change="getDataRows"
            >
            <el-option
                v-for="(row, index) in dataRows"
                :key="row.code_combination_id+'_'+row.cash_receipt_id"
                :label="row.account_code"
                :value="row.account_code"
            >
            </el-option>
        </el-select>
    </div>
</template>

<script>
export default {
    props: [
       'parent', 'setData', 'error', 'editFlag'
    ],
    data () {
        return {
            dataRows: [],
            loading: false,
            value: '',
        }
    },
    mounted() {
        // this.loading = true;
        // this.value = this.setData;
        // this.getDataRows(this.value);
    },
    watch: {
        setData() {
            this.value = this.setData;
            this.getDataRows(this.value);
        },
        parent() {
            this.value = '';
            this.dataRows = [];
            if(this.parent){
                this.getDataRows(this.value);
            }
        },
        error() {
            let ref = this.$refs['input'].$refs.wrapperRef;
            ref.style = "";
            if(this.error && (this.value === '' || this.value === null)){
                ref.style = "border: 1px solid red;";
            }
        },
    },
    methods: {
        getDataRows (query) {
            this.loading = true;
            axios.get(`/expense/api/get-receipt-account`, {
                params: {
                    parent: this.parent,
                    keyword: query
                }
            })
            .then(res => {
                this.loading = false;
                this.dataRows = res.data.data;
                let receipt_amount = '';
                res.data.data.filter((value) => {
                    if(value.account_code == this.value){
                        receipt_amount = value.amount;
                    }
                });
                this.$emit('setReceiptAccount', {receipt_account: this.value, receipt_amount: receipt_amount});
            })
            .catch((error) => {
                console.log('มีข้อผิดพลาด', error, 'error');
            })
        },
    },
}
</script>
<style type="text/css" scope>
    .el-select-dropdown__item{
        font-size: 12px;
    }
</style>
