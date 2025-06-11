<template>
    <div v-loading="loading">
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
                            <strong> เลขที่ใบกำกับ </strong>
                        </label>
                        <div class="">
                            <input type="hidden" name="invoice_number" :value="search.invoice_number">
                            <lovVoucher
                                :setData="search.invoice_number"
                                :error="false"
                                :editFlag="true"
                                @setVoucher="setVoucher"
                            />
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <label class="control-label">
                            <strong> สถานะ </strong>
                        </label>
                        <div class="">
                            <input type="hidden" name="status" :value="search.status">
                            <el-select v-model="search.status" placeholder="">
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
                    <div class="col-lg-12" style="margin-top: 10px;">
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

        <div class="table-responsive mt-4" style="max-height: 600px;">
            <table class="table text-nowrap table-hover text-center" style="position: sticky;">
                <thead>
                    <tr>
                        <th class="text-center sticky-col">
                            <div width="3%"> สถานะ </div>
                        </th>
                        <th class="text-center sticky-col">
                            <div width="8%"> วันที่อินเตอร์เฟซ </div>
                        </th>
                        <th class="text-center sticky-col">
                            <div width="10%"> เลขที่ใบสำคัญ </div>
                        </th>
                        <th class="text-center sticky-col">
                            <div width="10%"> เลขที่ใบกำกับ </div>
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
                                <div v-html="inf.status_icon"></div>
                            </td>
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
                                {{ inf.interface_msg }}
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
import lovVoucher from "../lov/Voucher.vue";

export default {
    props: ['pFormUrl', 'pSearch', 'pStatuses', 'pInterfaces'],
    components: {
        lovVoucher
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
                status: 'All',
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
                                        : moment().format('YYYY-MM-DD');
        this.invoice_date_to_input = this.pSearch && this.pSearch.invoice_date_to
                                        ? moment(this.pSearch.invoice_date_to, 'YYYY-MM-DD')
                                        : moment().format('YYYY-MM-DD');
        this.changeDateFormat();
        //req_number
        this.search.invoice_number = this.pSearch.length <= 0? '' : this.pSearch.invoice_number;
        this.search.status = this.pSearch.length <= 0? 'All' : this.pSearch.status;
        this.paginate = {
            size: this.pInterfaces.per_page,
            total: this.pInterfaces.total,
        }
    },
    watch: {
    },
    methods: {
        setVoucher(res) {
            this.search.invoice_number = res.voucher;
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
        async handleChangePage(page) {
          await this.fetchData(page);
        },
        async fetchData(page = 1) {
            const url = "/expense/api/invoice/fetch-interface-render-page";
            this.loading = true;
            this.interfaces = [];
            await axios
            .post(url, {
                page: page,
                invoice_date_from: this.search.invoice_date_from,
                invoice_date_to: this.search.invoice_date_to,
                invoice_number: this.search.invoice_number,
                status: this.search.status
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
</style>
