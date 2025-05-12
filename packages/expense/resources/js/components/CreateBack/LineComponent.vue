<template>
    <div>
        <form id="create-form">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> รายการ <span class="text-danger"> *</span></strong>
                            </label><br>
                            <!-- <el-select v-model="requisition.invoice_type" placeholder="" style="width: 100%;" ref="invoice_type">
                                <el-option
                                    v-for="type in invoiceTypes"
                                    :key="type.lookup_code"
                                    :label="type.lookup_code"
                                    :value="type.lookup_code"
                                />
                            </el-select> -->
                            <div id="el_explode_invoice_type" class="text-danger text-left"></div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> รายการบัญชี <span class="text-danger"> *</span></strong>
                            </label><br>
                            <el-input name="segment_override" v-model="account" 
                                autocomplete="off" 
                                style="width: 100%"
                                readonly
                                data-toggle="modal" 
                                :data-target="'#modal-flexfield'"
                                data-backdrop="static"
                                data-keyboard="false"
                            > </el-input>
                        </div>
                    </div>
                    <!--  MODAL -->
                    <!-- <accountComp
                        :itemSegment="reqLine.item_segment"
                        :defaultValueSetName="defaultValueSetName"
                        @accountSegment="resAccount"
                    ></accountComp> -->
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label" style="margin-bottom: 0.4rem;">
                                <!-- เพิ่มเงื่อนไขเช็ค multi -->
                                <strong> ชื่อสั่งจ่าย <span v-if="true" class="text-danger"> * ระบุข้อมูลเฉพาะกรณีเลือกแบบหลายราย </span></strong> &nbsp;
                            </label><br>
                            <supplier
                                :setData="reqLine.supplier"
                                :error="errors.doc_category"
                                :editFlag="true"
                            ></supplier>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เลขที่บัญชีธนาคาร <span class="text-danger"> *</span></strong>
                            </label><br>
                            <!-- เพิ่มเงื่อนไขเช็ค multi -->
                            <supplierBank
                                :parent="reqLine.supplier"
                                :setData="reqLine.supplier_bank"
                                :error="errors.doc_category"
                                :editFlag="reqLine.supplier? true: false"
                            ></supplierBank>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> จำนวนเงิน <span class="text-danger"> *</span></strong>
                            </label><br>
                            <el-input v-model="reqLine.amount" style="width: 100%;" placeholder=""/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ทะเบียนรถ <span class="text-danger"> *</span></strong>
                            </label><br>
                            <paymentMethod
                                :setData="requisition.payment_method"
                                :error="errors.doc_category"
                                :editFlag="true"
                            ></paymentMethod>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เลขที่กรมธรรม์ <span class="text-danger"> *</span></strong>
                            </label><br>
                            <currency
                                :setData="requisition.currency"
                                :error="errors.doc_category"
                                :editFlag="true"
                            ></currency>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ประเภทน้ำมัน </strong>
                            </label><br>
                            <el-input v-model="requisition.contact_date" style="width: 100%;" placeholder=""/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ประเภทค่าสาธารณูปโภค </strong>
                            </label><br>
                            <yesnoType
                                :setData="requisition.final_judgment"
                                :error="errors.doc_category"
                                :editFlag="true"
                            ></yesnoType>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> อาคาร/รหัสลูกค้า/ธพส. </strong>
                            </label><br>
                            <el-input v-model="requisition.gfmis_number" style="width: 100%;" placeholder=""/>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เลขที่ใบเสร็จรับเงิน </strong>
                            </label><br>
                            <el-input v-model="requisition.note" style="width: 100%;" placeholder="" maxlength="150" show-word-limit/>
                        </div>
                    </div>
                </div>
                <div class="row">                        
                    <div class="col-md-9">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> คำอธิบาย </strong>
                            </label><br>
                            <el-input v-model="requisition.description" type="textarea" :rows="2" style="width: 100%;" placeholder="" maxlength="240" show-word-limit/>
                        </div>
                    </div>
                </div>
                <div align="center">
                    <button type="submit" class="btn btn-primary" @click.prevent="store()">บันทึก</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import accountComp from "../AccountComponent.vue";
    import supplier from "../../lov/Supplier.vue";
    import supplierBank from "../../lov/SupplierBank.vue";
    // import paymentType from "../lov/PaymentType.vue";
    // import paymentMethod from "../lov/PaymentMethod.vue";
    // import currency from "../lov/Currency.vue";
    // import yesnoType from "../lov/YesNoType.vue";

    export default {
        components: {
            accountComp, supplier, supplierBank
        },
        props: ['invoiceTypes'],
        data() {
            return {
                errors: {
                    invoice_type: false,
                    document_category: false,
                    req_date: false,
                    supplier: false,
                    supplier_bank: false,
                    invoice_number: false,
                    payment_type: false,
                    payment_method: false,
                    currency: false,
                },
                requisition: {},
                reqLine: {
                    item_category: '',
                    item_segment: '',
                    account: '',
                    supplier: '',
                    supplier_bank: '',
                    amount: '',
                    amount: '',
                    vehicle_no: '',
                    policy_no: '',
                    oil_type: '',
                    utility_type: '',
                    building_no: '',
                    receipt_no: '',
                    // invoice_number: '',
                    // payment_type: '',
                    // payment_method: '',
                    // currency: '',
                    // contact_date: '',
                    // final_judgment: '',
                    // gfmis_number: '',
                    // note: '',
                    description: '',
                },
                loading: false,
            };
        },
        computed: {
        },
        watch:{
            errors: {
                handler(val){
                    val.invoice_type? this.setError('invoice_type') : this.resetError('invoice_type');
                    // val.document_category? this.setError('document_category') : this.resetError('document_category');
                    // val.req_date? this.setError('req_date') : this.resetError('req_date');
                    // val.supplier? this.setError('supplier') : this.resetError('supplier');
                    // val.supplier_bank? this.setError('supplier_bank') : this.resetError('supplier_bank');
                    // val.invoice_number? this.setError('invoice_number') : this.resetError('invoice_number');
                    // val.payment_type? this.setError('payment_type') : this.resetError('payment_type');
                    // val.payment_method? this.setError('payment_method') : this.resetError('payment_method');
                    // val.currency? this.setError('currency') : this.resetError('currency');
                },
                deep: true,
            },
        },
        mounted(){
            //
        },
        methods: {
            setError(ref_name){
                let ref = this.$refs[ref_name].$refs.referenceRef 
                        ? this.$refs[ref_name].$refs.referenceRef.$refs.inputRef 
                        : (this.$refs[ref_name].$refs.textareaRef 
                            ? this.$refs[ref_name].$refs.textareaRef 
                            : (this.$refs[ref_name].$refs.inputRef.$refs 
                                ? this.$refs[ref_name].$refs.inputRef.$refs.inputRef 
                                : this.$refs[ref_name].$refs.wrapperRef ));
                ref.style = "border: 1px solid red;";
            },
            resetError(ref_name){
                let ref = this.$refs[ref_name].$refs.referenceRef 
                        ? this.$refs[ref_name].$refs.referenceRef.$refs.inputRef 
                        : (this.$refs[ref_name].$refs.textareaRef 
                            ? this.$refs[ref_name].$refs.textareaRef
                            : (this.$refs[ref_name].$refs.inputRef.$refs 
                                ? this.$refs[ref_name].$refs.inputRef.$refs.inputRef 
                                : this.$refs[ref_name].$refs.wrapperRef ));
                ref.style = "";
            },
            resAccount(res){
                console.log(res);
                // this.reqLine.account = res.account;
            },
        },
    }
</script>

<style type="text/css" scope>
    .el-select__wrapper {
        font-size: 12px;
    }
    .el-input__wrapper {
        font-size: 12px;
        /*padding: 0px;*/
    }
    /*.el-input {
      border: 1px solid red !important;
      border-radius: 5px;
    }*/
   /* .el-message {
        min-width: 1000px;
        z-index: 9999 !important;
    }
    .el-message--error {
        background-color: #E22427;
        border-color: #E22427;
    }
    .el-message--error .el-message__content {
        color: #ffffff;
        font-size: 20px;
        font-weight: bold;
    }
    .el-message .el-icon-error {
        color: #ffffff;
        font-size: 25px;
    }
    .el-message__closeBtn {
        color: #ffffff;
        font-weight: bold;
    }*/
</style>