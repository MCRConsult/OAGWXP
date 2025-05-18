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
    props: ['setName', 'setData', 'setParent', 'error', 'defaultSetName', 'setOptions'],
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
            if (this.setName == this.defaultSetName.segment1) {
                this.$emit("coa", {name: this.setName, segment1: this.value, options: this.options});
            }
            if (this.setName == this.defaultSetName.segment2) {
                this.$emit("coa", {name: this.setName, segment2: this.value, options: this.options});
            }
            if (this.setName == this.defaultSetName.segment3) {
                this.$emit("coa", {name: this.setName, segment3: this.value, options: this.options});
            }
            if (this.setName == this.defaultSetName.segment4) {
                this.$emit("coa", {name: this.setName, segment4: this.value, options: this.options});
            }
            if (this.setName == this.defaultSetName.segment5) {
                this.$emit("coa", {name: this.setName, segment5: this.value, options: this.options});
            }
            if (this.setName == this.defaultSetName.segment6) {
                this.$emit("coa", {name: this.setName, segment6: this.value, options: this.options});
            }
            if (this.setName == this.defaultSetName.segment7) {
                this.$emit("coa", {name: this.setName, segment7: this.value, options: this.options});
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