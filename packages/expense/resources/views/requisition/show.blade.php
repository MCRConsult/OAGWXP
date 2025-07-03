@extends('layouts.app')
@section('title', 'เอกสารส่งเบิก')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('expense.requisition.index') }}"><strong> เอกสารส่งเบิก </strong></a>
    </li>
    <li class="breadcrumb-item active">
        <strong> ตรวจสอบเอกสารส่งเบิก </strong>
    </li>
@endsection

@section('content')
    {{-- @if ($requisition->status == 'CANCELLED')
        <div class="alert alert-danger background-danger mt-2">
            <strong>{{ $requisition->cancel_reason }}</strong>
        </div>
    @endif --}}

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
        <div class="ibox float-e-margins">
            @if ($requisition->status == 'CANCELLED')
                <el-alert title="สาเหตุการยกเลิก : {{ $requisition->cancel_reason }}" type="error" show-icon :closable="false">
                    <template #icon>
                        <i class="fa fa-bell"></i>
                    </template>                
                </el-alert>
            @endif
            <div class="col-md-12 mt-2">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> แหล่งเงิน </strong>
                            </label><br>
                            {{ $requisition->budgetSource->description }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ประเภทการขอเบิก </strong>
                            </label><br>
                            {{ $requisition->paymentType->description }}
                        </div>
                    </div>
                    @if ($requisition->cash_bank_account_id)
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> ธนาคาร </strong>
                                </label><br>
                                {{ $requisition->cashBankAccount->bank_account_num }} : {{ $requisition->cashBankAccount->bank_account_name }}
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ประเภท </strong>
                            </label><br>
                            {{ $requisition->invoiceType->description }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> สำนักงานผู้เบิกจ่าย </strong>
                            </label><br>
                            {{ $requisition->document_category }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> วันที่เอกสารส่งเบิก </strong>
                            </label><br>
                            {{ date('d-m-Y', strtotime($requisition->req_date)) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" style="padding: 5px;">
                            <span class="d-flex">
                                <label class="control-label" style="margin-right: 30px;">
                                    <strong>ชื่อสั่งจ่าย:</strong>
                                </label>
                                <label class="form-check-label" style="margin-right: 30px;">
                                    <input type="radio" class="form-check-input" name="multiple_supplier" value="ONE" disabled
                                        {{ $requisition->multiple_supplier == 'ONE'? 'checked': '' }}
                                    >
                                    <strong> รายเดียว </strong>
                                </label>
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="multiple_supplier" value="MORE" disabled
                                        {{ $requisition->multiple_supplier == 'MORE'? 'checked': '' }}
                                    >
                                    <strong> หลายราย (กรอกข้อมูลระดับรายการ) </strong>
                                </label>
                            </span>
                            {{ $requisition->supplier_name }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> คำอธิบาย </strong>
                            </label><br>
                            {{ $requisition->description }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong>
                                    @if ($requisition->payment_type == 'PAYMENT')
                                        เลขที่เอกสารส่งเบิก
                                    @else
                                        เลขที่ใบสำคัญ
                                    @endif
                                </strong>
                            </label><br>
                            {{ $requisition->req_number }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> ผู้รับผิดชอบ </strong>
                            </label><br>
                            {{ $requisition->user->hrEmployee->full_name }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> สถานะ </strong>
                            </label><br>
                            {!! $requisition->getStatusIcon() !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="padding: 5px;">
                            <label class="control-label">
                                <strong> เลขที่ใบกำกับ </strong>
                            </label><br>
                            {{ $requisition->invioce_number_ref }}
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card row" style="background-color: #8cbbff; border: 1px solid #8cbbff;">
                    <div class="card-header" style="background-color: #8cbbff; padding: 10px;">
                        <strong> รายการเอกสารส่งเบิก </strong>
                    </div>
                </div>
                <table class="table table-responsive-sm">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%"> รายการที่ </th>
                            <th class="text-center" width="15%"> ประเภทค่าใช้จ่าย </th>
                            <th class="text-center" width="10%"> จำนวนเงิน </th>
                            <th class="text-center" width="15%"> ชื่อสั่งจ่าย </th>
                            <th class="text-center" width="15%"> เลขที่บัญชีธนาคาร </th>
                            <th class="text-center" width="3%"> </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requisition->lines as $line)
                            <tr>
                                <td class="text-center" style="vertical-align: middle;"> {{ $line->seq_number }} </td>
                                <td class="text-center" style="vertical-align: middle;"> {{ $line->expense->description }} </td>
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
                            @include('expense::requisition._modal_detail')
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
                                            {{ number_format($requisition->lines->sum('amount'), 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-md-12 ">
                        @if ($requisition->status == 'ERROR' || $requisition->status == 'UNREVERSED')
                            <requisition-reinterface-component
                                p-form-url = "{{ route('expense.requisition.journal-resubmit', $requisition->id) }}"
                            ></requisition-reinterface-component>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')
@stop