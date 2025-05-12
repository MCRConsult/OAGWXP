@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href={{ route("invoice.index") }}>
            <strong>Invoices</strong>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href={{ route("invoice.show", $invoice->id) }}>
            <strong>{{ $invoice->inv_number }}</strong>
        </a>
    </li>
@endsection
@section('content')
    <div class="row">
        <div style="width: 200px">
            @include('e-expenses.invoice._menu')
        </div>
        <div class="col text-truncate">
            <div class="row">
                <div class="col-10">
                    <h3 class="page-title">{{ $invoice->inv_number }}</h3>
                </div>
                @if ( in_array($invoice->status, ['draft', 'waiting clear']))
                    <div class="col-2 text-right">
                        <a class="btn btn-secondary btn-sm" href="{{ route('invoice.edit', [$invoice->id]) }}">
                            Edit Header
                        </a>
                    </div>
                @endif
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="tw-font-bold text-right">Billing Date: </div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ date('d-m-Y', strtotime($invoice->inv_date)) }}
                        </div>
                    </div>
                    <hr>
                    {{-- <div class="row">
                        <div class="col-md-2">
                            <div class="tw-font-bold text-right">Due Date: </div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ date('d-m-Y', strtotime($invoice->due_date)) }}
                        </div>
                    </div>
                    <hr> --}}
                    @if ($invoice->clear_date)
                        <div class="row">
                            <div class="col-md-2">
                                <div class="tw-font-bold text-right">Clear Date: </div>
                            </div>
                            <div class="col-md-10 text-wrap">
                                {{ date('d-m-Y', strtotime($invoice->clear_date)) }}
                            </div>
                        </div>
                        <hr>
                    @endif
                    <div class="row">
                        <div class="col-md-2">
                            <div class="tw-font-bold text-right">Supplier: </div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ $invoice->supplier ? $invoice->supplier->supplier_number. " : " .$invoice->supplier->alt_supplier_name : $invoice->supplier_number }} 
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="tw-font-bold text-right">Term:</div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ $invoice->term }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="tw-font-bold text-right">Payment Method:</div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ $invoice->payment_method }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="tw-font-bold text-right">Currency: </div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ $invoice->currency }}
                        </div>
                    </div>
                    <hr>
                    @if ($invoice->bank_account)
                        <div class="row">
                            <div class="col-md-2">
                                <div class="tw-font-bold text-right">Bank: </div>
                            </div>
                            <div class="col-md-10 text-wrap">
                                {{ $invoice->bankAccount ? $invoice->bankAccount->bank_account_name : $invoice->bank_account }}
                            </div>
                        </div>
                        <hr>
                    @endif
                    @if ($invoice->receipt_number)
                        <div class="row">
                            <div class="col-md-2">
                                <div class="tw-font-bold text-right">Receipt Number: </div>
                            </div>
                            <div class="col-md-10 text-wrap">
                                {{ $invoice->receipt_number }}
                            </div>
                        </div>
                        <hr>
                    @endif
                    @if ($invoice->tax_invoice_number)
                        <div class="row">
                            <div class="col-md-2">
                                <div class="tw-font-bold text-right">Tax Invoice Number: </div>
                            </div>
                            <div class="col-md-10 text-wrap">
                                {{ $invoice->tax_invoice_number }}
                            </div>
                        </div>
                        <hr>
                    @endif
                    <div class="row">
                        <div class="col-md-2">
                            <div class="tw-font-bold text-right">Description: </div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ $invoice->description }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="tw-font-bold text-right">Status: </div>
                        </div>
                        <div class="col-md-10 text-wrap">
                            {{ $invoice->status }}
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Lines</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="7%">#</th>
                                <th width="40%">Item</th>
                                <th width="15%" class="text-right">Total</th>
                                <th width="15%" class="text-right">Balance</th>
                                <th width="15%" class="text-right">Use</th>
                            </tr>
                        </thead>
                        @foreach ($groupLineSelected as $index => $LinesSelected)
                            <tbody>
                                <tr>
                                    <td colspan="100" class="h5">
                                        Index {{ $loop->iteration }} {{ $index }}
                                    </td>
                                </tr>
                                @foreach ($LinesSelected as $lineUse)
                                    <tr>
                                        <td>
                                            {{ $loop->parent->iteration }}.{{ $loop->iteration }}
                                        </td>
                                        @if ($lineUse->add_line || $lineUse->pre_line)
                                            <td>
                                                {{ $lineUse->item->description }}
                                            </td>
                                            <td class="text-right">
                                                -
                                            </td>
                                            <td class="text-right">
                                                -
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($lineUse->total_amount,2) }}
                                            </td>
                                        @else
                                            <td>
                                                {{ $lineUse->line->budget->item->description }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($lineUse->line->total_price,2) }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($lineUse->line->balance,2) }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($lineUse->total_amount,2) }}
                                            </td>
                                        @endif
                                     </tr>
                                @endforeach
                            </tbody>
                        @endforeach
                    </table>
                </div>
            </div> --}}
            {{-- <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pays For</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="7%">#</th>
                                <th width="20%">Bank Account Name</th>
                                <th width="20%">Bank Name</th>
                                <th width="20%">Bank Account Number</th>
                                <th width="15%" class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pays as $pay)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $pay->account_name }}
                                    </td>
                                    <td>
                                        {{ $pay->bank_name }}
                                    </td>
                                    <td >
                                        {{ $pay->account_number }}
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
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Lines</h3>
                    <div class="table-responsive" style="max-height: 500px; overflow-x: scroll;">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th width="7%">#</th>
                                    <th>Item</th>
                                    <th>Department</th>
                                    <th>Project</th>
                                    <th>Account</th>
                                    <th>Budget</th>
                                    <th>Reserve1</th>
                                    <th class="text-right">Exclude VAT</th>
                                    @if ( $invoice->type == 'Prepayment' && $invoice->status_clear != null )
                                        <th class="text-right">Actual Total Price</th>
                                        <th class="text-right">Actual Exclude VAT</th>
                                    @endif
                                    <th>VAT Code</th>
                                    <th class="text-right">VAT Amount</th>
                                    <th>WHT Code</th>
                                    <th class="text-right">WHT Amount</th>
                                    <th>Pay For</th>
                                    <th>Bank</th>
                                    <th>Bank Branch</th>
                                    <th>Account Number</th>
                                    <th>Tax ID</th>
                                    <th>E-mail</th>
                                    <th>Address1</th>
                                    <th>Address2</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groupLineSelected as $index => $lines)
                                    <tr>
                                        <td colspan="{{ $invoice->type == 'Prepayment' && $invoice->status_clear != null ? '22' : '20' }}" 
                                            class="h5">
                                            Index {{ $loop->iteration }} {{ $index }}
                                        </td>
                                        <td class="text-right h5">
                                            {{ $invoice->getAmount() }}
                                        </td>
                                    </tr>
                                    @foreach ($lines as $line)
                                        @php
                                            $code = explode('.', $line->code_combination);
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $loop->parent->iteration }}.{{ $loop->iteration }}
                                            </td>
                                            <td>
                                                @if ($line->budget)
                                                    @if ($line->budget->description)
                                                        {{ $line->budget->item->description }} - {{ $line->budget->description }} 
                                                    @else
                                                        {{ $line->budget->item->description }}
                                                    @endif
                                                @else
                                                    @if ($line->item)
                                                        {{ $line->item->description }}
                                                    @else
                                                        -
                                                    @endif
                                                @endif
                                                @if($line->description)
                                                    <span> {{ ' : ' . $line->description }} </span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $code[2] }}
                                            </td>
                                            <td>
                                                {{ $code[3] }}
                                            </td>
                                            <td>
                                                {{ $code[4] }}
                                            </td>
                                            <td>
                                                {{ $code[5] }}
                                            </td>
                                            <td>
                                                {{ $code[6] }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($line->amount,2) }}
                                            </td>
                                            @if ( $invoice->type == 'Prepayment' && $invoice->status_clear != null )
                                                <td  class="text-right">
                                                    {{ number_format($line->actual_total_price,2) }}
                                                </td>   
                                                <td class="text-right">
                                                    {{ number_format($line->actual_total_amount,2) }}
                                                </td>
                                            @endif
                                            <td>
                                                {{ $line->vat_code }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($line->vat_amount,2) }}
                                            </td>
                                            <td>
                                                {{ $line->wht_code }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($line->wht_amount,2) }}
                                            </td>  
                                            <td>
                                                {{ $line->bank_account_name }}
                                            </td>
                                            <td>
                                                {{ $line->bank 
                                                ? $line->bank->flex_value_meaning . " : " . $line->bank->description 
                                                : $line->bank_name }}
                                            </td>
                                            <td>
                                                {{ $line->bankBranch 
                                                ? $line->bankBranch->branch_number . " : " . $line->bankBranch->bank_branch_name 
                                                : $line->bank_branch }}
                                            </td>
                                            <td>
                                                {{ $line->bank_account_number }}
                                            </td>
                                            <td>
                                                {{ $line->tax_payer_id }}
                                            </td>
                                            <td>
                                                {{ $line->email }}
                                            </td>
                                            <td>
                                                {{ $line->address1 }}
                                            </td>
                                            <td>
                                                {{ $line->address2 }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($line->total_amount,2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
