<template>
    <button type="button" class="btn btn-sm btn-success m-1" data-toggle="collapse" @click.prevent="openModal">
        <i class="icons cui-chevron-top"></i>
    </button>

    <div id="modal-account" class="modal fade" aria-labelledby="myModalLabel" tabindex="-1" role="dialog" 
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> รายการบัญชี </h4>
                </div>
                <div class="modal-body m-2">
                    <form class="account-form">
                        <!-- EXPENSE ACCOUNT -->
                        <div class="row">
                            <div class="col-sm-3 text-left">
                                <label class="control-label" style="margin-bottom: 0.4em;">
                                    <strong> หน่วยเบิกจ่าย <span class="text-danger"> * </span> </strong>
                                </label><br>
                                <coaComponent
                                    @coa="updateCoa"
                                    :set-name="defaultSetName.segment1"
                                    :set-data="segment1"
                                    :default-set-name="defaultSetName"
                                    :error="errors.segment1"
                                    :editFlag="false"
                                    placeholder=""
                                    ref="segment1"
                                >
                                </coaComponent>
                                <div id="_el_explode_acc_1" class="text-danger text-left"></div>
                            </div>
                           <div class="col-sm-3 text-left">
                                <label class="control-label" style="margin-bottom: 0.4em;">
                                    <strong> ศูนย์ต้นทุน <span class="text-danger"> * </span> </strong>
                                </label><br>
                                <coaComponent
                                    @coa="updateCoa"
                                    :set-name="defaultSetName.segment2"
                                    :set-data="segment2"
                                    :default-set-name="defaultSetName"
                                    :error="errors.segment2"
                                    :editFlag="true"
                                    placeholder=""
                                    ref="segment2"
                                >
                                </coaComponent>
                                <div id="_el_explode_acc_2" class="text-danger text-left"></div>
                            </div>
                            <div class="col-sm-3 text-left">
                                <label class="control-label" style="margin-bottom: 0.4em;">
                                    <strong> ปีงบประมาณ <span class="text-danger"> * </span> </strong>
                                </label><br>
                                <coaComponent
                                    @coa="updateCoa"
                                    :set-name="defaultSetName.segment3"
                                    :set-data="segment3"
                                    :default-set-name="defaultSetName"
                                    :error="errors.segment3"
                                    :editFlag="true"
                                    placeholder=""
                                    ref="segment3"
                                >
                                </coaComponent>
                                <div id="_el_explode_acc_3" class="text-danger text-left"></div>
                            </div>
                            <div class="col-sm-3 text-left">
                                <label class="control-label" style="margin-bottom: 0.4em;">
                                    <strong> แหล่งเงิน <span class="text-danger"> * </span> </strong>
                                </label><br>
                                <coaComponent
                                    @coa="updateCoa"
                                    :set-name="defaultSetName.segment4"
                                    :set-data="segment4"
                                    :default-set-name="defaultSetName"
                                    :error="errors.segment4"
                                    :editFlag="false"
                                    placeholder=""
                                    ref="segment4"
                                >
                                </coaComponent>
                                <div id="_el_explode_acc_4" class="text-danger text-left"></div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3 text-left">
                                <label class="control-label" style="margin-bottom: 0.4em;">
                                    <strong> แผนงาน <span class="text-danger"> * </span> </strong>
                                </label><br>
                                <coaComponent
                                    @coa="updateCoa"
                                    :set-name="defaultSetName.segment5"
                                    :set-data="segment5"
                                    :default-set-name="defaultSetName"
                                    :error="errors.segment5"
                                    :editFlag="false"
                                    placeholder=""
                                    ref="segment5"
                                >
                                </coaComponent>
                                <div id="_el_explode_acc_5" class="text-danger text-left"></div>
                            </div>
                            <div class="col-sm-3 text-left">
                                <label class="control-label" style="margin-bottom: 0.4em;">
                                    <strong> ผลผลิต/แผนงานรอง/โครงการ <span class="text-danger"> * </span> </strong> 
                                </label><br>
                                <coaComponent
                                    @coa="updateCoa"
                                    :set-name="defaultSetName.segment6"
                                    :set-data="segment6"
                                    :default-set-name="defaultSetName"
                                    :error="errors.segment6"
                                    :editFlag="true"
                                    placeholder=""
                                    ref="segment6"
                                >
                                </coaComponent>
                                <div id="_el_explode_acc_6" class="text-danger text-left"></div>
                            </div>
                            <div class="col-sm-3 text-left">
                                <label class="control-label" style="margin-bottom: 0.4em;">
                                    <strong> กิจกรรม <span class="text-danger"> * </span> </strong>
                                </label><br>
                                <coaComponent
                                    @coa="updateCoa"
                                    :set-name="defaultSetName.segment7"
                                    :set-data="segment7"
                                    :default-set-name="defaultSetName"
                                    :error="errors.segment7"
                                    :editFlag="true"
                                    placeholder=""
                                    ref="segment7"
                                >
                                </coaComponent>
                                <div id="_el_explode_acc_7" class="text-danger text-left"></div>
                            </div>
                            <div class="col-sm-3 text-left">
                                <label class="control-label" style="margin-bottom: 0.4em;">
                                    <strong> ประเภทรายจ่าย <span class="text-danger"> * </span> </strong>
                                </label><br>
                                <coaComponent
                                    @coa="updateCoa"
                                    :set-name="defaultSetName.segment8"
                                    :set-data="segment8"
                                    :default-set-name="defaultSetName"
                                    :error="errors.segment8"
                                    :editFlag="false"
                                    placeholder=""
                                    ref="segment8"
                                >
                                </coaComponent>
                                <div id="_el_explode_acc_8" class="text-danger text-left"></div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3 text-left">
                                <label class="control-label" style="margin-bottom: 0.4em;">
                                    <strong> รหัสงบประมาณ <span class="text-danger"> * </span> </strong>
                                </label><br>
                                <coaComponent
                                    @coa="updateCoa"
                                    :set-name="defaultSetName.segment9"
                                    :set-data="segment9"
                                    :default-set-name="defaultSetName"
                                    :error="errors.segment9"
                                    :editFlag="true"
                                    placeholder=""
                                    ref="segment9"
                                >
                                </coaComponent>
                                <div id="_el_explode_acc_9" class="text-danger text-left"></div>
                            </div>
                            <div class="col-sm-3 text-left">
                                <label class="control-label" style="margin-bottom: 0.4em;">
                                    <strong> รหัสบัญชีหลัก <span class="text-danger"> * </span> </strong>
                                </label><br>
                                <coaComponent
                                    @coa="updateCoa"
                                    :set-name="defaultSetName.segment10"
                                    :set-data="segment10"
                                    :default-set-name="defaultSetName"
                                    :error="errors.segment10"
                                    :editFlag="true"
                                    placeholder=""
                                    ref="segment10"
                                >
                                </coaComponent>
                                <div id="_el_explode_acc_10" class="text-danger text-left"></div>
                            </div>
                            <div class="col-sm-3 text-left">
                                <label class="control-label" style="margin-bottom: 0.4em;">
                                    <strong> รหัสบัญชีย่อย/รายจ่ายย่อย <span class="text-danger"> * </span> </strong>
                                </label><br>
                                <coaComponent
                                    @coa="updateCoa"
                                    :set-name="defaultSetName.segment11"
                                    :parent="segment10"
                                    :set-data="segment11"
                                    :default-set-name="defaultSetName"
                                    :error="errors.segment11"
                                    :editFlag="true"
                                    placeholder=""
                                    ref="segment11"
                                >
                                </coaComponent>
                                <div id="_el_explode_acc_11" class="text-danger text-left"></div>
                            </div>
                            <div class="col-sm-3 text-left">
                                <label class="control-label" style="margin-bottom: 0.4em;">
                                    <strong> สำรอง 1 <span class="text-danger"> * </span> </strong>
                                </label><br>
                                <coaComponent
                                    @coa="updateCoa"
                                    :set-name="defaultSetName.segment12"
                                    :set-data="segment12"
                                    :default-set-name="defaultSetName"
                                    :error="errors.segment12"
                                    :editFlag="false"
                                    placeholder=""
                                    ref="segment12"
                                >
                                </coaComponent>
                                <div id="_el_explode_acc_12" class="text-danger text-left"></div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3 text-left">
                                <label class="control-label" style="margin-bottom: 0.4em;">
                                    <strong> สำรอง 2 <span class="text-danger"> * </span> </strong>
                                </label><br>
                                <coaComponent
                                    @coa="updateCoa"
                                    :set-name="defaultSetName.segment13"
                                    :set-data="segment13"
                                    :default-set-name="defaultSetName"
                                    :error="errors.segment13"
                                    :editFlag="false"
                                    placeholder=""
                                    ref="segment13"
                                >
                                </coaComponent>
                                <div id="_el_explode_acc_13" class="text-danger text-left"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer pt-2">
                    <button type="button" class="btn btn-primary btn-sm" @click.private="confirm"
                        style="color: #fff; background-color: #01b471; border-color: #01b471;">
                        ตกลง
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" @click.private="cancel">
                        ยกเลิก
                    </button>
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
        props: ['reqLine', 'defaultSetName'],
        emits: ['updateAccount'],
        data() {
            return {
                line: this.reqLine,
                temp: '',
                loading: false,
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
                    segment13: false,
                },
                // Segment
                segment1: '', segment2: '', segment3: '', segment4: '', segment5: '', segment6: '',
                segment7: '', segment8: '', segment9: '', segment10: '', segment11: '', segment12: '', segment13: '',
            };
        },
        mounted() {
            this.extractAccount();
        },
        watch: {
            //
        },
        methods: {
            openModal(){
                this.temp = this.line.expense_account;
                $('#modal-account').modal('show');
                this.extractAccount();
            },
            confirm() {
                let vm = this;
                let errorMsg = '';
                let valid = true;
                var form = $('.account-form');
                vm.errors.segment1 = false;
                vm.errors.segment2 = false;
                vm.errors.segment3 = false;
                vm.errors.segment4 = false;
                vm.errors.segment5 = false;
                vm.errors.segment6 = false;
                vm.errors.segment7 = false;
                vm.errors.segment8 = false;
                vm.errors.segment9 = false;
                vm.errors.segment10 = false;
                vm.errors.segment11 = false;
                vm.errors.segment12 = false;
                vm.errors.segment13 = false;
                $(form).find("div[id='_el_explode_acc_1']").html("");
                $(form).find("div[id='_el_explode_acc_2']").html("");
                $(form).find("div[id='_el_explode_acc_3']").html("");
                $(form).find("div[id='_el_explode_acc_4']").html("");
                $(form).find("div[id='_el_explode_acc_5']").html("");
                $(form).find("div[id='_el_explode_acc_6']").html("");
                $(form).find("div[id='_el_explode_acc_7']").html("");
                $(form).find("div[id='_el_explode_acc_8']").html("");
                $(form).find("div[id='_el_explode_acc_9']").html("");
                $(form).find("div[id='_el_explode_acc_10']").html("");
                $(form).find("div[id='_el_explode_acc_11']").html("");
                $(form).find("div[id='_el_explode_acc_12']").html("");
                $(form).find("div[id='_el_explode_acc_13']").html("");
                
                if (vm.segment1 == '' || vm.segment1 == undefined) {
                    vm.errors.segment1 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุหน่วยเบิกจ่าย";
                    $(form).find("div[id='_el_explode_acc_1']").html(errorMsg);
                }
                if (vm.segment2 == '' || vm.segment2 == undefined) {
                    vm.errors.segment2 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุศูนย์ต้นทุน";
                    $(form).find("div[id='_el_explode_acc_2']").html(errorMsg);
                }
                if (vm.segment3 == '' || vm.segment3 == undefined) {
                    vm.errors.segment3 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุปีงบประมาณ";
                    $(form).find("div[id='_el_explode_acc_3']").html(errorMsg);
                }
                if (vm.segment4 == '' || vm.segment4 == undefined) {
                    vm.errors.segment4 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุแหล่งเงิน";
                    $(form).find("div[id='_el_explode_acc_4']").html(errorMsg);
                }
                if (vm.segment5 == '' || vm.segment5 == undefined) {
                    vm.errors.segment5 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุแผนงาน";
                    $(form).find("div[id='_el_explode_acc_5']").html(errorMsg);
                }
                if (vm.segment6 == '' || vm.segment6 == undefined) {
                    vm.errors.segment6 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุผลผลิต/แผนงานรอง/โครงการ";
                    $(form).find("div[id='_el_explode_acc_6']").html(errorMsg);
                }
                if (vm.segment7 == '' || vm.segment7 == undefined) {
                    vm.errors.segment7 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุกิจกรรม";
                    $(form).find("div[id='_el_explode_acc_7']").html(errorMsg);
                }
                if (vm.segment8 == '' || vm.segment8 == undefined) {
                    vm.errors.segment8 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุประเภทรายจ่าย";
                    $(form).find("div[id='_el_explode_acc_8']").html(errorMsg);
                }
                if (vm.segment9 == '' || vm.segment9 == undefined) {
                    vm.errors.segment9 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุรหัสงบประมาณ";
                    $(form).find("div[id='_el_explode_acc_9']").html(errorMsg);
                }
                if (vm.segment10 == '' || vm.segment10 == undefined) {
                    vm.errors.segment10 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุรหัสบัญชีหลัก";
                    $(form).find("div[id='_el_explode_acc_10']").html(errorMsg);
                }
                if (vm.segment11 == '' || vm.segment11 == undefined) {
                    vm.errors.segment11 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุรหัสบัญชีย่อย/รายจ่ายย่อย";
                    $(form).find("div[id='_el_explode_acc_11']").html(errorMsg);
                }
                if (vm.segment12 == '' || vm.segment12 == undefined) {
                    vm.errors.segment12 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุสำรอง 1";
                    $(form).find("div[id='_el_explode_acc_12']").html(errorMsg);
                }
                if (vm.segment13 == '' || vm.segment13 == undefined) {
                    vm.errors.segment13 = true;
                    valid = false;
                    errorMsg = "กรุณาระบุสำรอง 2";
                    $(form).find("div[id='_el_explode_acc_13']").html(errorMsg);
                }
                if (!valid) {
                    return;
                }

                if(this.temp){
                    $('#modal-account').modal('hide');
                    this.$emit("updateAccount", {expense_account: this.temp});
                    this.temp = null;
                }
            },
            cancel() {
                let vm = this;
                var form = $('.account-form');
                vm.errors.segment1 = false;
                vm.errors.segment2 = false;
                vm.errors.segment3 = false;
                vm.errors.segment4 = false;
                vm.errors.segment5 = false;
                vm.errors.segment6 = false;
                vm.errors.segment7 = false;
                vm.errors.segment8 = false;
                vm.errors.segment9 = false;
                vm.errors.segment10 = false;
                vm.errors.segment11 = false;
                vm.errors.segment12 = false;
                vm.errors.segment13 = false;
                $(form).find("div[id='_el_explode_acc_1']").html("");
                $(form).find("div[id='_el_explode_acc_2']").html("");
                $(form).find("div[id='_el_explode_acc_3']").html("");
                $(form).find("div[id='_el_explode_acc_4']").html("");
                $(form).find("div[id='_el_explode_acc_5']").html("");
                $(form).find("div[id='_el_explode_acc_6']").html("");
                $(form).find("div[id='_el_explode_acc_7']").html("");
                $(form).find("div[id='_el_explode_acc_8']").html("");
                $(form).find("div[id='_el_explode_acc_9']").html("");
                $(form).find("div[id='_el_explode_acc_10']").html("");
                $(form).find("div[id='_el_explode_acc_11']").html("");
                $(form).find("div[id='_el_explode_acc_12']").html("");
                $(form).find("div[id='_el_explode_acc_13']").html("");
                $('#modal-account').modal('hide');
                vm.temp = null;
            },
            setError(ref_name){
                let ref =  this.$refs[ref_name].$refs.referenceRef
                        ? this.$refs[ref_name].$refs.referenceRef.$refs.wrapperRef
                        : (this.$refs[ref_name].$refs.textareaRef
                            ? this.$refs[ref_name].$refs.textareaRef
                            : (this.$refs[ref_name].$refs.numeric
                                ? this.$refs[ref_name].$refs.numeric
                                : (this.$refs[ref_name].$refs.wrapperRef.$refs
                                    ? this.$refs[ref_name].$refs.wrapperRef.$refs.wrapperRef
                                    : this.$refs[ref_name].$refs.wrapperRef )));
                ref.style = "border: 1px solid red;";
            },
            resetError(ref_name){
                let ref = this.$refs[ref_name].$refs.referenceRef
                        ? this.$refs[ref_name].$refs.referenceRef.$refs.wrapperRef
                        : (this.$refs[ref_name].$refs.textareaRef
                            ? this.$refs[ref_name].$refs.textareaRef
                            : (this.$refs[ref_name].$refs.numeric
                                ? this.$refs[ref_name].$refs.numeric
                                : (this.$refs[ref_name].$refs.wrapperRef.$refs
                                    ? this.$refs[ref_name].$refs.wrapperRef.$refs.wrapperRef
                                    : this.$refs[ref_name].$refs.wrapperRef )));
                ref.style = "";
            },
            updateCoa(res){
                if (res.name == this.defaultSetName.segment1) { 
                    this.segment1 = res.segment1 == undefined? '': res.segment1;
                }
                if (res.name == this.defaultSetName.segment2) {
                    this.segment2 = res.segment2 == undefined? '': res.segment2; 
                }
                if (res.name == this.defaultSetName.segment3) {
                    this.segment3 = res.segment3 == undefined? '': res.segment3;
                }
                if (res.name == this.defaultSetName.segment4) {
                    this.segment4 = res.segment4 == undefined? '': res.segment4;
                }
                if (res.name == this.defaultSetName.segment5) {
                    this.segment5 = res.segment5 == undefined? '': res.segment5;
                }
                if (res.name == this.defaultSetName.segment6) {
                    this.segment6 = res.segment6 == undefined? '': res.segment6; 
                }
                if (res.name == this.defaultSetName.segment7) {
                    this.segment7 = res.segment7 == undefined? '': res.segment7;
                }
                if (res.name == this.defaultSetName.segment8) {
                    this.segment8 = res.segment8 == undefined? '': res.segment8;
                }
                if (res.name == this.defaultSetName.segment9) {
                    this.segment9 = res.segment9 == undefined? '': res.segment9;
                }
                if (res.name == this.defaultSetName.segment10) {
                    this.segment10 = res.segment10 == undefined? '': res.segment10;
                    this.segment11 = '';
                }
                if (res.name == this.defaultSetName.segment11) {
                    this.segment11 = res.segment11 == undefined? '': res.segment11;
                }
                if (res.name == this.defaultSetName.segment12) {
                    this.segment12 = res.segment12 == undefined? '': res.segment12;
                }
                if (res.name == this.defaultSetName.segment13) {
                    this.segment13 = res.segment13 == undefined? '': res.segment13;
                }

                let expenseAcc = this.segment1+'.'+this.segment2+'.'+this.segment3+'.'+this.segment4+'.'+this.segment5+'.'+this.segment6+'.'+this.segment7+'.'+this.segment8+'.'+this.segment9+'.'+this.segment10+'.'+this.segment11+'.'+this.segment12+'.'+this.segment13;
                this.temp = expenseAcc;
            },
            extractAccount(){
                var coa = this.line.expense_account.split('.');
                this.segment1 = coa[0];
                this.segment2 = coa[1];
                this.segment3 = coa[2];
                this.segment4 = coa[3];
                this.segment5 = coa[4];
                this.segment6 = coa[5];
                this.segment7 = coa[6];
                this.segment8 = coa[7];
                this.segment9 = coa[8];
                this.segment10 = coa[9];
                this.segment11 = coa[10];
                this.segment12 = coa[11];
                this.segment13 = coa[12];
            }
        }
    };
</script>

<style>
    .el-popper{
        z-index: 9999 !important;
    }
</style>