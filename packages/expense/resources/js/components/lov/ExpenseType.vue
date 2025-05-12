<template>
    <div class="el_select">
        <el-select v-model="value"
                name="expense_type"
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
                :key="row.category_concat_segs"
                :label="row.description"
                :value="row.category_concat_segs"
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
        parent() {
            this.value = '';
            if(this.parent){
                this.getDataRows(this.value);
            }
        },
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
            axios.get(`/expense/api/get-expense-type`, {
                params: {
                    parent: this.parent,
                    keyword: query
                }
            })
            .then(res => {
                this.loading = false;
                this.dataRows = res.data.data;
                let expense_desc = '';
                res.data.data.filter((value) => {
                    if(value.category_concat_segs == this.value){
                        expense_desc = value.description;
                    }
                });
                this.$emit('setExpenseType', {expense_type: this.value, expense_description: expense_desc});
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
