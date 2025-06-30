@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('expense.requisition.index') }}"><strong> เอกสารส่งเบิก </strong></a>
    </li>
    <li class="breadcrumb-item active">
        <strong> รายการเอกสารเคลียร์เงินยืม </strong>
    </li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <span class="d-inline">
                <h5> <strong> เอกสารเคลียร์เงินยืม </strong> </h5>
            </div>
        </div>
    </div>

    <div class="card-body">
        <requisition-clear-edit-component
            :requisition="{{ json_encode($requisition) }}"
            :invoice-types="{{ json_encode($invoiceTypes) }}"
            :default-set-name="{{ json_encode($defaultSetName) }}"
        ></requisition-clear-edit-component>
    </div>
</div>
@endsection
