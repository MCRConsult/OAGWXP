<template>
    <div class="el_select">
        <el-select v-model="value"
                name="ar_540_receipt"
                placeholder=""
                :remote-method="getDataRows"
                :loading="loading"
                remote
                clearable
                filterable
                remote-show-suffix
                style="width: 100%"
                ref="input"
                :disabled="!editFlag"
                @change="getDataRows"
            >
            <el-option
                v-for="(row, index) in dataRows"
                :key="row.cash_receipt_id"
                :label="row.receipt_number+': '+row.activity_name"
                :value="row.cash_receipt_id"
            >
            </el-option>
        </el-select>
    </div>
</template>

<script>
export default {
    props: [
       'setData', 'refContract', 'error', 'editFlag'
    ],
    data () {
        return {
            dataRows: [],
            loading: false,
            value: '',
        }
    },
    mounted() {
        this.loading = true;
        this.value = this.setData;
        this.getDataRows(this.value);
    },
    watch: {
        setData() {
            this.value = this.setData;
            this.getDataRows(this.value);
        },
        // refContract() {
        //     console.log(this.refContract);
        //     if (this.value == '' || this.value == undefined){
        //         console.log('1----'+this.refContract);
        //         this.value = this.setData;
        //         this.getDataRows(this.value);
        //     }else{
        //         console.log('2----'+this.refContract);
        //         this.getDataRows(this.value);
        //     }
        // },
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
            axios.get(`/OAGWXP/api/get-guarantee-receipt`, {
                params: {
                    keyword: query,
                    refContract: this.refContract,
                }
            })
            .then(res => {
                this.loading = false;
                this.dataRows = res.data.data;
                let receipt_amount = 0;
                let contract_number = '';
                // if (this.refContract != ''){
                //     this.value = res.data.data[0]?.cash_receipt_id;
                // }
                res.data.data.filter((value) => {
                    if(value.cash_receipt_id == this.value){
                        receipt_amount = value.receipt_amount;
                        contract_number = value.doc_no;
                    }
                });
                this.$emit('setGuaranteeReceipt', {guarantee_receipt: this.value, receipt_amount: receipt_amount, contract_number: contract_number});
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
