<template>
    <div v-loading="loading">
        <div id="search_form">
            <div class="card-body" style="border: 2px solid #ddd; border-radius: 5px;">
                <form :action="pFormUrl">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label class="control-label">
                                <strong> วันที่เอกสารขอเบิก ตั้งแต่ </strong>
                            </label>
                            <input type="hidden" name="invoice_date_from" :value="search.invoice_date_from">
                            <el-date-picker
                                v-model="invoice_date_from_input"
                                style="width: 100%"
                                type="date"
                                placeholder=""
                                size="default"
                                format="DD-MM-YYYY"
                                @change="changeDateFormatFrom"
                            />
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label">
                                <strong> วันที่เอกสารขอเบิก ถึง </strong>
                            </label>
                            <input type="hidden" name="invoice_date_to" :value="search.invoice_date_to">
                            <el-date-picker
                                v-model="invoice_date_to_input"
                                style="width: 100%"
                                type="date"
                                placeholder=""
                                size="default"
                                format="DD-MM-YYYY"
                                @change="changeDateFormatTo"
                            />
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label">
                                <strong> เลขที่เอกสารส่งเบิก ตั้งแต่ </strong>
                            </label>
                            <div class="">
                                <input type="hidden" name="req_number_from" :value="search.req_number_from">
                                <lovRequisition
                                    :setData="search.req_number_from"
                                    :error="false"
                                    :editFlag="true"
                                    @setRequisition="setRequisitionFrom"
                                />
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label">
                                <strong> เลขที่เอกสารส่งเบิก ถึง </strong>
                            </label>
                            <div class="">
                                <input type="hidden" name="req_number_to" :value="search.req_number_to">
                                <lovRequisition
                                    :setData="search.req_number_to"
                                    :error="false"
                                    :editFlag="true"
                                    @setRequisition="setRequisitionTo"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 5px;">
                        <div class="form-group col-md-3">
                            <label class="control-label">
                                <strong> ชื่อสั่งจ่าย ตั้งแต่ </strong>
                            </label>
                            <div class="">
                                <input type="hidden" name="supplier_from" :value="search.supplier_from">
                                <lovSupplier
                                    :setData="search.supplier_from"
                                    :error="false"
                                    :editFlag="true"
                                    @setSupplier="setSupplierFrom"
                                />
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label">
                                <strong> ชื่อสั่งจ่าย ถึง </strong>
                            </label>
                            <div class="">
                                <input type="hidden" name="supplier_to" :value="search.supplier_to">
                                <lovSupplier
                                    :setData="search.supplier_to"
                                    :error="false"
                                    :editFlag="true"
                                    @setSupplier="setSupplierTo"
                                />
                            </div>
                        </div>
                        <div class="col-lg-6" style="margin-top: 10px;">
                            <div class="text-right">
                                <span> &nbsp; <br> </span>
                                <button type="submit" class="btn btn-success btn-sm m-1">
                                    พิมพ์รายงาน
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";
import lovRequisition from "./lov/Requisition.vue";
import lovSupplier from "./lov/Supplier.vue";

export default {
    props: ['pFormUrl', 'pToken', 'pSearch'],
    components: {
        lovRequisition, lovSupplier
    },
    data() {
        return {
            loading: false,
            invoice_date_from_input: '',
            invoice_date_to_input: '',
            search: {
                invoice_date_from: '',
                invoice_date_to: '',
                req_number_from: '',
                req_number_to: '',
                supplier_from: '',
                supplier_to: '',
            },
        };
    },
    mounted() {
        //invoice_date
        this.invoice_date_from_input = this.pSearch && this.pSearch.invoice_date_from
                                        ? moment(this.pSearch.invoice_date_from, 'YYYY-MM-DD')
                                        : moment().format('YYYY-MM-DD');
        this.invoice_date_to_input = this.pSearch && this.pSearch.invoice_date_to
                                        ? moment(this.pSearch.invoice_date_to, 'YYYY-MM-DD')
                                        : moment().format('YYYY-MM-DD');
        this.changeDateFormat();
        //req_number
        this.search.req_number_from = this.pSearch.length <= 0? '' : this.pSearch.req_number_from;
        this.search.req_number_to = this.pSearch.length <= 0? '' : this.pSearch.req_number_to;
        //supplier
        this.search.supplier_from = this.pSearch.length <= 0? '' : this.pSearch.supplier_from;
        this.search.supplier_to = this.pSearch.length <= 0? '' : this.pSearch.supplier_to;
    },
    watch: {
    },
    methods: {
        setRequisitionFrom(res) {
            this.search.req_number_from = res.requisition;
            this.search.req_number_to = res.requisition;
        },
        setRequisitionTo(res) {
            this.search.req_number_to = res.requisition;
        },
        setSupplierFrom(res){
            this.search.supplier_from = res.supplier;
            this.search.supplier_to = res.supplier;
        },
        setSupplierTo(res){
            this.search.supplier_to = res.supplier;
        },
        changeDateFormat() {
            this.search.invoice_date_from = '';
            this.search.invoice_date_to = '';
            if(this.invoice_date_from_input){
                const formattedDate = moment(this.invoice_date_from_input, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.search.invoice_date_from = formattedDate;
            }
            if(this.invoice_date_to_input){
                const formattedDate = moment(this.invoice_date_to_input, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.search.invoice_date_to = formattedDate;
            }
        },
        changeDateFormatFrom() {
            if(this.invoice_date_from_input){
                const formattedDate = moment(this.invoice_date_from_input, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.search.invoice_date_from = formattedDate;
                this.invoice_date_to_input = this.invoice_date_from_input;
                this.search.invoice_date_to = formattedDate;
            }
        },
        changeDateFormatTo() {
            if(this.invoice_date_to_input){
                const formattedDate = moment(this.invoice_date_to_input, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.search.invoice_date_to = formattedDate;
                if(this.search.invoice_date_to < this.search.invoice_date_from){
                    this.invoice_date_from_input = this.invoice_date_to_input;
                    this.search.invoice_date_from = formattedDate;
                }
            }
        },
    }
};
</script>

<style type="text/css" scope>
    .el-select-dropdown{
        z-index: 9999 !important;
    }

    .el-notification {
        z-index: 9999 !important;
    }

    .tw-bg-yellow-200 {
        background-color: #fefcbf;
    }
    .tw-border-b {
        border-bottom-width: 1px !important;
    }

    .card-body {
        -webkit-box-flex: 1;
        flex: 1 1 auto;
        padding: 1.25rem;
    }
</style>
