@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('expense.requisition.index') }}"><strong>BUDGET REQUISITION</strong></a>
    </li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <span class="d-inline">
                <h5> BUDGET REQUISITION </h5>
            </div>
        </div>
    </div>

    <div class="card-body">
        <requisition-header-component
            :invoice-types="{{ json_encode($invoiceTypes) }}"
        ></requisition-header-component>
    </div>
</div>
@endsection
