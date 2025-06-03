@extends('layouts.app')
@section('title', 'เอกสารส่งเบิก')
@section('breadcrumb')
    <li class="breadcrumb-item active">
        <strong> เอกสารส่งเบิก </strong>
    </li>
    <div class="col-md-10" style="padding-right: 0px;">
        <div class="text-right m-t-lg" style="padding-right: 0px;">
            <a class="btn btn-white btn-md mr-2" data-toggle="collapse" href="#search_form" role="button" aria-expanded="false" aria-controls="requirement_form">
                <i class="fa fa-search"></i> ค้นหา
            </a>
            <a href="{{ route('expense.requisition.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> สร้างเอกสารส่งเบิก
            </a>
        </div>
    </div>
@endsection

@section('content')
    <requisition-component
        p-form-url          = "{{ route('expense.requisition.index') }}"
        :p-search           = "{{ json_encode(request()->all()) }}"
        :p-invoice-types    = "{{ json_encode($invoiceTypes) }}"
        :p-statuses         = "{{ json_encode($statuses) }}"
        {{-- DATA --}}
        :p-requisitions     = "{{ json_encode($requisitions) }}"
    ></requisition-component>
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