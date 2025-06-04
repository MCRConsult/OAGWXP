<template>
    <div v-loading="loading">
        <div class="card-body" style="border: 2px solid #ddd; border-radius: 5px;">
            <form :action="pFormUrl">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label class="control-label">
                            <strong> ชื่อผู้ใช้งาน </strong>
                        </label>
                        <div class="">
                            <el-input v-model="search.username" name="username" :value="search.username" style="width: 100%;"/>
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
                    <div class="col-lg-2" style="margin-top: 8px;">
                        <div class="text-left">
                            <span> &nbsp; <br> </span>
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
            <table class="table text-nowrap table-hover text-center" style="position: sticky; font-size: 14px;">
                <thead>
                    <tr>
                        <th class="text-center sticky-col">
                            <div width="10%"> ชื่อผู้ใช้งาน </div>
                        </th>
                        <th class="text-center sticky-col">
                            <div width="8%"> ชื่อผู้ใช้งาน (ORACLE) </div>
                        </th>
                        <th class="text-center sticky-col">
                            <div width="10%"> องค์กร </div>
                        </th>
                        <th class="text-center sticky-col">
                            <div width="10%"> สถานที่ </div>
                        </th>
                        <th class="text-center sticky-col">
                            <div width="5%"> สถานะ </div>
                        </th>
                        <th class="text-center sticky-col">
                            <div width="5%"> </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="user in users">
                        <tr>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                {{ user.hr_employee.full_name }}
                            </td>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                {{ user.name }}
                            </td>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                {{ user.organization_v.name }}
                            </td>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                {{ user.location.location_code }}
                            </td>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                <template v-if="user.is_active == true">
                                    <i class="fa fa-check-circle text-primary"></i>
                                </template>
                                <template v-else>
                                    <i class="fa fa-circle text-secondary"></i>
                                </template>
                            </td>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                <a class="btn btn-sm btn-light active mr-1"
                                    :href="'/expense/settings/user/'+user.id">
                                    ตรวจสอบ
                                </a>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="pull-right">
                    <el-pagination v-if="users.length > 0"
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
export default {
    props: ['pFormUrl', 'pSearch', 'pStatuses', 'pUsers'],
    components: {
        //
    },
    data() {
        return {
            loading: false,
            search: {
                username: '',
                status: 'ACTIVE',
            },
            users: this.pUsers.data,
            currPage: 1,
            paginate: {
                size: 0,
                total: 0,
            },
        };
    },
    mounted() {
        this.search.username = this.pSearch.length <= 0? '' : this.pSearch.username;
        this.search.status = this.pSearch.length <= 0? 'ACTIVE' : this.pSearch.status;
        this.paginate = {
            size: this.pUsers.per_page,
            total: this.pUsers.total,
        }
    },
    methods: {
        async handleChangePage(page) {
          await this.fetchData(page);
        },
        async fetchData(page = 1) {
            const url = "/expense/api/settings/users/fetch-render-page";
            this.loading = true;
            this.users = [];
            await axios
            .post(url, {
                page: page,
                username: this.search.username,
                status: this.search.status
            })
            .then((res) => res.data)
            .then((res) => {
                this.paginate = {
                    size: res.users.per_page,
                    total: res.users.total,
                };
                this.users = res.users.data;
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
</style>
