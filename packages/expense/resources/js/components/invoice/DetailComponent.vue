<template>
    <div>
        <button class="btn btn-outline-secondary" type="button" data-toggle="collapse" @click.prevent="openModal()">
            [...]
        </button>
        <div id="modal-detail" class="modal fade" aria-labelledby="myModalLabel" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> รายละเอียดเพิ่มเติม </h4>
                    </div>
                    <div class="modal-body pb-0">
                        <form id='detail-form'>
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
                            <i class="fa fa-check"></i> ตกลง
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import vehicleOilType from "../lov/vehicleOilType.vue";
    import utilityType from "../lov/UtilityType.vue";
    import utilityDetail from "../lov/UtilityDetail.vue";

    export default {
        components: {
            vehicleOilType, utilityType, utilityDetail
        },
        props: ['reqLine'],
        data() {
            return {
                line: this.reqLine,
                loading: false,
            };
        },
        mounted() {
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
            openModal(){
                $('#modal-detail').modal('show');
            },
            confirm(){
                $('#modal-detail').modal('hide');
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