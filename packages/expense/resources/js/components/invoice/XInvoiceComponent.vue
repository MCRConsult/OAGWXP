<template>
    <div>
        <form id="create-form">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เลขที่ใบสำคัญ </strong>
                            </label><br>
                            <el-input v-model="requisition.invoice_number" style="width: 100%;" placeholder="" disabled/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ผู้รับผิดชอบ </strong>
                            </label><br>
                            <el-input v-model="requisition.create_by" style="width: 100%;" placeholder="" disabled/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> สถานะ </strong>
                            </label><br>
                            <el-input v-model="requisition.status" style="width: 100%;" placeholder="" disabled/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ประเภท </strong>
                            </label><br>
                            <el-input v-model="requisition.create_by" style="width: 100%;" placeholder="" disabled/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> สำนักงานผู้เบิกจ่าย </strong>
                            </label><br>
                            <el-select v-model="requisition.invoice_type" placeholder="" style="width: 100%;" ref="invoice_type">
                                <el-option
                                    v-for="type in invoiceTypes"
                                    :key="type.lookup_code"
                                    :label="type.lookup_code"
                                    :value="type.lookup_code"
                                />
                            </el-select>
                            <div id="el_explode_invoice_type" class="text-danger text-left"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> วันที่เอกสารขอเบิก <span class="text-danger"> *</span></strong>
                            </label><br>
                            <el-date-picker
                                ref="req_date"
                                v-model="requisition.req_date"
                                type="date"
                                placeholder=""
                                clearable
                                format="DD-MM-YYYY"
                                style="width: 100%;;"
                            />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">
                            <strong> ชื่อสั่งจ่าย </strong>
                        </label><br>
                        <el-input v-model="requisition.create_by" style="width: 100%;" placeholder="" disabled/>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เลขที่ใบกำกับ </strong>
                            </label><br>
                            <el-input v-model="requisition.create_by" style="width: 100%;" placeholder="" disabled/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> สกุลเงิน <span class="text-danger"> *</span></strong>
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
                                <strong> วิธีการจ่ายเงิน <span class="text-danger"> *</span></strong>
                            </label><br>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เทอมการชำระเงิน <span class="text-danger"> *</span></strong>
                            </label><br>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> วันที่เคลียร์เงินยืม <span class="text-danger"> *</span></strong>
                            </label><br>
                        </div>
                        <el-date-picker
                            ref="req_date"
                            v-model="requisition.req_date"
                            type="date"
                            placeholder=""
                            clearable
                            format="DD-MM-YYYY"
                            style="width: 100%;;"
                        />
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> วันที่สัญญายืมเงิน <span class="text-danger"> *</span></strong>
                            </label><br>
                        </div>
                        <el-date-picker
                            ref="req_date"
                            v-model="requisition.req_date"
                            type="date"
                            placeholder=""
                            clearable
                            format="DD-MM-YYYY"
                            style="width: 100%;;"
                        />
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ใบโอนล้างถึงที่สุด (บอ.) </strong>
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
                                <strong> เลขที่เอกสาร JV/KL (GFMIS) </strong>
                            </label><br>
                            <el-input v-model="requisition.gfmis_number" style="width: 100%;" placeholder=""/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> คำอธิบาย </strong>
                            </label><br>
                            <el-input v-model="requisition.description" type="textarea" :rows="2" style="width: 100%;" placeholder="" maxlength="240" show-word-limit/>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> หมายเหตุ </strong>
                            </label><br>
                            <el-input v-model="requisition.note" style="width: 100%;" placeholder="" maxlength="150" show-word-limit/>
                        </div>
                    </div>                    
                </div>
            </div>
        </form>
        <br>
        <!-- TABLE LINE LISTS-->
        <table class="table table-responsive-sm">
            <thead>
                <tr>
                    <th class="text-center" width="3%"> รายการที่ </th>
                    <th class="text-center" width="15%"> ประเภทค่าใช้จ่าย </th>
                    <th class="text-center" width="15%"> รายการบัญชี </th>
                    <th class="text-center" width="10%"> จำนวนเงิน </th>
                    <th class="text-center" width="15%"> ชื่อสั่งจ่าย </th>
                    <th class="text-center" width="15%"> เลขที่บัญชีธนาคาร </th>
                    <th class="text-center" width="3%"> </th>
                </tr>
            </thead>
            <tbody>
                <listComp
                    v-for="(row, index) in linelists"
                    :key="index"
                    :index="index"
                    :attribute="row"
                    :requisition="requisition"
                    @copyRow="copyRow"
                    @removeRow="removeRow"
                >
                </listComp>
            </tbody>
        </table>
    </div>
</template>

<script>
    import moment from "moment";
    import documentCategory from "../../lov/DocumentCategory.vue";
    import supplier from "../../lov/Supplier.vue";
    import supplierBank from "../../lov/SupplierBank.vue";
    import paymentType from "../../lov/PaymentType.vue";
    import paymentMethod from "../../lov/PaymentMethod.vue";
    import currency from "../../lov/Currency.vue";
    import yesnoType from "../../lov/YesNoType.vue";
    import detailComp from "./DetailComponent.vue";
    import listComp from "./ListComponent.vue";

    export default {
        components: {
            documentCategory, supplier, supplierBank, paymentType, paymentMethod, currency, yesnoType, detailComp, listComp
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
                invHeader: {
                    invoice_type: 'STANDARD',
                    document_category: '',
                    req_date: moment().format('DD-MM-YYYY'),
                    status: '',
                    req_number: '',
                    create_by: '',
                    supplier: '',
                    supplier_bank: '',
                    invoice_number: '',
                    payment_type: 'PAYMENT',
                    payment_method: '',
                    currency: 'THB',
                    contact_date: '',
                    final_judgment: '',
                    gfmis_number: '',
                    note: '',
                    description: '',
                    more_supplier: ''
                },
                invLine: {
                    supplier: '',
                    supplier_name: '',
                    supplier_bank: '',
                    expense_type: 'test', //alias or item cate
                    amount: '',
                    description: '',
                    vehicle_number: '',
                    policy_number: '',
                    vehicle_oil_type: '',
                    utility_type: '',
                    utility_detail: '',
                    unit_quantity: '',
                    invoice_number: '',
                    invoice_date: '',
                    receipt_number: '',
                    receipt_date: '',
                },
                loading: false,
                linelists: [],
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
            // console.log('ssss');
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
            chooseSupplier(){
                let vm = this;
                let checked = $('input[name="more_supplier"]').prop('checked');
                if(checked){
                    vm.requisition.more_supplier = true;
                }else{
                    vm.requisition.more_supplier = false;
                }
            },
            async store(){
                var vm = this;
                var form = $('#create-form');
                let errorMsg = '';
                // this.resetValues(form, errorMsg);
                let valid = true;
                if (vm.requisition.invoice_type == '') {
                    vm.errors.invoice_type = true;
                    valid = false;
                    errorMsg = "กรุณาเลือกประเภท";
                    $(form).find("div[id='el_explode_invoice_type']").html(errorMsg);
                }
                // ------------------------------------------------

                if (!valid) {
                    return;
                }
                // vm.loading = true;
                // let url = vm.url.ajax_customer_store;
                // let formData = new FormData();
                // // JSON
                // formData.append("requester", vm.requester);
                // formData.append("trade_type", vm.trade_type);
                // formData.append("select_org", JSON.stringify(vm.selectOrg));
                // formData.append("cust_head", JSON.stringify(vm.custHead));
                // formData.append("bill_to", JSON.stringify(vm.billTo));
                // formData.append("ship_to", JSON.stringify(vm.shipTo));
                // formData.append("general", JSON.stringify(vm.general));
                // formData.append("financial", JSON.stringify(vm.financial));
                // formData.append("detail", JSON.stringify(vm.detail));

                // POST METHOD
                axios.post(url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function (res) {
                    vm.loading = false;
                    if (res.data.data.error_msg) {
                        vm.attachments = [];
                        $(form).find("div[id='el_explode_attach']").html('');
                        vm.$message({
                            showClose: true,
                            message: res.data.data.error_msg,
                            type: 'error',
                            offset: 30,
                            duration: 0
                        });
                    } else {
                        setTimeout(function() {
                            location.href = res.data.data.redirect_url;
                        }, 500);
                    }
                }.bind(vm))
                .catch(err => {
                    let msg = err.response.data;
                    this.$message({
                        showClose: true,
                        message: msg.message,
                        type: 'error',
                        offset: 30,
                        duration: 0
                    });
                })
                .then(() => {
                    vm.loading = false;
                });
            },
            copyRow(index) {
                const copyLine = JSON.parse(JSON.stringify(this.linelists[index]));
                this.linelists.push(JSON.parse(JSON.stringify(copyLine)));
            },
            removeRow(index) {
                this.linelists.splice(index, 1);
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