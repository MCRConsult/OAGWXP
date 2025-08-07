@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('expense.requisition.index') }}"><strong> เอกสารส่งเบิก </strong></a>
    </li>
    <li class="breadcrumb-item active">
        <strong> รายการเอกสารเคลียร์เงินยืม </strong>
    </li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <span class="d-inline">
                <h5> <strong> เอกสารเคลียร์เงินยืม </strong> </h5>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="card" style="border: 2px solid #ff6d6d;">
            <div class="card-body pt-2 pb-1">
               <div class="row">
                    <div class="col-md-10">
                        <h3 style="font-weight: bold;">
                            เลขที่เอกสารส่งเบิก : {{ $requisition->req_number }}
                        </h3>
                    </div>
                    <div class="col-md-2">
                        <h3 style="font-weight: bold;">
                            สกุลเงิน : THB
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @php
                $amount = $requisition->lines->sum('amount');
                $actualAmount = $requisition->lines->sum('actual_amount');
            @endphp
            <div class="col-12 col-sm-6 col-xl-4 col-xxl-3">
                <div class="card overflow-hidden">
                    <div class="card-body p-0 d-flex align-items-center">
                        <div class="bg-primary text-white py-4 px-5 me-3">
                            <i class="fa fa-shopping-basket fa-2x"></i>
                        </div>
                        <div>
                            <div class="fs-6 fw-semibold font-weight-bold">
                                <h4> {{ number_format($amount, 2) }} </h4>
                            </div>
                            <div class="font-weight-bold mt-2"> จำนวนเงินที่ขอเบิก </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4 col-xxl-3">
                <div class="card overflow-hidden">
                    <div class="card-body p-0 d-flex align-items-center">
                        <div class="bg-warning text-white py-4 px-5 me-3">
                            <i class="fa fa-credit-card fa-2x"></i>
                        </div>
                        <div>
                            <div class="fs-6 fw-semibold font-weight-bold">
                                <h4> {{ number_format($actualAmount, 2) }} </h4>
                            </div>
                            <div class="font-weight-bold mt-2"> จำนวนเงินที่เบิกจริง </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4 col-xxl-3">
                <div class="card overflow-hidden">
                    <div class="card-body p-0 d-flex align-items-center">
                        <div class="bg-danger text-white py-4 px-5 me-3">
                            <i class="fa fa-balance-scale fa-2x"></i>
                        </div>
                        <div>
                            <div class="fs-6 fw-semibold font-weight-bold">
                                <h4> {{ number_format($amount - $actualAmount, 2) }} </h4>
                            </div>
                            <div class="font-weight-bold mt-2"> จำนวนเงินผลต่าง </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- TABLE LINE LISTS-->
        <div class="table-responsive mt-3" style="max-height: 600px;">
            <table class="table text-nowrap table-hover">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">
                            รายการที่ 
                        </th>
                        <th class="text-left" width="15%">
                            ประเภทค่าใช้จ่าย 
                        </th>
                        <th class="text-center bg-primary" width="10%">
                            จำนวนเงินที่ขอเบิก
                        </th>
                        <th class="text-center bg-warning" width="10%">
                            จำนวนเงินที่เบิกจริง
                        </th>
                        <th class="text-center bg-danger" width="10%">
                            จำนวนเงินผลต่าง
                        </th>
                        <th class="text-center" width="12%">
                            ชื่อสั่งจ่าย 
                        </th>
                        <th class="text-center" width="12%">
                            เลขที่บัญชีธนาคาร 
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requisition->lines as $line)
                        <tr>
                            <td class="text-center" style="vertical-align: middle;">
                                {{ $line->seq_number }}
                            </td>
                            <td class="text-left" style="vertical-align: middle;">
                                {{ $line->expense->description }}
                            </td>
                            <td class="text-right bg-primary" style="vertical-align: middle;">
                                {{ number_format($line->amount, 2) }}
                            </td>
                            <td class="text-right bg-warning" style="vertical-align: middle;">
                                {{ number_format($line->actual_amount, 2) }}
                            </td>
                            <td class="text-right bg-danger" style="vertical-align: middle;">
                                {{ number_format($line->amount - $line->actual_amount, 2) }}
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                {{ $line->supplier_name }}
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                {{ $line->bank_account_number }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="text-center" colspan="2"> รวมทั้งสิ้น </th>
                        <th class="text-right"> {{ number_format($amount, 2) }} </th>
                        <th class="text-right"> {{ number_format($actualAmount, 2) }} </th>
                        <th class="text-right"> {{ number_format($amount - $actualAmount, 2) }} </th>
                        <th class="text-center" colspan="2"> </th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
