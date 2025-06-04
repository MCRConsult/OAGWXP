<template>
    <div v-loading="loading">
        <div :class="showSearch? 'collapse show mb-2 ': 'collapse mb-2'" id="search_form">
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
                                <strong> วันที่เอกสาร </strong>
                            </label>
                            <div class="">
                                <input type="hidden" name="req_date" :value="search.req_date">
                                <el-date-picker
                                    v-model="req_date_input"
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

        <div class="card">
            <div class="card-header">
                <div class="row col-12" style="padding-right: 0px;">
                    <div class="col-md-6">
                        <span class="d-inline">
                            <h5> <strong> เอกสารส่งเบิก </strong> </h5>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="ibox float-e-margins">
                    <div class="table-responsive" style="max-height: 600px;">
                        <table class="table text-nowrap table-hover" style="position: sticky; font-size: 14px;">
                            <thead>
                                <tr>
                                    <th class="text-center sticky-col">
                                        <div style="width: 150px;"> เลขที่เอกสารส่งเบิก </div>
                                    </th>
                                    <th class="text-center sticky-col">
                                        <div style="width: 100px;"> วันที่เอกสาร </div>
                                    </th>
                                    <th class="text-center sticky-col">
                                        <div style="width: 150px;"> ผู้รับผิดชอบ </div>
                                    </th>
                                    <th class="text-center sticky-col">
                                        <div style="width: 120px;"> ประเภทการขอเบิก </div>
                                    </th>
                                    <th class="text-center sticky-col">
                                        <div style="width: 200px;"> คำอธิบาย </div>
                                    </th>
                                    <th class="text-center sticky-col">
                                        <div style="width: 120px;"> จำนวนเงิน </div>
                                    </th>
                                    <th class="text-center sticky-col">
                                        <div style="width: 60px;"> สถานะ </div>
                                    </th>
                                    <th class="text-center sticky-col">
                                        <div style="width: 60px;"> </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="requisition in requisitions">
                                    <tr>
                                        <td class="text-left text-nowrap" style="vertical-align: middle;">
                                            {{ requisition.req_number }}
                                            <template v-if="requisition.clear">
                                                <br><small style="font-weight: bold;"> เคลียร์เงินยืม : {{ requisition.clear.req_number }} </small>
                                            </template>
                                        </td>
                                        <td class="text-center text-nowrap" style="vertical-align: middle;">
                                            {{ requisition.req_date_format }}
                                        </td>
                                        <td class="text-center text-nowrap" style="vertical-align: middle;">
                                            {{ requisition.user.hr_employee.full_name }}
                                        </td>
                                        <td class="text-center  text-nowrap" style="vertical-align: middle;">
                                                {{ requisition.invoice_type.description }}
                                        </td>
                                        <td class="text-left" style="vertical-align: middle;">
                                            <div class="truncate" style="border-collapse: collapse; width: 200px;" :title="requisition.description">
                                                {{ requisition.description }}
                                            </div>
                                        </td>
                                        <td class="text-center text-nowrap" style="vertical-align: middle;">
                                            {{ numberFormat(requisition.total_amount) }}
                                        </td>
                                        <td class="text-center text-nowrap" style="vertical-align: middle;">
                                            <div v-html="requisition.status_icon"></div>
                                        </td>
                                        <td class="text-center text-nowrap" style="vertical-align: middle;">
                                            <div style="border-collapse: collapse; width: 120px; display:inline-block; flex-direction: row;">
                                                <template v-if="requisition.invoice
                                                    && requisition.status == 'COMPLETED'
                                                    && requisition.clear_reference_id == null || requisition.clear_reference_id == ''
                                                    && requisition.invoice_reference_id != ''
                                                    && requisition.invoice_type.lookup_code == 'PREPAYMENT'">
                                                    <template v-if="requisition.invoice.voucher_number">
                                                        <a class="btn btn-sm btn-danger active mr-1"
                                                            :href="'/expense/requisition/'+requisition.id+'/clear'">
                                                            เคลียร์เงินยืม
                                                        </a>
                                                    </template>
                                                </template>
                                                <template v-if="requisition.status == 'HOLD'">
                                                    <a class="btn btn-sm btn-light active mr-1"
                                                        :href="'/expense/requisition/'+requisition.id+'/hold'">
                                                        ตรวจสอบ
                                                    </a>
                                                </template>
                                                <template v-else>
                                                    <a class="btn btn-sm btn-light active mr-1"
                                                        :href="'/expense/requisition/'+requisition.id">
                                                        ตรวจสอบ
                                                    </a>
                                                </template>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="pull-right">
                            <el-pagination v-if="requisitions.length > 0"
                                background
                                :page-size="paginate.size"
                                :pager-count="25"
                                layout="prev, pager, next"
                                :total="paginate.total"
                                :current-page="currPage"
                                @current-change="handleChangePage">
                            </el-pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";
import numeral  from "numeral";
import lovRequisition from "./lov/Requisition.vue";

export default {
    props: ['pFormUrl', 'pSearch', 'pInvoiceTypes', 'pStatuses', 'pRequisitions'],
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
            requisitions: this.pRequisitions.data,
            currPage: 1,
            paginate: {
                size: 0,
                total: 0,
            },
        };
    },
    mounted() {
        this.search.req_number = this.pSearch.length <= 0? '' : this.pSearch.req_number;
        this.search.invoice_type = this.pSearch.length <= 0? '' : this.pSearch.invoice_type;
        this.req_date_input = this.req_date_input = this.pSearch && this.pSearch.req_date? moment(this.pSearch.req_date, 'YYYY-MM-DD'): '';
        this.changeDateFormat();
        this.search.status = this.pSearch.length <= 0? '' : this.pSearch.status;
        if (this.pSearch.length <= 0) {
            this.showSearch = true;
        }
        this.paginate = {
            size: this.pRequisitions.per_page,
            total: this.pRequisitions.total,
        }
    },
    watch: {
    },
    methods: {
        setRequisition(res) {
            this.search.req_number = res.requisition;
        },
        changeDateFormat() {
            this.search.req_date = '';
            if(this.req_date_input){
                const formattedDate = moment(this.req_date_input, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.search.req_date = formattedDate;
            }
        },
        numberFormat(value) {
            if (!value) return "0.00";
            return numeral(value).format("0,0.00");
        },
        async handleChangePage(page) {
          await this.fetchData(page);
        },
        async fetchData(page = 1) {
            const url = "/expense/api/requisition/fetch-render-page";
            this.loading = true;
            this.requisitions = [];
            await axios
            .post(url, {
                page: page
                , req_number: this.search.req_number
                , invoice_type: this.search.invoice_type
                , req_date: this.search.req_date
                , status: this.search.status
            })
            .then((res) => res.data)
            .then((res) => {
                this.paginate = {
                    size: res.requisitions.per_page,
                    total: res.requisitions.total,
                };
                this.requisitions = res.requisitions.data;
                this.currPage = page;
            })
            .catch((error) => {
                console.error(error);
            });
            this.loading = false;
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
    .card-body {
        -webkit-box-flex: 1;
        flex: 1 1 auto;
        padding: 1.25rem;
    }
    .truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
