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
        <requisition-clear-component
            :requisition="{{ json_encode($requisition) }}"
            :clear-req="{{ json_encode($clearReq) }}"
        ></requisition-clear-component>
    </div>
</div>
@endsection
