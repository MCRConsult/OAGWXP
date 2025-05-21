<template>
    <div>
        <el-select
            v-model="value"
            filterable
            remote
            clearable 
            reserve-keyword
            placeholder=""
            :remote-method="getValueSetList"
            :loading="loading"
            size="default"
            class="w-100 el-select-input-segment"
            remote-show-suffix
            style="width: 100%;"
            ref="input"
            @change="changeCoa"
        >
            <el-option
                v-for="(item, key) in options"
                :key="key"
                :label="item.flex_value + ' : ' + item.description"
                :value="item.flex_value"
            />
        </el-select>
    </div>
</template>
<script>
export default {
    props: ['setName', 'parent', 'setData', 'error', 'defaultSetName'],
    emits: ['coa'],
    data() {
        return {
            options: [],
            value: '',
            loading: false
        };
    },
    mounted() {
        this.value = this.setData;
        this.getValueSetList(this.value);
        this.changeCoa();
    },
    watch: {
        setData() {
            this.value = this.setData;
            this.getValueSetList(this.value);
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
        async getValueSetList(query) {
            await axios.get("/expense/api/get-expense-account", {
                params: {
                    flex_value_set_name: this.setName,
                    flex_value_set_parent: this.parent,
                    flex_value_set_data: this.value,
                    query: query,
                }
            })
            .then(res => {
                this.options = res.data;
            })
            .catch(err => {
                console.log(err)
            })
            .then( () => {
                this.loading = false;
            });
        },
        changeCoa() {
            if (this.setName == this.defaultSetName.segment1) {
                this.$emit("coa", {name: this.setName, segment1: this.value});
            }
            if (this.setName == this.defaultSetName.segment2) {
                this.$emit("coa", {name: this.setName, segment2: this.value});
            }
            if (this.setName == this.defaultSetName.segment3) {
                this.$emit("coa", {name: this.setName, segment3: this.value});
            }
            if (this.setName == this.defaultSetName.segment4) {
                this.$emit("coa", {name: this.setName, segment4: this.value});
            }
            if (this.setName == this.defaultSetName.segment5) {
                this.$emit("coa", {name: this.setName, segment5: this.value});
            }
            if (this.setName == this.defaultSetName.segment6) {
                this.$emit("coa", {name: this.setName, segment6: this.value});
            }
            if (this.setName == this.defaultSetName.segment7) {
                this.$emit("coa", {name: this.setName, segment7: this.value});
            }
            if (this.setName == this.defaultSetName.segment8) {
                this.$emit("coa", {name: this.setName, segment8: this.value});
            }
            if (this.setName == this.defaultSetName.segment9) {
                this.$emit("coa", {name: this.setName, segment9: this.value});
            }
            if (this.setName == this.defaultSetName.segment10) {
                this.$emit("coa", {name: this.setName, segment10: this.value});
            }
            if (this.setName == this.defaultSetName.segment11) {
                this.$emit("coa", {name: this.setName, segment11: this.value});
            }
            if (this.setName == this.defaultSetName.segment12) {
                this.$emit("coa", {name: this.setName, segment12: this.value});
            }
            if (this.setName == this.defaultSetName.segment13) {
                this.$emit("coa", {name: this.setName, segment13: this.value});
            }
        }
    }
};
</script>

<style>
    .el-popper{
        z-index: 9999 !important;
    }
</style>