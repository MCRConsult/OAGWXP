@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('expense.requisition.index') }}"><strong> เอกสารส่งเบิก </strong></a>
    </li>
    <li class="breadcrumb-item active">
        <strong> รายการเอกสารส่งเบิก </strong>
    </li>
@endsection
@section('content')
    @if ($requisition->status == 'HOLD')
        <div class="alert alert-danger background-danger mt-2">
            <strong>{{ $requisition->hold_reason }}</strong>
        </div>
    @endif
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
            <requisition-hold-component
                :requisition="{{ json_encode($requisition) }}"
                :invoice-types="{{ json_encode($invoiceTypes) }}"
                :default-set-name="{{ json_encode($defaultSetName) }}"
            ></requisition-hold-component>
        </div>
    </div>
@endsection
