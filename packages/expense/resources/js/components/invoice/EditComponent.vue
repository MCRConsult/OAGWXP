<template>
    <div>
        
        <div id="modal-edit" class="modal fade" aria-labelledby="myModalLabel" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> แก้ไขรายละเอียด </h4>
                    </div>
                    <div class="modal-body m-2">
                        <form id='edit-form'>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group" style="padding: 5px;">
                                        <label class="control-label" style="margin-bottom: 0.4rem;">
                                            <strong> ชื่อสั่งจ่าย <span class="text-danger"> * </span></strong> &nbsp;
                                        </label><br>
                                        <supplier
                                            :setData="tempData.supplier"
                                            :error="errors.supplier_detail"
                                            :editFlag="requisition.multiple_supplier == 'MORE'? true: false"
                                            @setSupplier="setSupplierLine"
                                        ></supplier>
                                        <div id="el_explode_supplier_detail" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" style="padding: 5px;">
                                        <label class="control-label">
                                            <strong> เลขที่บัญชีธนาคาร <span class="text-danger"> *</span></strong>
                                        </label><br>
                                        <supplierBank
                                            :parent="tempData.supplier"
                                            :setData="tempData.supplier_bank"
                                            :error="errors.supplier_bank"
                                            :editFlag="requisition.multiple_supplier == 'MORE'? true: false"
                                            @setSupplierBank="setSupplierBank"
                                        ></supplierBank>
                                        <div id="el_explode_supplier_bank" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" style="padding: 5px;">
                                        <label class="control-label">
                                            <strong> ประเภทค่าใช้จ่าย <span class="text-danger"> *</span></strong>
                                        </label><br>
                                        <!-- <paymentMethod
                                            :setData="tempData.expense_type"
                                            :error="errors.expense_type"
                                            :editFlag="true"
                                        ></paymentMethod> -->
                                        <div id="el_explode_expense_type" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" style="padding: 5px;">
                                        <label class="control-label">
                                            <strong> จำนวนเงิน <span class="text-danger"> *</span></strong>
                                        </label><br>
                                        <el-input v-model="tempData.amount" style="width: 100%;" placeholder=""/>
                                        <div id="el_explode_amount" class="text-danger text-left"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" style="padding: 5px;">
                                        <label class="control-label">
                                            <strong> คำอธิบายรายการ </strong>
                                        </label><br>
                                        <el-input v-model="tempData.description" type="textarea" :rows="1" style="width: 100%;" placeholder="" maxlength="240" show-word-limit/>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group" style="padding: 5px;">
                                        <label class="control-label">
                                            <strong> ทะเบียนรถยนต์ </strong>
                                        </label><br>
                                        <el-input v-model="line.vehicle_no" style="width: 100%;" placeholder=""/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" style="padding: 5px;">
                                        <label class="control-label">
                                            <strong> เลขที่กรมธรรม์ </strong>
                                        </label><br>
                                        <el-input v-model="line.policy_no" style="width: 100%;" placeholder=""/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" style="padding: 5px;">
                                        <label class="control-label">
                                            <strong> ประเภทน้ำมัน </strong>
                                        </label><br>
                                        <vehicleOilType
                                            :setData="line.vehicle_oil_type"
                                            :editFlag="true"
                                            @setVehicleOilType="setVehicleOilType"
                                        ></vehicleOilType>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group" style="padding: 5px;">
                                        <label class="control-label">
                                            <strong> ประเภทค่าสาธารณูปโภค </strong>
                                        </label><br>
                                        <utilityType
                                            :setData="line.utility_type"
                                            :editFlag="true"
                                            @setUtilityType="setUtilityType"
                                        ></utilityType>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" style="padding: 5px;">
                                        <label class="control-label">
                                            <strong> อาคาร/รหัสลูกค้า/ธพส. </strong>
                                        </label><br>
                                        <utilityDetail
                                            :parent="line.utility_type"
                                            :setData="line.utility_detail"
                                            :editFlag="true"
                                            @setUtilityDetail = "setUtilityDetail"
                                        ></utilityDetail>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" style="padding: 5px;">
                                        <label class="control-label">
                                            <strong> เลขที่ใบแจ้งหนี้ </strong>
                                        </label><br>
                                        <el-input v-model="line.invoice_no" style="width: 100%;" placeholder=""/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" style="padding: 5px;">
                                        <label class="control-label">
                                            <strong> วันที่ใบแจ้งหนี้ </strong>
                                        </label><br>
                                        <el-date-picker
                                            v-model="line.invoice_date"
                                            type="date"
                                            placeholder=""
                                            clearable
                                            format="DD-MM-YYYY"
                                            style="width: 100%;;"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group" style="padding: 5px;">
                                        <label class="control-label">
                                            <strong> จำนวนหน่วยที่ใช้ </strong>
                                        </label><br>
                                        <el-input v-model="line.unit_quantity" style="width: 100%;" placeholder=""/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" style="padding: 5px;">
                                        <label class="control-label">
                                            <strong> วันที่รับ </strong>
                                        </label><br>
                                        <el-date-picker
                                            v-model="line.receipt_date"
                                            type="date"
                                            placeholder=""
                                            clearable
                                            format="DD-MM-YYYY"
                                            style="width: 100%;"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" style="padding: 5px;">
                                        <label class="control-label">
                                            <strong> เลขที่หนังลือ </strong>
                                        </label><br>
                                        <el-input v-model="line.receipt_no" style="width: 100%;" placeholder=""/>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer pt-2">
                        <button type="button" class="btn btn-primary btn-sm" @click.private="confirm"
                            style="color: #fff; background-color: #01b471; border-color: #01b471;">
                            ตกลง
                        </button>
                        <button type="button" class="btn btn-warning btn-sm" @click.private="confirm">
                            ยกเลิก
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import supplier from "../lov/Supplier.vue";
    import supplierBank from "../lov/SupplierBank.vue";
    import vehicleOilType from "../lov/vehicleOilType.vue";
    import utilityType from "../lov/UtilityType.vue";
    import utilityDetail from "../lov/UtilityDetail.vue";

    export default {
        components: {
            supplier, supplierBank, vehicleOilType, utilityType, utilityDetail
        },
        props: ['requisition', 'reqLine'],
        data() {
            return {
                line: this.reqLine,
                loading: false,
                tempData: {},
                errors: {
                    supplier_detail: false,
                    supplier_bank: false,
                    expense_type: false,
                    amount: false,
                },
            };
        },
        mounted() {
            this.copyDataForEdit();
        },
        watch: {
            errors: {
                handler(val){
                    val.segmentOverride? this.setError('segmentOverride') : this.resetError('segmentOverride');
                },
                deep: true,
            },
        },
        methods: {
            copyDataForEdit() {
              // คัดลอกข้อมูลเดิมไปยัง tempData
              this.tempData = { ...this.reqLine };
            },
            saveEditedData() {
              // บันทึกค่าแก้ไขแทนที่ค่าเดิม
              if (this.tempData) {
                this.reqLine = { ...this.tempData };
                this.tempData = null; // รีเซ็ต tempData หลังบันทึก
              }
            },
            cancelEdit() {
              // ยกเลิกการแก้ไขและรีเซ็ต tempData
              this.tempData = null;
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
            confirm(){
                $('#modal-edit').modal('hide');
            },
            setSupplierLine(res){
                this.line.supplier = res.supplier;
                this.line.supplier_name = res.vendor_name;
            },
            setSupplierBank(res){
                this.line.supplier_bank = res.supplier_bank;
            },
            setVehicleOilType(res){
                this.line.vehicle_oil_type = res.vehicle_oil_type;
            },
            setUtilityType(res){
                this.line.utility_type = res.utility_type;
            },
            setUtilityDetail(res){
                this.line.utility_detail = res.utility_detail;
            },
        }
    };
</script>

<style>
    .el-popper{
        z-index: 9999 !important;
    }
</style>