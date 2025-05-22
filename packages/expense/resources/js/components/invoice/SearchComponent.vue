<template>
    <div v-loading="loading">
        <div :class="showSearch? 'collapse show mb-2 ': 'collapse mb-2'" id="search_form">
            <div class="card-body tw-bg-yellow-200" style="border: 2px solid #ddd; border-radius: 5px;">
                <form :action="pFormUrl">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label class="control-label">
                                <strong> เลขที่ใบสำคัญ </strong>
                            </label>
                            <input type="hidden" name="voucher_number" :value="search.voucher_number">
                            <lovVoucher
                                :setData="search.voucher_number"
                                :error="false"
                                :editFlag="true"
                                @setVoucher="setVoucher"
                            />
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label">
                                <strong> ประเภทการขอเบิก </strong>
                            </label>
                            <input type="hidden" name="invoice_type" :value="search.invoice_type">
                            <el-select v-model="search.invoice_type" style="width: 100%" size="default" placeholder="" filterable>
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
                                <strong> วันที่เอกสารขอเบิก </strong>
                            </label>
                            <div class="">
                                <input type="hidden" name="invoice_date" :value="search.invoice_date">
                                <el-date-picker
                                    v-model="invoice_date_input"
                                    style="width: 100%"
                                    type="date"
                                    placeholder=""
                                    size="default"
                                    format="DD-MM-YYYY"
                                    @change="changeDateFormat"
                                />
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label">
                                <strong> สถานะ </strong>
                            </label>
                            <div class="">
                                <input type="hidden" name="status" :value="search.status">
                                <el-select v-model="search.status" style="width: 100%" size="default" placeholder="" filterable>
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
import moment from "moment";
import lovVoucher from "./lov/Voucher.vue";

export default {
    props: ['pFormUrl', 'pToken', 'pSearch', 'pInvoiceTypes', 'pStatuses', 'pDateJsFormat'],
    components: {
        lovVoucher
    },
    data() {
        return {
            loading: false,
            showSearch: false,
            invoice_date_input: '',
            search: {
                voucher_number: '',
                invoice_type: '',
                invoice_date: '',
                status: '',
            },
        };
    },
    mounted() {
        this.search.voucher_number = this.pSearch.length <= 0? '' : this.pSearch.voucher_number;
        this.search.invoice_type = this.pSearch.length <= 0? '' : this.pSearch.invoice_type;
        this.invoice_date_input = this.invoice_date_input = this.pSearch && this.pSearch.invoice_date
                                ? moment(this.pSearch.invoice_date, 'YYYY-MM-DD') : '';
        this.changeDateFormat();
        this.search.status = this.pSearch.length <= 0? '' : this.pSearch.status;
        if (this.pSearch.length <= 0) {
            this.showSearch = true;
        }
    },
    watch: {
    },
    methods: {
        setVoucher(res) {
            this.search.voucher_number = res.voucher;
        },
        changeDateFormat() {
            this.search.invoice_date = '';
            if(this.invoice_date_input){
                const formattedDate = moment(this.invoice_date_input, "DD-MM-YYYY").format("YYYY-MM-DD");
                this.search.invoice_date = formattedDate;
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
