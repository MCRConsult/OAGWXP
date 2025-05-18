<template>
    <div>
        <el-input name="itemSegment" placeholder="" v-model="account" 
            size="default" autocomplete="off" 
            style="width: 100%"
            readonly
            @click="segmentModal(index)"
        > </el-input>
        <div id="modal-flexfield" :class="'modal fade modal-flexfield'+index" aria-hidden="true" data-backdrop="static" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body pb-0" style="">
                        <h3 class="text-left mb-3 tw-text-grey-darknes"> รายการบัญชี </h3>
                        <form id='segment-form'>
                            <div class="row">
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> หน่วยเบิกจ่าย </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment1"
                                        :set-data="segment1"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment1"
                                        placeholder=""
                                        ref="segment1"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_1" class="error_msg text-left"></div>
                                </div>
                               <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> ศูนย์ต้นทุน </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment2"
                                        :set-data="segment2"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment2"
                                        placeholder=""
                                        ref="segment2"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_2" class="error_msg text-left"></div>
                                </div>
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> ปีงบประมาณ </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment3"
                                        :set-data="segment3"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment3"
                                        placeholder=""
                                        ref="segment3"
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
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment4"
                                        :set-data="segment4"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment4"
                                        placeholder=""
                                        ref="segment4"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_4" class="error_msg text-left"></div>
                                </div>
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> แผนงาน </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment5"
                                        :set-data="segment5"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment5"
                                        placeholder=""
                                        ref="segment5"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_5" class="error_msg text-left"></div>
                                </div>
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> ผลผลิต/แผนงานรอง/โครงการ </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment6"
                                        :set-data="segment6"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment6"
                                        placeholder=""
                                        ref="segment6"
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
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment7"
                                        :set-data="segment7"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment7"
                                        placeholder=""
                                        ref="segment7"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_7" class="error_msg text-left"></div>
                                </div>
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> ประเภทรายจ่าย </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment8"
                                        :set-data="segment8"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment8"
                                        placeholder=""
                                        ref="segment8"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_8" class="error_msg text-left"></div>
                                </div>
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> รหัสงบประมาณ </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment9"
                                        :set-data="segment9"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment9"
                                        placeholder=""
                                        ref="segment9"
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
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment10"
                                        :set-data="segment10"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment10"
                                        placeholder=""
                                        ref="segment10"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_10" class="error_msg text-left"></div>
                                </div>
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> รหัสบัญชีย่อย/รายจ่ายย่อย </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment11"
                                        :set-data="segment11"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment11"
                                        placeholder=""
                                        ref="segment11"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_11" class="error_msg text-left"></div>
                                </div>
                                <div class="col-sm-4 text-left">
                                    <label class="control-label" style="margin-bottom: 0.4em;"> สำรอง 1 </label><br>
                                    <coaComponent
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment12"
                                        :set-data="segment12"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment12"
                                        placeholder=""
                                        ref="segment12"
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
                                        @coa="updateCoa"
                                        :set-name="defaultSetName.segment13"
                                        :set-data="segment13"
                                        :default-set-name="defaultSetName"
                                        :error="errors.segment13"
                                        placeholder=""
                                        ref="segment13"
                                    >
                                    </coaComponent>
                                    <div id="el_explode_acc_13" class="error_msg text-left"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br>
                    <div class="modal-footer pt-2">
                        <button type="button" class="btn btn-primary btn-sm" @click.private="accountConfirm"
                            style="color: #fff; background-color: #01b471; border-color: #01b471;">
                            ตกลง
                        </button>
                        <button type="button" class="btn btn-sm btn-warning" data-dismiss="modal" aria-label="Close">
                            ยกเลิก
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
    props: ['index', 'itemSegment', 'defaultSetName'],
    data() {
        return {
            options: [],
            value: "",
            loading: false,
            account: this.itemSegment,
            // Segment
            segment1: '', segment2: '', segment3: '', segment4: '', segment5: '', segment6: '',
            segment7: '', segment8: '', segment9: '', segment10: '', segment11: '', segment12: '', segment13: '',
            errors: {
                segment1: false,
                segment2: false,
                segment3: false,
                segment4: false,
                segment5: false,
                segment6: false,
                segment7: false,
                segment8: false,
                segment9: false,
                segment10: false,
                segment11: false,
                segment12: false,
                segment13: false
            },
        };
    },
    mounted() {
        // this.value = this.setData;
        // this.getValueSetList(this.value);
        // this.changeCoa();
    },
    watch: {
        // setData() {
        //     this.value = this.setData;
        //     // this.getValueSetList(this.value);
        //     // this.options = this.setOptions;
        // },
        // errors: {
        //     handler(val){
        //         val.segmentOverride? this.setError('segmentOverride') : this.resetError('segmentOverride');
        //     },
        //     deep: true,
        // },
    },
    methods: {
        segmentModal(index){
            $('.modal-edit'+index).modal('hide');
            $('.modal-flexfield'+index).modal('show');
        },
        setError(ref_name){
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
        // async getValueSetList(query) {
        //     // this.loading = true;
        //     await axios.get("/ajax/inquiry-funds", {
        //         params: {
        //             flex_value_set_name: this.setName,
        //             flex_value_set_data: this.value,
        //             query: query,
        //         }
        //     })
        //     .then(res => {
        //         this.options = res.data;
        //     })
        //     .catch(err => {
        //         console.log(err)
        //     })
        //     .then( () => {
        //         this.loading = false;
        //     });
        // },
        updateCoa(res){
                if (res.name == this.defaultSetName.segment1) { 
                    this.segment1 = res.segment1;
                    this.option1 = res.options;
                }
                if (res.name == this.defaultSetName.segment2) {
                    this.segment2 = res.segment2; 
                    this.option2 = res.options;
                }
                if (res.name == this.defaultSetName.segment3) {
                    this.segment3 = res.segment3;
                    this.option3 = res.options;
                }
                if (res.name == this.defaultSetName.segment4) {
                    this.segment4 = res.segment4;
                    this.option4 = res.options;
                }
                if (res.name == this.defaultSetName.segment5) {
                    this.segment5 = res.segment5;
                    this.option5 = res.options;
                }
                if (res.name == this.defaultSetName.segment6) {
                    this.segment6 = res.segment6; 
                    this.option6 = res.options;
                }
                if (res.name == this.defaultSetName.segment7) {
                    this.segment7 = res.segment7;
                    this.option7 = res.options;
                }
                if (res.name == this.defaultSetName.segment8) {
                    this.segment8 = res.segment8;
                    this.option8 = res.options;
                }
                if (res.name == this.defaultSetName.segment9) {
                    this.segment9 = res.segment9;
                    this.option8 = res.options;
                }
                if (res.name == this.defaultSetName.segment10) {
                    this.segment10 = res.segment10;
                    this.option8 = res.options;
                }
                if (res.name == this.defaultSetName.segment11) {
                    this.segment11 = res.segment11;
                    this.option8 = res.options;
                }
                if (res.name == this.defaultSetName.segment12) {
                    this.segment12 = res.segment12;
                    this.option8 = res.options;
                }
                if (res.name == this.defaultSetName.segment13) {
                    this.segment13 = res.segment13;
                    this.option8 = res.options;
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
        //     if (this.setName == this.defaultSetName.segment1) {
        //         this.$emit("coa", {name: this.setName, segment1From: this.value, segment1To: this.value, options: this.options});
        //     }
        //     if (this.setName == this.defaultSetName.segment2) {
        //         this.$emit("coa", {name: this.setName, segment2From: this.value, segment2To: this.value, options: this.options});
        //     }
        //     if (this.setName == this.defaultSetName.segment3) {
        //         this.$emit("coa", {name: this.setName, segment3From: this.value, segment3To: this.value, options: this.options});
        //     }
        //     if (this.setName == this.defaultSetName.segment4) {
        //         this.$emit("coa", {name: this.setName, segment4From: this.value, segment4To: this.value, options: this.options});
        //     }
        //     if (this.setName == this.defaultSetName.segment5) {
        //         this.$emit("coa", {name: this.setName, segment5From: this.value, segment5To: this.value, options: this.options});
        //     }
        //     if (this.setName == this.defaultSetName.segment6) {
        //         this.$emit("coa", {name: this.setName, segment6From: this.value, segment6To: this.value, options: this.options});
        //     }
        //     if (this.setName == this.defaultSetName.segment7) {
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