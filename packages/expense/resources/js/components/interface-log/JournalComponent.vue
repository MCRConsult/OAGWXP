<template>
    <div v-loading="loading">
        <div class="card-body">
            <form :action="pFormUrl">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label class="control-label">
                            <strong> วันที่เอกสารขอเบิก ตั้งแต่ </strong>
                        </label>
                        <input type="hidden" name="type" value="JOURNAL">
                        <input type="hidden" name="req_date_from" :value="search.req_date_from">
                        <el-date-picker
                            v-model="req_date_from_input"
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
                        <input type="hidden" name="req_date_to" :value="search.req_date_to">
                        <el-date-picker
                            v-model="req_date_to_input"
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
                            <strong> เลขที่เอกสารส่งเบิก </strong>
                        </label>
                        <div class="">
                            <input type="hidden" name="req_number" :value="search.req_number">
                            <lovRequisition
                                :sourceType="REQUISITION"
                                :setData="search.req_number"
                                :error="false"
                                :editFlag="true"
                                @setRequisition="setRequisition"
                            />
                        </div>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label">
                            <strong> สถานะ </strong>
                        </label>
                        <div class="">
                            <input type="hidden" name="journal_status" :value="search.journal_status">
                            <el-select v-model="search.journal_status" placeholder="" clearable>
                                <el-option
                                    v-for="(status, index) in pStatuses"
                                    :key="index"
                                    :label="status"
                                    :value="index">
                                </el-option>
                            </el-select>
                        </div>
                    </div>
                    <div class="form-group col-md-4">
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
                            <div width="13%"> เลขที่เอกสารส่งเบิก </div>
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
                                {{ inf.req_date_format }}
                            </td>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                {{ inf.reference2 }}
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
import lovRequisition from "./lov/Requisition.vue";

export default {
    props: ['pFormUrl', 'pSearch', 'pStatuses', 'pInterfaces'],
    components: {
        lovRequisition
    },
    data() {
        return {
            loading: false,
            req_date_from_input: '',
            req_date_to_input: '',
            search: {
                req_date_from: '',
                req_date_to: '',
                req_number: '',
                journal_status: 'All',
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
        //req_date
        this.req_date_from_input = this.pSearch && this.pSearch.req_date_from
                                        ? moment(this.pSearch.req_date_from, 'YYYY-MM-DD')
                                        : '';
        this.req_date_to_input = this.pSearch && this.pSearch.req_date_to
                                        ? moment(this.pSearch.req_date_to, 'YYYY-MM-DD')
                                        : '';
        this.changeDateFormat();
        //req_number
        this.search.req_number = this.pSearch.length <= 0? '' : this.pSearch.req_number;
        this.search.journal_status = this.pSearch.length <= 0? 'All' : this.pSearch.journal_status;
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
        setRequisition(res) {
            this.search.req_number = res.invoice;
        },
        changeDateFormat() {
            this.search.req_date_from = '';
            this.search.req_date_to = '';
            if(this.req_date_from_input){
                const formattedDate = moment(this.req_date_from_input, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.search.req_date_from = formattedDate;
            }
            if(this.req_date_to_input){
                const formattedDate = moment(this.req_date_to_input, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.search.req_date_to = formattedDate;
            }
        },
        changeDateFormatFrom() {
            this.search.req_date_from = '';
            if(this.req_date_from_input){
                const formattedDate = moment(this.req_date_from_input, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.search.req_date_from = formattedDate;
                this.req_date_to_input = this.req_date_from_input;
                this.search.req_date_to = formattedDate;
            }
        },
        changeDateFormatTo() {
            this.search.req_date_to = '';
            if(this.req_date_to_input){
                const formattedDate = moment(this.req_date_to_input, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.search.req_date_to = formattedDate;
                if(this.search.req_date_to < this.search.req_date_from){
                    this.req_date_from_input = this.req_date_to_input;
                    this.search.req_date_from = formattedDate;
                }
            }
        },
        async handleChangePage(page) {
          await this.fetchData(page);
        },
        async fetchData(page = 1) {
            const url = "/expense/api/interface/fetch-journal-interface";
            this.loading = true;
            this.interfaces = [];
            await axios
            .post(url, {
                page: page,
                req_date_from: this.search.req_date_from,
                req_date_to: this.search.req_date_to,
                req_number: this.search.req_number,
                journal_status: this.search.journal_status
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
