<template>
    <div v-loading="loading">
        <div class="card-body"> <!-- style="border: 2px solid #ddd; border-radius: 5px;" -->
            <form :action="pFormUrl">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label class="control-label">
                            <strong> วันที่เอกสารขอเบิก ตั้งแต่ </strong>
                        </label>
                        <input type="hidden" name="type" value="INVOICE">
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
                    <div class="form-group col-md-2">
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
                    <div class="form-group col-md-2">
                        <label class="control-label">
                            <strong> เลขที่ใบสำคัญ </strong>
                        </label>
                        <div class="">
                            <input type="hidden" name="voucher_number" :value="search.voucher_number">
                            <lovVoucher
                                :setData="search.voucher_number"
                                :error="false"
                                :editFlag="true"
                                @setVoucher="setVoucher"
                            />
                        </div>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label">
                            <strong> เลขที่ใบกำกับ </strong>
                        </label>
                        <div class="">
                            <input type="hidden" name="invoice_number" :value="search.invoice_number">
                            <lovInvoice
                                :setData="search.invoice_number"
                                :error="false"
                                :editFlag="true"
                                @setInvoice="setInvoice"
                            />
                        </div>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label">
                            <strong> สถานะ </strong>
                        </label>
                        <div class="">
                            <input type="hidden" name="invoice_status" :value="search.invoice_status">
                            <el-select v-model="search.invoice_status" placeholder="" clearable>
                                <el-option
                                    v-for="(status, index) in pStatuses"
                                    :key="index"
                                    :label="status"
                                    :value="index">
                                </el-option>
                            </el-select>
                        </div>
                    </div>
                    <div class="form-group col-md-2">
                        <span> &nbsp; <br> </span>
                        <div class="text-right mt-2">
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

        <div class="table-responsive mt-4" style="max-height: 600px;">
            <table class="table text-nowrap table-hover text-center" style="position: sticky; font-size: 13px;">
                <thead>
                    <tr>
                        <th class="text-center sticky-col">
                            <div width="10%"> วันที่อินเตอร์เฟส </div>
                        </th>
                        <th class="text-center sticky-col">
                            <div width="13%"> เลขที่ใบสำคัญ </div>
                        </th>
                        <th class="text-center sticky-col">
                            <div width="13%"> เลขที่ใบกำกับ </div>
                        </th>
                        <th class="text-center sticky-col">
                            <div width="4%"> สถานะ </div>
                        </th>
                        <th class="text-center sticky-col">
                            <div width="20%"> รายละเอียด </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="inf in interfaces">
                        <tr>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                {{ inf.invoice_date_format }}
                            </td>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                {{ inf.voucher_num }}
                            </td>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                {{ inf.invoice_num }}
                                <div style="color: #858585;">
                                    <small> Batch#: {{ inf.web_batch_no }} </small>
                                </div>
                            </td>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                <div v-html="inf.status_icon"></div>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                {{ truncatedMessage(inf.interface_msg) }}
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="pull-right">
                    <el-pagination v-if="interfaces.length > 0"
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
</template>

<script>
import moment from "moment";
import lovInvoice from "./lov/Invoice.vue";
import lovVoucher from "./lov/Voucher.vue";

export default {
    props: ['pFormUrl', 'pSearch', 'pStatuses', 'pInterfaces'],
    components: {
        lovInvoice, lovVoucher
    },
    data() {
        return {
            loading: false,
            invoice_date_from_input: '',
            invoice_date_to_input: '',
            search: {
                invoice_date_from: '',
                invoice_date_to: '',
                invoice_number: '',
                voucher_number: '',
                invoice_status: 'All',
            },
            interfaces: this.pInterfaces.data,
            currPage: 1,
            paginate: {
                size: 0,
                total: 0,
            },
        };
    },
    mounted() {
        //invoice_date
        this.invoice_date_from_input = this.pSearch && this.pSearch.invoice_date_from
                                        ? moment(this.pSearch.invoice_date_from, 'YYYY-MM-DD')
                                        : '';
        this.invoice_date_to_input = this.pSearch && this.pSearch.invoice_date_to
                                        ? moment(this.pSearch.invoice_date_to, 'YYYY-MM-DD')
                                        : '';
        this.changeDateFormat();
        //req_number
        this.search.invoice_number = this.pSearch.length <= 0? '' : this.pSearch.invoice_number;
        this.search.voucher_number = this.pSearch.length <= 0? '' : this.pSearch.voucher_number;
        this.search.invoice_status = this.pSearch.length <= 0? 'All' : this.pSearch.invoice_status;
        this.paginate = {
            size: this.pInterfaces.per_page,
            total: this.pInterfaces.total,
        }
    },
    watch: {
    },
    methods: {
        truncatedMessage(msg) {
            // Extract the first 20 characters
            return msg? msg.substring(0, 70): '';
        },
        setInvoice(res) {
            this.search.invoice_number = res.invoice;
        },
        setVoucher(res) {
            this.search.voucher_number = res.voucher;
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
            this.search.invoice_date_from = '';
            if(this.invoice_date_from_input){
                const formattedDate = moment(this.invoice_date_from_input, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.search.invoice_date_from = formattedDate;
                this.invoice_date_to_input = this.invoice_date_from_input;
                this.search.invoice_date_to = formattedDate;
            }
        },
        changeDateFormatTo() {
            this.search.invoice_date_to = '';
            if(this.invoice_date_to_input){
                const formattedDate = moment(this.invoice_date_to_input, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.search.invoice_date_to = formattedDate;
                if(this.search.invoice_date_to < this.search.invoice_date_from){
                    this.invoice_date_from_input = this.invoice_date_to_input;
                    this.search.invoice_date_from = formattedDate;
                }
            }
        },
        async handleChangePage(page) {
          await this.fetchData(page);
        },
        async fetchData(page = 1) {
            const url = "/OAGWXP/api/interface/fetch-invoice-interface";
            this.loading = true;
            this.interfaces = [];
            await axios
            .post(url, {
                page: page,
                type: 'INVOICE',
                invoice_date_from: this.search.invoice_date_from,
                invoice_date_to: this.search.invoice_date_to,
                invoice_number: this.search.invoice_number,
                voucher_number: this.search.voucher_number,
                invoice_status: this.search.invoice_status
            })
            .then((res) => res.data)
            .then((res) => {
                this.paginate = {
                    size: res.interfaces.per_page,
                    total: res.interfaces.total,
                };
                this.interfaces = res.interfaces.data;
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
    .truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
