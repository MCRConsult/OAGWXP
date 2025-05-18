@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('expense.invoice.index') }}"><strong> รายการเอกสารส่งเบิก </strong></a>
    </li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <span class="d-inline">
                <h5> <strong> เอกสารขอเบิก </strong> </h5>
            </div>
        </div>
    </div>

    <div class="card-body">
        <invoice-edit-component
            :invoice="{{ json_encode($invoice) }}"
            :invoice-types="{{ json_encode($invoiceTypes) }}"
            :default-set-name="{{ json_encode($defaultSetName) }}"
        ></invoice-edit-component>
    </div>
</div>
@endsection
