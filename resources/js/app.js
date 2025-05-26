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
import SearchRequisitionComponent from '/packages/expense/resources/js/components/requisition/SearchComponent.vue';
import CreateRequisitionComponent from '/packages/expense/resources/js/components/requisition/CreateComponent.vue';

import SearchInvoiceComponent from '/packages/expense/resources/js/components/invoice/SearchComponent.vue';
import CreateInvoiceComponent from '/packages/expense/resources/js/components/invoice/CreateComponent.vue';
import EditInvoiceComponent from '/packages/expense/resources/js/components/invoice/EditComponent.vue';

import ReportComponent from '/packages/expense/resources/js/components/report/ReportComponent.vue';


const app = window.app = createApp({});

app.use(ElementPlus);
// app.use(ElNotification);

// the registered name
app.component("vue-numeric", VueNumeric)
app.component('requisition-search-component', SearchRequisitionComponent);
app.component('requisition-create-component', CreateRequisitionComponent);

app.component('invoice-search-component', SearchInvoiceComponent);
app.component('invoice-create-component', CreateInvoiceComponent);
app.component('invoice-edit-component', EditInvoiceComponent);

app.component('report-component', ReportComponent);

// ## Register Component is here
app.mount("#app");
for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
    app.component(key, component)
  }
import.meta.glob(["../assets/**.*"]);