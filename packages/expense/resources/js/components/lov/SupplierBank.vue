<template>
    <div class="el_select">
        <el-select v-model="value"
                name="supplier_bank"
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
                :key="row.bank_account_num"
                :label="row.bank_account_num"
                :value="row.bank_account_num"
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
        // this.getDataRows(this.value);
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
            axios.get(`/expense/api/get-supplier-bank`, {
                params: {
                    parent: this.parent,
                    keyword: query
                }
            })
            .then(res => {
                this.loading = false;
                this.dataRows = res.data.data;
                let supplier_site = '';
                if(this.parent){
                    res.data.data.filter((value) => {
                        if(value.bank_account_num == this.value){
                            supplier_site = value.vendor_site_id;
                        }
                    });
                    this.value = res.data.data[0].bank_account_num;
                }
                this.$emit('setSupplierBank', {supplier_bank: this.value, supplier_site: supplier_site});
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
