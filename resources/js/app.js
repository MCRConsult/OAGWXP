import "./bootstrap";
import { createApp } from "vue";


import ElementPlus from "element-plus";
import VueNumeric from '@handcrafted-market/vue3-numeric';
import "element-plus/dist/index.css";
// import '/node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css';
// import '/node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js';
import th from "element-plus/es/locale/lang/th";
// import ExpensePackage from '../../packages/expense/resources/js/app.js'

import $ from 'jquery';
// import 'popper.js';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'pace-progress/pace.min.js';
import 'perfect-scrollbar';
import '@coreui/coreui/dist/js/coreui.min.js';
import * as ElementPlusIconsVue from '@element-plus/icons-vue'
// import ElNotification from 'element-plus';

// COMPONENT
// REQUISITION
import RequisitionComponent from '/packages/expense/resources/js/components/requisition/IndexComponent.vue';
import CreateRequisitionComponent from '/packages/expense/resources/js/components/requisition/CreateComponent.vue';
// HOLD REQUISITION
import HoldRequisitionComponent from '/packages/expense/resources/js/components/requisition/hold/HoldComponent.vue';
// CLEAR REQUISITION
import ClearRequisitionComponent from '/packages/expense/resources/js/components/requisition/clear/ClearComponent.vue';

// INVOICE
import InvoiceComponent from '/packages/expense/resources/js/components/invoice/IndexComponent.vue';
import CreateInvoiceComponent from '/packages/expense/resources/js/components/invoice/CreateComponent.vue';
import EditInvoiceComponent from '/packages/expense/resources/js/components/invoice/EditComponent.vue';
import InterfaceInvoiceComponent from '/packages/expense/resources/js/components/invoice/interface-log/IndexComponent.vue';

import UserComponent from '/packages/expense/resources/js/components/settings/user/indexComponent.vue';
import ShowUserComponent from '/packages/expense/resources/js/components/settings/user/ShowComponent.vue';

import ReportComponent from '/packages/expense/resources/js/components/report/ReportComponent.vue';


const app = window.app = createApp({});

app.use(ElementPlus);
// app.use(ElNotification);

// the registered name
app.component("vue-numeric", VueNumeric)
app.component('requisition-component', RequisitionComponent);
app.component('requisition-create-component', CreateRequisitionComponent);
app.component('requisition-hold-component', HoldRequisitionComponent);
app.component('clear-requisition-component', ClearRequisitionComponent);

app.component('invoice-component', InvoiceComponent);
app.component('invoice-create-component', CreateInvoiceComponent);
app.component('invoice-edit-component', EditInvoiceComponent);
app.component('invoice-interface-component', InterfaceInvoiceComponent);

app.component('report-component', ReportComponent);

app.component('user-component', UserComponent);
app.component('user-show-component', ShowUserComponent);

// ## Register Component is here
app.mount("#app");
for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
    app.component(key, component)
  }
import.meta.glob(["../assets/**.*"]);