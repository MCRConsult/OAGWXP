@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href={{ route("requisition.index") }}><strong>BUDGET REQUISITION</strong></a></li>
    <li class="breadcrumb-item"><a href={{ route("requisition.show", $requisition->id) }}><strong>{{ $requisition->req_number }}</strong></a></li>
@endsection
@section('content')
    <div class="row">
        <div style="width: 200px">
            @include('e-expenses.requisition._menu')
        </div>
        <div class="col text-truncate">
            <div class="row">
                <div class="col-10">
                    <h3 class="page-title">{{ $requisition->req_number }}</h3>
                </div>
                @if (in_array($requisition->status , ['draft', 'rejected', 'hold']) && $requisition->status_clear != 'waiting clear')
                    <div class="col-2 text-right">
                        <a class="btn btn-secondary btn-sm" href="{{ route('requisition.edit', [$requisition->id]) }}">
                            Edit Header
                        </a>
                    </div>
                @endif
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="tw-font-bold text-right">Type: </div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ $requisition->getReqType() }}
                            @if ( $requisition->withdraw == 'Withdraw' )
                                - เบิกแทน
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="tw-font-bold text-right">Date: </div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ date('d-m-Y', strtotime($requisition->req_date)) }}
                        </div>
                    </div>
                    <hr>
                    @if ($requisition->clear_date)
                        <div class="row">
                            <div class="col-md-2">
                                <div class="tw-font-bold text-right">Clear Date: </div>
                            </div>
                            <div class="col-md-10 text-wrap">
                                {{ date('d-m-Y', strtotime($requisition->clear_date)) }}
                            </div>
                        </div>
                        <hr>
                    @endif
                    <div class="row">
                        <div class="col-md-2">
                            <div class="tw-font-bold text-right">Requester: </div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ $requisition->user ? $requisition->user->fullname : '' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="tw-font-bold text-right">Position: </div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ $requisition->position }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="tw-font-bold text-right">Department: </div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ $requisition->department()->flex_value }} {{ $requisition->department()->value_description }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="tw-font-bold text-right">Campus: </div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ $requisition->campus }} : {{ $requisition->getCampus()->value_description }}
                        </div>
                    </div>
                    <hr>
                    @if ($requisition->project)
                        <div class="row">
                            <div class="col-md-2">
                                <div class="tw-font-bold text-right">Project: </div>
                            </div>
                            <div class="col-md-10 text-wrap">
                                {{ $requisition->project->name }}
                            </div>
                        </div>
                        <hr>
                    @else
                    @endif
                    <div class="row">
                        <div class="col-md-2">
                            <div class="tw-font-bold text-right">Extension Number: </div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ $requisition->extension_number }}
                        </div>
                    </div>
                    <hr>
                    @if ($requisition->outsourcing == '1')
                        <div class="row">
                            <div class="col-md-2">
                                <div class="tw-font-bold text-right">Payment Method:</div>
                            </div>
                            <div class="col-md-10 text-wrap">
                                {{ $requisition->payment_method }}
                            </div>
                        </div>
                        <hr>
                    @endif
                    <div class="row">
                        <div class="col-md-2">
                            <div class="tw-font-bold text-right">Currency: </div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ $requisition->currency }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="tw-font-bold text-right">Payment Type: </div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ $requisition->getPaymentType() }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                                <div class="tw-font-bold text-right">Description: </div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ $requisition->description }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                                <div class="tw-font-bold text-right">Status: </div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ $requisition->status }}
                        </div>
                    </div>
                </div>
            </div>

            <table-line-new
                :group-lines="{{ json_encode($groupRequisitionLinesNew) }}"
                :requisition="{{ json_encode($requisition) }}"
                :project="{{ json_encode($requisition->project) }}">
            </table-line-new>
                    {{-- <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="7%">#</th>
                                <th width="50%">Item</th>
                                <th width="10%" class="text-right">Qty</th>
                                <th width="10%" class="text-right">Price</th>
                                <th width="10%" class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupRequisitionLines as $index => $lines)
                                <tr>
                                    <td colspan="100" class="h5">
                                        Index {{ $loop->iteration }} {{ $index }}
                                    </td>
                                </tr>
                                @foreach ($lines as $line)
                                    <tr>
                                        <td>
                                            {{ $loop->parent->iteration }} . {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $line->budget->item->description }}
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($line->quantity,2) }}
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($line->price,2) }}
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($line->total_price,2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table> --}}
                    {{-- <table-line
                        :group-lines="{{ json_encode($groupRequisitionLines) }}"
                        :banks="{{ json_encode($banks) }}">
                    </table-line> --}}
            @if ($requisition->req_type == 'Prepayment')
                <table-summary-after-clear
                    :requisition="{{json_encode($requisition)}}"
                    :group-lines="{{ json_encode($groupRequisitionLinesNew) }}"
                    :project="{{ json_encode($requisition->project) }}">
                </table-summary-after-clear>
            @endif
            {{-- <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Pay For</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="7%">#</th>
                                <th width="25%">Pay for</th>
                                <th width="25%">Bank Name</th>
                                <th width="25%">Bank Account Number</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requisitionPays as $pay)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $pay->bank_account_name }}
                                    </td>
                                    <td>
                                        {{ $pay->bank->value_description }}
                                    </td>
                                    <td>
                                        {{ $pay->bank_account_number }}
                                    </td>
                                    <td class="text-right">
                                        {{ number_format($pay->amount,2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div> --}}

        </div>
        @if ($requisition->status == 'in process')
            <div class="col-md-12 ">
                <div class="row">
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-7">
                        <div class="card ml-3">
                            <div class="card-body">
                                <div class="tw-text-grey-darker">
                                    {{-- @include('e-expenses.requisition.approves._form') --}}
                                    @include('shared.approves._form')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        @include('e-expenses.requisition.approves._time_line')
                    </div>
                </div>
            </div>
        @endif
        @if ($requisition->status == 'approved' && $requisition->status_clear != 'waiting clear')
            <div class="col-md-12 ">
                <div class="row">
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-7">
                        <div class="card ml-3">
                            <div class="card-body">
                                <div class="tw-text-grey-darker">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        @include('e-expenses.requisition.approves._time_line')
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
