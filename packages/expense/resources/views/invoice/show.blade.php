@extends('layouts.app')
@section('title', 'เอกสารส่งเบิก')
@section('breadcrumb')
<li class="breadcrumb-item">
        <a href="{{ route('expense.invoice.index') }}"><strong> เอกสารขอเบิก </strong></a>
    </li>
    <li class="breadcrumb-item active">
        <strong> ตรวจสอบเอกสารขอเบิก </strong>
    </li>
@endsection

@section('content')
    @if ($invoice->status == 'ERROR')
        <div class="alert alert-danger background-danger mt-2">
            <strong>{{ $invoice->error_message }}</strong>
        </div>
    @endif

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
        <div class="ibox float-e-margins">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เลขที่ใบสำคัญ </strong>
                            </label><br>
                            {{ $invoice->voucher_number }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ผู้รับผิดชอบ </strong>
                            </label><br>
                            {{ $invoice->user->hrEmployee->full_name }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> สถานะ </strong>
                            </label><br>
                            {!! $invoice->getStatusIcon() !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ประเภทการขอเบิก </strong>
                            </label><br>
                            {{ $invoice->invoiceType->description ?? '-' }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> สำนักงานผู้เบิกจ่าย </strong>
                            </label><br>
                            {{ $invoice->document_category ?? '-' }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> วันที่เอกสารขอเบิก </strong>
                            </label><br>
                            {{ $invoice->invoice_date? date('d-m-Y', strtotime($invoice->invoice_date)): '-' }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ชื่อผู้สั่งจ่าย </strong>
                            </label><br>
                            {{ $invoice->supplier->vendor_name ?? '-' }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เลขที่ใบกำกับ </strong>
                            </label><br>
                            {{ $invoice->invoice_number ?? '-' }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> สกุลเงิน </strong>
                            </label><br>
                            {{ $invoice->currency ?? '-' }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> วิธีการจ่ายเงิน </strong>
                            </label><br>
                            {{ optional($invoice->paymentMethod)->description ?? '-' }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เทอมการชำระเงิน </strong>
                            </label><br>
                            {{ optional($invoice->paymentTerm)->description ?? '-'}}
                        </div>
                    </div>
                     <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> วันที่เคลียร์เงินยืม </strong>
                            </label><br>
                            {{ $invoice->clear_date? date('d-m-Y', strtotime($invoice->clear_date)): '-' }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> วันที่สัญญายืมเงิน </strong>
                            </label><br>
                            {{ $invoice->contact_date? date('d-m-Y', strtotime($invoice->contact_date)): '-' }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ใบโอนล้างถึงที่สุด (บอ.) </strong>
                            </label><br>
                            {{ optional($invoice->finalJudgment)->description ?? '-' }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เลขที่เอกสาร JV/KL (GFMIS) </strong>
                            </label><br>
                            {{ $invoice->gfmis_document_number ?? '-' }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> คำอธิบาย </strong>
                            </label><br>
                            {{ $invoice->description ?? '-' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> หมายเหตุ </strong>
                            </label><br>
                            {{ $invoice->note ?? '-' }}
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card row" style="background-color: #8cbbff; border: 1px solid #8cbbff;">
                    <div class="card-header" style="background-color: #8cbbff; padding: 10px;">
                        <strong> ข้อมูลรายการขอเบิก </strong>
                    </div>
                </div>
                <!-- TABLE LINE LISTS-->
                <table class="table table-responsive-sm">
                    <thead>
                        <tr>
                            <th class="text-center" width="7%"> รายการที่ </th>
                            <th class="text-left" width="15%"> ประเภทค่าใช้จ่าย </th>
                            <th class="text-left" width="26%"> รายการบัญชี </th>
                            <th class="text-center" width="10%"> จำนวนเงิน </th>
                            <th class="text-center" width="17%"> ชื่อสั่งจ่าย </th>
                            <th class="text-center" width="15%"> เลขที่บัญชีธนาคาร </th>
                            <th class="text-center" width="3%"> </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->lines as $line)
                            <tr>
                                <td class="text-center" style="vertical-align: middle;"> {{ $line->seq_number }} </td>
                                <td class="text-left" style="vertical-align: middle;"> {{ $line->expense->description }} </td>
                                <td class="text-left small wrap-text" style="vertical-align: middle;"> {{ $line->expense_account }} </td>
                                <td class="text-center" style="vertical-align: middle;"> {{ number_format($line->amount, 2) }} </td>
                                <td class="text-center" style="vertical-align: middle;"> {{ $line->supplier_name }} </td>
                                <td class="text-center" style="vertical-align: middle;"> {{ $line->bank_account_number }} </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <button type="button" class="btn btn-light" data-toggle="modal"
                                        data-target=".detail_{{ $line->id }}">
                                        <i class="fa fa-ellipsis-h "></i>
                                    </button>
                                </td>
                            </tr>
                            @include('expense::invoice._modal_detail')
                        @endforeach
                    </tbody>
                </table>
                <div class="row m-t-sm">
                    <div class="col-sm-9"> </div>
                    <div class="col-sm-3 text-right">
                        <div class="card">
                            <table class="table" style="margin: 0px;">
                                <tbody>
                                    <tr>
                                        <td class="mb-0 tw-text-grey-darker tw-text-bold" style="width:40%; font-size:15px !important;">
                                            <strong> รวมทั้งสิ้น : </strong>
                                        </td>
                                        <td class="mb-0 tw-text-grey-darker tw-text-bold" style="width:60%; font-size:15px !important;" >
                                            {{ number_format($invoice->lines->sum('amount'), 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-md-12 ">
                        @if ($invoice->status == 'ERROR')
                            {{-- <a href="{{ route('expense.invoice.edit', $invoice->id) }}" class="btn btn-warning btn-sm">
                                แก้ไขรายการ
                            </a> --}}
                            <invoice-reinterface-component
                                p-form-url = "{{ route('expense.invoice.re-submit', $invoice->id) }}"
                            ></invoice-reinterface-component>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')
@stop