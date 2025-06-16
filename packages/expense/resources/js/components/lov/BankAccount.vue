<template>
    <div class="el_select">
        <el-select v-model="value"
                name="bank_account"
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
                :key="row.bank_account_id"
                :label="row.bank_account_name+' : '+row.bank_account_num"
                :value="row.bank_account_id"
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
        this.loading = true;
        this.value = this.setData;
        this.getDataRows(this.value);
    },
    watch: {
        setData() {
            this.value = '';
            this.value = this.setData;
            this.getDataRows(this.value);
        },
        parent() {
            this.value = '';
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
            axios.get(`/expense/api/get-bank-account`, {
                params: {
                    parent: this.parent,
                    keyword: query
                }
            })
            .then(res => {
                this.loading = false;
                this.dataRows = res.data.data;
                this.$emit('setBankAccount', {cash_bank_account_id: this.value});
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
