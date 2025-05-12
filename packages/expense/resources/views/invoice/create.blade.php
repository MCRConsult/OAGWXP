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
                <h5> <strong> โปรดเลือกรายการเอกสารส่งเบิก เพื่อตรวจสอบรายการ </strong> </h5>
            </div>
        </div>
    </div>

    <div class="card-body">
        <invoice-create-component
            :invoice-types="{{ json_encode($invoiceTypes) }}"
        ></invoice-create-component>
    </div>
</div>
@endsection
