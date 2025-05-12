<template>
    <div>
        <el-select
            v-model="value"
            filterable
            remote
            clearable 
            reserve-keyword
            placeholder="Please enter a value"
            :remote-method="getValueSetList"
            :loading="loading"
            size="default"
            @change="changeCoa"
            class="w-100 el-select-input-segment"
            style="width: 100%;"
            ref="input"
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
    props: ['setName', 'setData', 'setParent', 'error', 'defaultValueSetName', 'setOptions'],
    // , "setOptions"
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
            this.options = this.setOptions;
        },
        error() {
            let ref = this.$refs['input'].$refs.reference.$refs.input;
            ref.style = "";
            if(this.error && (this.value === '' || this.value === null)){
                ref.style = "border: 1px solid red;";
            }
        },
    },
    methods: {
        async getValueSetList(query) {
            // this.loading = true;
            await axios.get("/ajax/inquiry-funds", {
                params: {
                    flex_value_set_name: this.setName,
                    flex_value_set_data: this.value,
                    flex_value_parent: this.setParent,
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
            if (this.setName == this.defaultValueSetName.segment1) {
                this.$emit("coa", {name: this.setName, segment1From: this.value, segment1To: this.value, options: this.options});
            }
            if (this.setName == this.defaultValueSetName.segment2) {
                this.$emit("coa", {name: this.setName, segment2From: this.value, segment2To: this.value, options: this.options});
            }
            if (this.setName == this.defaultValueSetName.segment3) {
                this.$emit("coa", {name: this.setName, segment3From: this.value, segment3To: this.value, options: this.options});
            }
            if (this.setName == this.defaultValueSetName.segment4) {
                this.$emit("coa", {name: this.setName, segment4From: this.value, segment4To: this.value, options: this.options});
            }
            if (this.setName == this.defaultValueSetName.segment5) {
                this.$emit("coa", {name: this.setName, segment5From: this.value, segment5To: this.value, options: this.options});
            }
            if (this.setName == this.defaultValueSetName.segment6) {
                this.$emit("coa", {name: this.setName, segment6From: this.value, segment6To: this.value, options: this.options});
            }
            if (this.setName == this.defaultValueSetName.segment7) {
                this.$emit("coa", {name: this.setName, segment7From: this.value, segment7To: this.value, options: this.options});
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