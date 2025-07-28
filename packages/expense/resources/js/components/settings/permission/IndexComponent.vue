<template>
    <div v-loading="loading">
        <div class="table-responsive mt-4" style="max-height: 600px;">
            <table class="table text-nowrap table-hover text-center" style="position: sticky; font-size: 13px;">
                <thead>
                    <tr>
                        <th class="text-center sticky-col">
                            <div width="10%"> กลุ่มสิทธิ์เข้าถึงการใช้งาน </div>
                        </th>
                        <th class="text-center sticky-col">
                            <div width="8%"> สิทธิ์เข้าถึงการใช้งาน </div>
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
                    <template v-for="permission in permissions">
                        <tr>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                <span class="badge badge-primary"
                                    :style="'font-size: 12px; padding: 4px; background-color: #'+permission.perm_group_color+'; border-color: #'+permission.perm_group_color+';'">
                                    {{ permission.perm_group_title }}
                                </span>
                                <!-- {{ permission.perm_group_title }} -->
                            </td>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                {{ permission.description }}
                            </td>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                <template v-if="permission.is_active == true">
                                    <i class="fa fa-check-circle text-primary"></i>
                                </template>
                                <template v-else>
                                    <i class="fa fa-circle text-secondary"></i>
                                </template>
                            </td>
                            <td class="text-center text-nowrap" style="vertical-align: middle;">
                                <a class="btn btn-sm btn-check"
                                    :href="'/OAGWXP/settings/permission/'+permission.id">
                                    ตรวจสอบ
                                </a>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import moment from "moment";
export default {
    props: ['pFormUrl', 'pPermissions'],
    components: {
        //
    },
    data() {
        return {
            loading: false,
            permissions: this.pPermissions,
        };
    },
    mounted() {
        //
    },
    methods: {
        //
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
