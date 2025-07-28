<template>
    <div class="el_select">
        <el-select v-model="value"
                name="receipt"
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
                :key="row.tax_rate_id"
                :label="row.tax_rate_code"
                :value="row.tax_rate_code"
            >
            </el-option>
        </el-select>
    </div>
</template>

<script>
export default {
    props: [
       'setData', 'error', 'editFlag'
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
            axios.get(`/OAGWXP/api/get-taxes`, {
                params: {
                    keyword: query
                }
            })
            .then(res => {
                this.loading = false;
                this.dataRows = res.data.data;
                let tax_percent = 0;
                res.data.data.filter((value) => {
                    if(value.tax == this.value){
                        tax_percent = value.percentage_rate;
                    }
                });
                this.$emit('setTax', {tax_code: this.value, tax_percent: tax_percent});
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
