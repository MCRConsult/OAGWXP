@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('expense.requisition.index') }}"><strong> เอกสารส่งเบิก </strong></a>
    </li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <span class="d-inline">
                <h5> <strong> เอกสารส่งเบิก </strong> </h5>
            </div>
        </div>
    </div>

    <div class="card-body">
        <requisition-create-component
            reference-no="{{ $referenceNo }}"
            :invoice-types="{{ json_encode($invoiceTypes) }}"
        ></requisition-create-component>
    </div>
</div>
@endsection
