<template>
    <div v-loading="loading">
        <div class="card-body">
            <form :action="pFormUrl">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label class="control-label">
                            <strong> วันที่จองงบประมาณ ตั้งแต่ </strong>
                        </label>
                        <input type="hidden" name="type" value="ENCUMBRANCE">
                        <input type="hidden" name="reserve_date_from" :value="search.reserve_date_from">
                        <el-date-picker
                            v-model="reserve_date_from_input"
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
                            <strong> วันที่จองงบประมาณ ถึง </strong>
                        </label>
                        <input type="hidden" name="reserve_date_to" :value="search.reserve_date_to">
                        <el-date-picker
                            v-model="reserve_date_to_input"
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
                            <strong> ประเภทการจองงบประมาณ </strong>
                        </label>
                        <div class="">
                            <input type="hidden" name="reserve_type" :value="search.reserve_type">
                            <el-select v-model="search.reserve_type" placeholder="" clearable>
                                <el-option
                                    v-for="(type, index) in reserveTypes"
                                    :key="index"
                                    :label="type.label"
                                    :value="type.value">
                                </el-option>
                            </el-select>
                        </div>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label">
                            <strong> สถานะ </strong>
                        </label>
                        <div class="">
                            <input type="hidden" name="status" :value="search.status">
                            <el-select v-model="search.status" placeholder="" clearable>
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
            <table class="table text-nowrap table-hover text-center table-striped" style="position: sticky; font-size: 13px;">
                <thead>
                    <tr>
                        <th class="text-center sticky-col">
                            <div width="10%"> วันที่การจองงบประมาณ </div>
                        </th>
                        <th class="text-center sticky-col">
                            <div width="8%"> ประเภทการจองงบประมาณ </div>
                        </th>
                        <th class="text-center sticky-col">
                            <div width="8%"> เลขที่การจองงบประมาณ </div>
                        </th>
                        <th class="text-center sticky-col">
                            <div width="4%"> สถานะ </div>
                        </th>
                        <th class="text-center sticky-col">
                            <div width="20%"> รายละเอียด </div>
                        </th>
                        <th class="text-center sticky-col">
                            <div width="4%"> </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="inf in interfaces">
                        <tr>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                {{ inf.reserve_date_format }}
                            </td>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                {{ inf.reserve_type == 'RESERVE'? 'จองงบประมาณ': 'คืนงบประมาณ' }}
                            </td>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                {{ inf.transaction_number }}
                                <div style="color: #858585;">
                                    <small> Batch#: {{ inf.batch_no }} </small>
                                </div>
                            </td>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                <div v-html="inf.status_icon"></div>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                {{ truncatedMessage(inf.error_msg) }}
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <button v-if="inf.reserve_status == 'E'" type="button"
                                    class="btn btn-sm btn-check"
                                    @click.prevent="handleReserve(inf.batch_no, inf.reserve_type)">
                                    ส่ง{{ inf.reserve_type == 'RESERVE'? 'จองงบประมาณ': 'คืนงบประมาณ' }}ใหม่
                                </button>
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
import Swal     from 'sweetalert2';

export default {
    props: ['pFormUrl', 'pSearch', 'pStatuses', 'pInterfaces'],
    data() {
        return {
            loading: false,
            reserveTypes: [{
                value: 'RESERVE',
                label: 'จองงบประมาณ'
            }, {
                value: 'UNRESERVE',
                label: 'คืนงบประมาณ'
            }],
            reserve_date_from_input: '',
            reserve_date_to_input: '',
            search: {
                reserve_date_from: '',
                reserve_date_to: '',
                reserve_type: '',
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
        //reserve_date
        this.reserve_date_from_input = this.pSearch && this.pSearch.reserve_date_from
                                        ? moment(this.pSearch.reserve_date_from, 'YYYY-MM-DD')
                                        : '';
        this.reserve_date_to_input = this.pSearch && this.pSearch.reserve_date_to
                                        ? moment(this.pSearch.reserve_date_to, 'YYYY-MM-DD')
                                        : '';
        this.changeDateFormat();
        //reserve_type
        this.search.reserve_type = this.pSearch.length <= 0? '' : this.pSearch.reserve_type;
        this.search.status = this.pSearch.length <= 0? 'All' : this.pSearch.status;
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
            this.search.reserve_type = res.invoice;
        },
        changeDateFormat() {
            this.search.reserve_date_from = '';
            this.search.reserve_date_to = '';
            if(this.reserve_date_from_input){
                const formattedDate = moment(this.reserve_date_from_input, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.search.reserve_date_from = formattedDate;
            }
            if(this.reserve_date_to_input){
                const formattedDate = moment(this.reserve_date_to_input, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.search.reserve_date_to = formattedDate;
            }
        },
        changeDateFormatFrom() {
            this.search.reserve_date_from = '';
            if(this.reserve_date_from_input){
                const formattedDate = moment(this.reserve_date_from_input, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.search.reserve_date_from = formattedDate;
                this.reserve_date_to_input = this.reserve_date_from_input;
                this.search.reserve_date_to = formattedDate;
            }
        },
        changeDateFormatTo() {
            this.search.reserve_date_to = '';
            if(this.reserve_date_to_input){
                const formattedDate = moment(this.reserve_date_to_input, "YYYY-MM-DD").format("YYYY-MM-DD");
                this.search.reserve_date_to = formattedDate;
                if(this.search.reserve_date_to < this.search.reserve_date_from){
                    this.reserve_date_from_input = this.reserve_date_to_input;
                    this.search.reserve_date_from = formattedDate;
                }
            }
        },
        async handleChangePage(page) {
          await this.fetchData(page);
        },
        async fetchData(page = 1) {
            const url = "/OAGWXP/api/interface/fetch-encumbrance-interface";
            this.loading = true;
            this.interfaces = [];
            await axios
            .post(url, {
                page: page,
                type: 'ENCUMBRANCE',
                reserve_date_from: this.search.reserve_date_from,
                reserve_date_to: this.search.reserve_date_to,
                reserve_type: this.search.reserve_type,
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
        async handleReserve(batch, reserveType) {
            var type = reserveType == 'RESERVE'? 'จองงบประมาณ': 'คืนงบประมาณ';
            await  Swal.fire({
                title: "ส่ง"+type,
                html: "ต้องการ <b>ยืนยัน</b> "+type+"ใช่หรือไม่?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ใช่",
                cancelButtonText: "ไม่",
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'ระบบกำลัง'+type,
                        type: "success",
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                    axios
                    .get('/OAGWXP/interface/'+batch+'/reserve')
                    .then(function (res) {
                        if (res.data.message) {
                            Swal.fire({
                                title: "มีข้อผิดพลาด",
                                text: res.data.message,
                                icon: "error",
                                showCancelButton: false,
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "ตกลง",
                                allowOutsideClick: false
                            });
                        } else {
                            Swal.fire({
                                title: type,
                                html: type+"เรียบร้อยแล้ว",
                                icon: "success",
                                showCancelButton: false,
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "ตกลง",
                                allowOutsideClick: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    setTimeout(function() {
                                        location.reload();
                                    }, 500);
                                }
                            });
                        }
                    })
                    .catch(err => {
                        let msg = err.response;
                        Swal.fire({
                            title: "มีข้อผิดพลาด",
                            text: msg.message,
                            icon: "error",
                            showCancelButton: false,
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "ตกลง",
                            allowOutsideClick: false
                        });
                    });
                }
            });
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
