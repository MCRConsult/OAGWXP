<template>
    <div class="" v-loading="loading">
        <div :class="showSearch? ' collapse mb-2 show': ' collapse mb-2'" id="search_form" >
            <div class="card-body tw-bg-yellow-200" style="border: 2px solid #ddd; border-radius: 5px;">
                <form :action="pFormUrl">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label class="control-label">
                                <strong> เลขที่เอกสารส่งเบิก </strong>
                            </label>
                            <input type="hidden" name="req_number" :value="search.req_number">
                            <lovRequisition
                                :setData="search.req_number"
                                :error="false"
                                :editFlag="true"
                                @setRequisition="setRequisition"
                            />
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label">
                                <strong> ประเภทการขอเบิก </strong>
                            </label>
                            <input type="hidden" name="invoice_type" :value="search.invoice_type">
                            <el-select v-model="search.invoice_type" style="width: 100%" size="medium" placeholder="" filterable>
                                <el-option
                                    v-for="(type, index) in pInvoiceTypes"
                                    :key="index"
                                    :label="type.description"
                                    :value="type.lookup_code">
                                </el-option>
                            </el-select>
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label">
                                <strong> วันที่การขอเบิก </strong>
                            </label>
                            <div class="">
                                <input type="hidden" name="req_date" :value="search.req_date">
                                <el-date-picker
                                    v-model="req_date_input"
                                    style="width: 100%"
                                    type="date"
                                    placeholder=""
                                    size="medium"
                                    format="DD-MM-YYYY"
                                    @change="changeDateFormat"
                                />
                                <!-- <input id="req_date"
                                    autocomplete="off"
                                    :value="search.req_date"
                                    name="req_date"
                                    type="text"
                                    class="form-control"
                                    style="border: 1px solid #dcdfe6; "> -->
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label">
                                <strong> สถานะ </strong>
                            </label>
                            <div class="">
                                <input type="hidden" name="status" :value="search.status">
                                <el-select v-model="search.status" style="width: 100%" size="medium" placeholder="" filterable>
                                    <el-option
                                        v-for="(status, index) in pStatuses"
                                        :key="index"
                                        :label="status"
                                        :value="index">
                                    </el-option>
                                </el-select>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 5px;">
                        <div class="col-lg-12">
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary btn-sm m-1">
                                    ค้นหา
                                </button>
                                <a :href="pFormUrl" class="btn btn-warning btn-sm m-1">
                                    ล้างค่า
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import lovRequisition from "../lov/Requisition.vue";
import moment from "moment";

export default {
    props: ['pFormUrl', 'pToken', 'pSearch', 'pInvoiceTypes', 'pStatuses', 'pDateJsFormat'],
    components: {
        lovRequisition
    },
    data() {
        return {
            loading: false,
            showSearch: false,
            req_date_input: '',
            search: {
                req_number: '',
                invoice_type: '',
                req_date: '',
                status: '',
            },
        };
    },
    mounted() {
        this.search.req_number = this.pSearch.length <= 0? '' : this.pSearch.req_number;
        this.search.invoice_type = this.pSearch.length <= 0? '' : this.pSearch.invoice_type;
        this.req_date_input = this.pSearch.length <= 0? '' : (this.pSearch.req_date = ''? '': moment(new Date(this.pSearch.req_date)));
        // this.search.req_date = this.pSearch.length <= 0? '' : (this.pSearch.req_date = ''? '': moment(this.pSearch.req_date, 'YYYY-MM-DD'));
        this.changeDateFormat();
        this.search.status = this.pSearch.length <= 0? '' : this.pSearch.status;
        if (this.pSearch.length <= 0) {
            this.showSearch = true;
        }
        let vm = this;
        // $('#req_date').datepicker({
        //     format: this.pDateJsFormat,
        //     todayBtn: true,
        //     multidate: false,
        //     keyboardNavigation: false,
        //     autoclose: true,
        //     todayBtn: "linked"
        // }).on('changeDate', function(e) {
        //     vm.search.req_date = e.format();
        // });
    },
    watch: {
    },
    methods: {
        setRequisition(res) {
            this.search.req_number = res.requisition;
        },
        changeDateFormat() {
            const formattedDate = moment(this.req_date_input, "DD-MM-YYYY").format("YYYY-MM-DD");
            this.search.req_date = formattedDate;
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
