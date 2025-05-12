<template>
    <div>
        <div id="modal-flexfield" class="modal fade" aria-hidden="true" data-backdrop="static" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body pb-0" style="">
                        <h3 class="text-left mb-3 tw-text-grey-darknes"> รายการบัญชี </h3>
                        <form id='segment-form'>
                            <div class="row">
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> หน่วยเบิกจ่าย </label><br>
                                    <coaComponent
                                        @coa="updateCoaFrom"
                                        :set-name="defaultValueSetName.segment1"
                                        :set-data="segment1"
                                        placeholder=""
                                        ref="segment1"
                                        :default-value-set-name="defaultValueSetName"
                                        :error="errors.segment1"
                                        :set-options="option1"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_1" class="error_msg text-left"></div>
                                </div>
                               <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> ศูนย์ต้นทุน </label><br>
                                    <coaComponent
                                        @coa="updateCoaFrom"
                                        :set-name="defaultValueSetName.segment2"
                                        :parent="segment1"
                                        :set-data="segment2"
                                        placeholder=""
                                        ref="segment2"
                                        :default-value-set-name="defaultValueSetName"
                                        :error="errors.segment2"
                                        :set-options="option2"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_2" class="error_msg text-left"></div>
                                </div>
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> ปีงบประมาณ </label><br>
                                    <coaComponent
                                        @coa="updateCoaFrom"
                                        :set-name="defaultValueSetName.segment3"
                                        :set-data="segment3"
                                        placeholder=""
                                        ref="segment3"
                                        :default-value-set-name="defaultValueSetName"
                                        :error="errors.segment3"
                                        :set-options="option3"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_3" class="error_msg text-left"></div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> แหล่งเงิน </label><br>
                                    <coaComponent
                                        @coa="updateCoaFrom"
                                        :set-name="defaultValueSetName.segment4"
                                        :set-data="segment4"
                                        placeholder=""
                                        ref="segment4"
                                        :default-value-set-name="defaultValueSetName"
                                        :error="errors.segment4"
                                        :set-options="option4"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_4" class="error_msg text-left"></div>
                                </div>
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> แผนงาน </label><br>
                                    <coaComponent
                                        @coa="updateCoaFrom"
                                        :set-name="defaultValueSetName.segment5"
                                        :set-data="segment5"
                                        placeholder=""
                                        ref="segment5"
                                        :default-value-set-name="defaultValueSetName"
                                        :error="errors.segment5"
                                        :set-options="option5"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_5" class="error_msg text-left"></div>
                                </div>
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> ผลผลิต/แผนงานรอง/โครงการ </label><br>
                                    <coaComponent
                                        @coa="updateCoaFrom"
                                        :set-name="defaultValueSetName.segment6"
                                        :set-data="segment6"
                                        placeholder=""
                                        ref="segment6"
                                        :default-value-set-name="defaultValueSetName"
                                        :error="errors.segment6"
                                        :set-options="option6"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_6" class="error_msg text-left"></div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> กิจกรรม </label><br>
                                    <coaComponent
                                        @coa="updateCoaFrom"
                                        :set-name="defaultValueSetName.segment7"
                                        :set-data="segment7"
                                        placeholder=""
                                        ref="segment7"
                                        :default-value-set-name="defaultValueSetName"
                                        :error="errors.segment7"
                                        :set-options="option7"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_7" class="error_msg text-left"></div>
                                </div>
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> ประเภทรายจ่าย </label><br>
                                    <coaComponent
                                        @coa="updateCoaFrom"
                                        :set-name="defaultValueSetName.segment8"
                                        :set-data="segment8"
                                        placeholder=""
                                        ref="segment8"
                                        :default-value-set-name="defaultValueSetName"
                                        :error="errors.segment8"
                                        :set-options="option8"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_8" class="error_msg text-left"></div>
                                </div>
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> รหัสงบประมาณ </label><br>
                                    <coaComponent
                                        @coa="updateCoaFrom"
                                        :set-name="defaultValueSetName.segment9"
                                        :set-data="segment9"
                                        placeholder=""
                                        ref="segment9"
                                        :default-value-set-name="defaultValueSetName"
                                        :error="errors.segment9"
                                        :set-options="option9"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_9" class="error_msg text-left"></div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> รหัสบัญชีหลัก </label><br>
                                    <coaComponent
                                        @coa="updateCoaFrom"
                                        :set-name="defaultValueSetName.segment10"
                                        :set-data="segment10"
                                        placeholder=""
                                        ref="segment10"
                                        :default-value-set-name="defaultValueSetName"
                                        :error="errors.segment10"
                                        :set-options="option10"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_10" class="error_msg text-left"></div>
                                </div>
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> รหัสบัญชีย่อย/รายจ่ายย่อย </label><br>
                                    <coaComponent
                                        @coa="updateCoaFrom"
                                        :set-name="defaultValueSetName.segment11"
                                        :parent="segment10"
                                        :set-data="segment11"
                                        placeholder=""
                                        ref="segment11"
                                        :default-value-set-name="defaultValueSetName"
                                        :error="errors.segment11"
                                        :set-options="option11"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_11" class="error_msg text-left"></div>
                                </div>
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> สำรอง 1 </label><br>
                                    <coaComponent
                                        @coa="updateCoaFrom"
                                        :set-name="defaultValueSetName.segment12"
                                        :set-data="segment12"
                                        placeholder=""
                                        ref="segment12"
                                        :default-value-set-name="defaultValueSetName"
                                        :error="errors.segment12"
                                        :set-options="option12"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_12" class="error_msg text-left"></div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> สำรอง 2 </label><br>
                                    <coaComponent
                                        @coa="updateCoaFrom"
                                        :set-name="defaultValueSetName.segment13"
                                        :set-data="segment13"
                                        placeholder=""
                                        ref="segment13"
                                        :default-value-set-name="defaultValueSetName"
                                        :error="errors.segment13"
                                        :set-options="option13"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_13" class="error_msg text-left"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer pt-2">
                        <button type="button" class="btn btn-primary btn-sm" @click.private="accountConfirm"
                            style="color: #fff; background-color: #01b471; border-color: #01b471;">
                            <i class="fa fa-check"></i> ตกลง
                        </button>
                         <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times"></i> ยกเลิก
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import coaComponent from './InputCOAComponent.vue';
export default {
    components: {
        coaComponent
    },
    props: ['itemSegment', 'defaultValueSetName'],
    data() {
        return {
            options: [],
            value: "",
            loading: false,
            account: this.search? this.search.account_from: '',
            // coaEnter: this.search? this.search.account_from: '',
            // Segment
            segment1: '', segment2: '', segment3: '', segment4: '', segment5: '', segment6: '',
            segment7: '', segment8: '', segment9: '', segment10: '', segment11: '', segment12: '', segment13: '',
            // Option
            option1: [], option2: [], option3: [], option4: [], option5: [], option6: [],
            option7: [], option8: [], option9: [], option10: [], option11: [], option12: [], option13: [], 
            errors: {
                segmentOverride: false,
                segment1: false,
                segment2: false,
                segment3: false,
                segment4: false,
                segment5: false,
                segment6: false,
                segment7: false
            },
        };
    },
    mounted() {
        this.value = this.setData;
        this.getValueSetList(this.value);
        // this.changeCoa();
    },
    watch: {
        setData() {
            this.value = this.setData;
            this.getValueSetList(this.value);
            this.options = this.setOptions;
        },
        errors: {
            handler(val){
                val.segmentOverride? this.setError('segmentOverride') : this.resetError('segmentOverride');
            },
            deep: true,
        },
    },
    methods: {
        setError(ref_name){
        console.log(this.$refs[ref_name].$refs);
            let ref = this.$refs[ref_name].$refs.reference 
                    ? this.$refs[ref_name].$refs.reference.$refs.input 
                    : (this.$refs[ref_name].$refs.textarea 
                        ? this.$refs[ref_name].$refs.textarea 
                        : (this.$refs[ref_name].$refs.input.$refs 
                            ? this.$refs[ref_name].$refs.input.$refs.input 
                            : this.$refs[ref_name].$refs.wrapperRef ));
            ref.style = "border: 1px solid red;";
        },
        resetError(ref_name){
            let ref = this.$refs[ref_name].$refs.reference 
                    ? this.$refs[ref_name].$refs.reference.$refs.input 
                    : (this.$refs[ref_name].$refs.textarea 
                        ? this.$refs[ref_name].$refs.textarea
                        : (this.$refs[ref_name].$refs.input.$refs 
                            ? this.$refs[ref_name].$refs.input.$refs.input 
                            : this.$refs[ref_name].$refs.wrapperRef ));
            ref.style = "";
        },
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
        updateCoaFrom(res){
                if (res.name == this.defaultValueSetName.segment1) { 
                    this.segment1From = res.segment1From;
                    this.segment1To = res.segment1To;
                    this.option1 = res.options;
                }
                if (res.name == this.defaultValueSetName.segment2) {
                    this.segment2From = res.segment2From; 
                    this.segment2To = res.segment2To;
                    this.option2 = res.options;
                }
                if (res.name == this.defaultValueSetName.segment3) {
                    this.segment3From = res.segment3From;
                    this.segment3To = res.segment3To;
                    // this.segment4From = '';
                    this.option3 = res.options;
                }
                if (res.name == this.defaultValueSetName.segment4) {
                    this.segment4From = res.segment4From;
                    this.segment4To = res.segment4To;
                    this.option4 = res.options;
                }
                if (res.name == this.defaultValueSetName.segment5) {
                    this.segment5From = res.segment5From;
                    this.segment5To = res.segment5To;
                    this.option5 = res.options;
                }
                if (res.name == this.defaultValueSetName.segment6) {
                    this.segment6From = res.segment6From; 
                    this.segment6To = res.segment6To;
                    // this.segment7From = '';
                    this.option6 = res.options;
                }
                if (res.name == this.defaultValueSetName.segment7) {
                    this.segment7From = res.segment7From;
                    this.segment7To = res.segment7To;
                    this.option7 = res.options;
                }
                if (res.name == this.defaultValueSetName.segment8) {
                    this.segment8From = res.segment8From;
                    this.segment8To = res.segment8To;
                    this.option8 = res.options;
                }
            },
            updateCoaTo(res){
                if (res.name == this.defaultValueSetName.segment1) {
                    this.segment1To = res.segment1To;
                    this.option1 = res.options;
                }
                if (res.name == this.defaultValueSetName.segment2) {
                    this.segment2To = res.segment2To;
                    this.option2 = res.options;
                }
                if (res.name == this.defaultValueSetName.segment3) {
                    this.segment3To = res.segment3To;
                    // this.segment4To = '';
                    this.option3 = res.options;
                }
                if (res.name == this.defaultValueSetName.segment4) {
                    this.segment4To = res.segment4To;
                    this.option4 = res.options;
                }
                if (res.name == this.defaultValueSetName.segment5) {
                    this.segment5To = res.segment5To;
                    this.option5 = res.options;
                }
                if (res.name == this.defaultValueSetName.segment6) {
                    this.segment6To = res.segment6To;
                    // this.segment7To = '';
                    this.option6 = res.options;
                }
                if (res.name == this.defaultValueSetName.segment7) {
                    this.segment7To = res.segment7To;
                    this.option7 = res.options;
                }
            },
            enterAccSegment(){
                var form  = $('#modal-flexfield');
                let valid = true;
                let errorMsg;
                this.errors.segmentOverride = false;
                $(form).find("div[id='el_explode_segment']").html("");

                // if (this.coaEnter != null || this.coaEnter != '') {
                    // var coa = this.coaEnter.split('.');
                    
                    // if (coa[0] == '' || coa[1] == '' || coa[2] == '' || coa[3] == '' || coa[4] == '' || coa[5] == '' || coa[6] == '' || coa[7] == '' || coa[8] == '' || coa[9] == '' || coa[10] == '' || coa[11] == '') {
                    //     this.errors.segmentOverride = true;
                    //     valid = false;
                    //     errorMsg = "กรอกชุดบัญชีไม่ครบ กรุณาตรวจสอบ";
                    //     $(form).find("div[id='el_explode_segment']").html(errorMsg);
                    // }
                // }
                if (!valid) {
                    return;
                }
                var defaultCoa = this.account_from;
                this.account_from = this.coaEnter;
                this.account_to = this.account_to ?? this.coaEnter;
                if (this.coaEnter) {
                    var coa = this.coaEnter.split('.');
                    this.segment1From = coa[0];
                    this.segment2From = coa[1];
                    this.segment3From = coa[2];
                    this.segment4From = coa[3];
                    this.segment5From = coa[4];
                    this.segment6From = coa[5];
                    this.segment7From = coa[6];
                }

                if (this.account_to && defaultCoa == this.coaEnter) {
                    var last_coa = this.account_to.split('.');
                    this.segment1To = last_coa[0];
                    this.segment2To = last_coa[1];
                    this.segment3To = last_coa[2];
                    this.segment4To = last_coa[3];
                    this.segment5To = last_coa[4];
                    this.segment6To = last_coa[5];
                    this.segment7To = last_coa[6];
                }else if(this.coaEnter){
                    this.segment1To = coa[0];
                    this.segment2To = coa[1];
                    this.segment3To = coa[2];
                    this.segment4To = coa[3];
                    this.segment5To = coa[4];
                    this.segment6To = coa[5];
                    this.segment7To = coa[6];
                }
                return;
            },
            clearAccSegment(){
                var form  = $('#modal-flexfield');
                let valid = true;
                this.errors.segmentOverride = false;
                $(form).find("div[id='el_explode_segment']").html("");
                this.account_from = '';
                this.account_to = '';
                this.coaEnter = '';
                this.segment1From = '';
                this.segment2From = '';
                this.segment3From = '';
                this.segment4From = '';
                this.segment5From = '';
                this.segment6From = '';
                this.segment7From = '';

                this.segment1To = '';
                this.segment2To = '';
                this.segment3To = '';
                this.segment4To = '';
                this.segment5To = '';
                this.segment6To = '';
                this.segment7To = '';
                return;
            },
            accountConfirm(){
                var vm = this;
                var form = $('#tb-segment-override');
                var form2 = $('#modal-flexfield');
                let errorMsg;
                let valid = true;
                vm.errors.segment1From = false;
                vm.errors.segment2From = false;
                vm.errors.segment3From = false;
                vm.errors.segment4From = false;
                vm.errors.segment5From = false;
                vm.errors.segment6From = false;
                vm.errors.segment7From = false;
                vm.errors.segmentOverride = false;
                $(form).find("div[id='el_explode_acc_1']").html("");
                $(form).find("div[id='el_explode_acc_2']").html("");
                $(form).find("div[id='el_explode_acc_3']").html("");
                $(form).find("div[id='el_explode_acc_4']").html("");
                $(form).find("div[id='el_explode_acc_5']").html("");
                $(form).find("div[id='el_explode_acc_6']").html("");
                $(form).find("div[id='el_explode_acc_7']").html("");
                $(form).find("div[id='el_explode_segment']").html("");

                if (vm.segment1From == '' && vm.segment2From == '' && vm.segment3From == '' && vm.segment4From == '' && vm.segment5From == '' && vm.segment6From == '' && vm.segment7From == '') {
                    vm.errors.segmentOverride = true;
                    valid = false;
                    errorMsg = "ยังไม่มีการกรอกชุดบัญชี กรุณาตรวจสอบ";
                    $(form2).find("div[id='el_explode_segment']").html(errorMsg);
                }
                if (!valid) {
                    return;
                }
                vm.account_from = vm.segment1From+'.'+vm.segment2From+'.'+vm.segment3From+'.'+vm.segment4From+'.'+vm.segment5From+'.'+vm.segment6From+'.'+vm.segment7From;
                vm.account_to = vm.segment1To+'.'+vm.segment2To+'.'+vm.segment3To+'.'+vm.segment4To+'.'+vm.segment5To+'.'+vm.segment6To+'.'+vm.segment7To;
                vm.coaEnter = vm.account_from

                this.$emit("accountSegment", {account_from: vm.account_from, account_to: vm.account_to, coaEnter: vm.coaEnter});
                $('#modal-flexfield').modal('hide');
            },









        // changeCoa() {
        //     if (this.setName == this.defaultValueSetName.segment1) {
        //         this.$emit("coa", {name: this.setName, segment1From: this.value, segment1To: this.value, options: this.options});
        //     }
        //     if (this.setName == this.defaultValueSetName.segment2) {
        //         this.$emit("coa", {name: this.setName, segment2From: this.value, segment2To: this.value, options: this.options});
        //     }
        //     if (this.setName == this.defaultValueSetName.segment3) {
        //         this.$emit("coa", {name: this.setName, segment3From: this.value, segment3To: this.value, options: this.options});
        //     }
        //     if (this.setName == this.defaultValueSetName.segment4) {
        //         this.$emit("coa", {name: this.setName, segment4From: this.value, segment4To: this.value, options: this.options});
        //     }
        //     if (this.setName == this.defaultValueSetName.segment5) {
        //         this.$emit("coa", {name: this.setName, segment5From: this.value, segment5To: this.value, options: this.options});
        //     }
        //     if (this.setName == this.defaultValueSetName.segment6) {
        //         this.$emit("coa", {name: this.setName, segment6From: this.value, segment6To: this.value, options: this.options});
        //     }
        //     if (this.setName == this.defaultValueSetName.segment7) {
        //         this.$emit("coa", {name: this.setName, segment7From: this.value, segment7To: this.value, options: this.options});
        //     }
        // }
    }
};
</script>

<style>
    .el-popper{
        z-index: 9999 !important;
    }
</style>