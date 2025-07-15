@extends('layouts.app')
@section('title', 'ประวัติการอินเทอร์เฟซ')
@section('breadcrumb')
    <li class="breadcrumb-item active">
        <strong> ประวัติการอินเทอร์เฟซ </strong>
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row col-12" style="padding-right: 0px;">
                <div class="col-md-6">
                    <span class="d-inline">
                        <h5> <strong> ประวัติการอินเทอร์เฟซ </strong> </h5>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="ibox float-e-margins">
                <div class="col-md-12 mb-4">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->all() == null? 'active': (request()->type == 'ENCUMBRANCE'? 'active': '') }}"
                                data-toggle="tab" href="#encumbranceInterface" role="tab" aria-controls="encumbranceInterface" aria-selected="false">
                                <strong> ตรวจสอบงบประมาณ </strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->type == 'INVOICE'? 'active': ''}}"
                                data-toggle="tab" href="#invoiceInterface" role="tab" aria-controls="invoiceInterface" aria-selected="true">
                                <strong> ขอเบิก - ฎีกา </strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->type == 'JOURNAL'? 'active': '' }}"
                                data-toggle="tab" href="#journalInterface" role="tab" aria-controls="journalInterface" aria-selected="false">
                                <strong> ขอเบิก - ใบสำคัญ </strong>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane {{  request()->all() == null? 'active': (request()->type == 'ENCUMBRANCE'? 'active': '') }}"
                            id="encumbranceInterface" role="tabpanel">
                            <encumbrance-interface-log-component
                                p-form-url      = "{{ route('expense.interface-log') }}"
                                :p-search       = "{{ json_encode(request()->all()) }}"
                                :p-statuses     = "{{ json_encode($statusEncs) }}"
                                :p-interfaces   = "{{ json_encode($interfaceEncumbrances) }}"
                            ></encumbrance-interface-log-component>
                        </div>
                        <div class="tab-pane {{ (request()->type == 'INVOICE'? 'active': '') }}"  id="invoiceInterface" role="tabpanel">
                            <invoice-interface-log-component
                                p-form-url      = "{{ route('expense.interface-log') }}"
                                :p-search       = "{{ json_encode(request()->all()) }}"
                                :p-statuses     = "{{ json_encode($statuses) }}"
                                :p-interfaces   = "{{ json_encode($interfaceInvoices) }}"
                            ></invoice-interface-log-component>
                        </div>
                        <div class="tab-pane {{ request()->type == 'JOURNAL'? 'active': '' }}" id="journalInterface" role="tabpanel">
                            <journal-interface-log-component
                                p-form-url      = "{{ route('expense.interface-log') }}"
                                :p-search       = "{{ json_encode(request()->all()) }}"
                                :p-statuses     = "{{ json_encode($statuses) }}"
                                :p-interfaces   = "{{ json_encode($interfaceJournals) }}"
                            ></journal-interface-log-component>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

<style type="text/css">
    .sticky-col {
        position: sticky !important;
        background-color: #FFF;
        z-index: 9999;
        top:0px;
    }
</style>

@section('scripts')
@stop