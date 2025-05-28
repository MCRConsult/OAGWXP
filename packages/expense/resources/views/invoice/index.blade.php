@extends('layouts.app')
@section('title', 'เอกสารขอเบิก')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('expense.requisition.index') }}"><strong> เอกสารขอเบิก </strong></a>
    </li>
    <div class="col-md-10" style="padding-right: 0px;">
        <div class="text-right m-t-lg" style="padding-right: 0px;">
            <a class="btn btn-white btn-md mr-2" data-toggle="collapse" href="#search_form" role="button" aria-expanded="false" aria-controls="invoice_form">
                <i class="fa fa-search"></i> ค้นหา
            </a>
            <a href="{{ route('expense.invoice.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> สร้างเอกสารขอเบิก
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
    <invoice-search-component
        p-form-url          = "{{ route('expense.invoice.index') }}"
        p-token             = "{{ csrf_token() }}"
        :p-search           = "{{ json_encode(request()->all()) }}"
        :p-invoice-types    = "{{ json_encode($invoiceTypes) }}"
        :p-statuses         = "{{ json_encode($statuses) }}"
        p-date-js-format    = "{{ trans('date.js-format') }}"
    ></invoice-search-component>

<div class="card">
    <div class="card-header">
        <div class="row col-12" style="padding-right: 0px;">
            <div class="col-md-6">
                <span class="d-inline">
                    <h5> <strong> เอกสารขอเบิก </strong> </h5>
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
                                <div style="width: 120px;"> เลขที่ใบสำคัญ </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div style="width: 120px;"> วันที่เอกสารขอเบิก </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div style="width: 100px;"> วันที่เคลียร์เงิน </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div style="width: 150px;"> ผู้รับผิดชอบ </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div style="width: 150px;"> ชื่อผู้สั่งจ่าย </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div style="width: 200px;"> คำอธิบาย </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div style="width: 100px;"> จำนวนเงิน </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div style="width: 60px;"> สถานะ </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div style="width: 50px;"> </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    {{ $invoice->voucher_number }}
                                </td>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    {{ $invoice->invoice_date_format }}
                                </td>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    {{ $invoice->clear_date_format }}
                                </td>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    {{ $invoice->user->hrEmployee->last_name }}
                                </td>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    {{ $invoice->supplier->vendor_name }}
                                </td>
                                <td class="text-left text-nowrap" style="vertical-align: middle;">
                                    {{ $invoice->description }}
                                </td>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    {{ number_format($invoice->total_amount, 2) }}
                                </td>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    {!! $invoice->getStatusIcon() !!}
                                </td>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    <div style="border-collapse: collapse; width: 50px; display:inline-block; flex-direction: row;">
                                        @if ($invoice->status == 'CANCELLED' || $invoice->status == 'INTERFACED')
                                            <a class="btn btn-sm btn-light active mr-1"
                                                href="{{ route('expense.invoice.show', $invoice->id) }}">
                                                ตรวจสอบ
                                            </a>
                                        @else
                                            <a class="btn btn-sm btn-light active mr-1"
                                                href="{{ route('expense.invoice.edit', $invoice->id) }}">
                                                ตรวจสอบ
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
                        @if (count($invoices) > 0)
                            {{ $invoices->links() }}
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