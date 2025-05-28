@extends('layouts.app')
@section('title', 'เอกสารส่งเบิก')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('expense.requisition.index') }}"><strong> เอกสารส่งเบิก </strong></a>
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
<style type="text/css">
    .sticky-col {
        position: sticky !important;
        background-color: #FFF;
        z-index: 9999;
        top:0px;
    }
</style>


@section('content')
    <requisition-search-component
        p-form-url          = "{{ route('expense.requisition.index') }}"
        p-token             = "{{ csrf_token() }}"
        :p-search           = "{{ json_encode(request()->all()) }}"
        :p-invoice-types    = "{{ json_encode($invoiceTypes) }}"
        :p-statuses         = "{{ json_encode($statuses) }}"
        p-date-js-format    = "{{ trans('date.js-format') }}"
    ></requisition-search-component>

<div class="card">
    <div class="card-header">
        <div class="row col-12" style="padding-right: 0px;">
            <div class="col-md-6">
                <span class="d-inline">
                    <h5> <strong> เอกสารส่งเบิก </strong> </h5>
                </span>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="ibox float-e-margins">
            <div class="table-responsive" style="max-height: 600px;">
                <table class="table text-nowrap table-hover" style="position: sticky;">
                    <thead>
                        <tr>
                            <th class="text-center sticky-col">
                                <div style="width: 150px;"> เลขที่เอกสารส่งเบิก </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div style="width: 100px;"> วันที่เอกสาร </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div style="width: 150px;"> ผู้รับผิดชอบ </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div style="width: 120px;"> ประเภทการขอเบิก </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div style="width: 200px;"> คำอธิบาย </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div style="width: 120px;"> จำนวนเงิน </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div style="width: 60px;"> สถานะ </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div style="width: 10px;"> </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requisitions as $requisition)
                            <tr>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    {{ $requisition->req_number }}
                                </td>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    {{ $requisition->req_date_format }}
                                </td>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    {{ $requisition->user->hrEmployee->last_name }}
                                </td>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    {{ $requisition->invoiceType->description }}
                                </td>
                                <td class="text-left text-nowrap" style="vertical-align: middle;">
                                    {{ $requisition->description }}
                                </td>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    {{ number_format($requisition->lines->sum('amount'), 2) }}
                                </td>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    {!! $requisition->getStatusIcon() !!}
                                </td>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    <div style="border-collapse: collapse; width: 160px; display:inline-block; flex-direction: row;">
                                        <a class="btn btn-sm btn-light active mr-1"
                                            href="{{ route('expense.requisition.show', $requisition->id) }}">
                                            ตรวจสอบ
                                        </a>
                                        @if ($requisition->invoice_type == 'PREPAYMENT')
                                            <a class="btn btn-sm btn-danger active"
                                                href="{{ route('expense.requisition.show', $requisition->id) }}">
                                                เคลียร์เงินยืม
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="pull-right">
                        @if (count($requisitions) > 0)
                            {{ $requisitions->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
@stop