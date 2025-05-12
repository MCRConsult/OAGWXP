@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('invoice.index') }}"><strong>Invoices</strong></a>
    </li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <span class="d-inline">
                    <h1>Invoices</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => ['invoice.index'], 'method' => 'get']) !!}
            <div class="row">
                <inv-search
                    :search-data="{{ json_encode($searchData) }}"
                    :status-set="{{ json_encode($statusSet) }}"
                    :type-set="{{ json_encode($typeSet) }}">
                </inv-search>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary" style="margin-top: 23px;"> search</button>
                </div>
                <div class="col-md-1">
                    <a class="btn btn-info" href="{{ route('invoice.index') }}" style="margin-top: 23px;"> reset</a>
                </div>
            </div>
        {!! form::close() !!}
    </div>
    <div class="card-body">
            <div class="row">
               <div class="col-md-12">
                   <div class="row">
                        <div class="col-md-8">
                            {{ $invoices->links() }}
                        </div>
                        <div class="col-md-4">
                            <div class="text-right">
                                <a class="btn btn-primary" href="{{ route('invoice.create') }}">
                                    <i class="fas fa-plus"></i><span> Add Invoice </span>
                                </a>
                            </div>
                       </div>
                   </div>
                   <div class="table-responsive" style="max-height: 500px; overflow-x: scroll;">
                       <table class="table table-sm" style="margin-top: 20px">
                           <thead>
                               <tr>
                                   {{-- <th>Status</th> --}}
                                   <th style="min-width: auto; max-width: 100px;">Number</th>
                                   <th style="min-width: 88px; max-width: 100px;">Billing Date</th>
                                   {{-- <th width="10%">Due Date</th> --}}
                                   <th style="min-width: 88px; max-width: 100px;">Clear Date</th>
                                   <th style="min-width: 140px; max-width: 300px;">Supplier</th>
                                   <th style="min-width: 160px; max-width: 300px;">Created by</th>
                                   <th>Description</th>
                                   <th>Status</th>
                                   <th class="text-right" width="5%">Amount</th>
                                   <th></th>
                               </tr>
                           </thead>
                           <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        {{-- <td>
                                            {{ $invoice->status }}
                                        </td> --}}
                                        <td>
                                            {{ $invoice->inv_number }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime($invoice->inv_date)) }}
                                        </td>
                                        {{-- <td>
                                            {{ date('d-m-Y', strtotime($invoice->due_date)) }}
                                        </td> --}}
                                        <td>
                                            {{ $invoice->clear_date ? date('d-m-Y', strtotime($invoice->clear_date)) : '' }}
                                        </td>
                                        <td>
                                            {{-- {{ $invoice->supplier_number }} --}}
                                            {{-- {{ $invoice->supplier ? $invoice->supplier->alt_supplier_name : $invoice->supplier_number }} --}}
                                            {{-- {{ dd($invoice->supplierName) }} --}}
                                            {{ $invoice->supplier ? $invoice->supplier->alt_supplier_name : ( $invoice->supplierName ? $invoice->supplierName->vendor_name : $invoice->supplier_number) }}
                                        </td>
                                        <td>
                                            {{ $invoice->createdBy->fullName }}
                                        </td>
                                        <td>
                                            {{ $invoice->description }}
                                        </td>
                                        <td>
                                            {{ $invoice->status }}
                                        </td>
                                        <td class="text-right">
                                            {{ $invoice->getAmount() }}
                                        </td>
                                        <td  class="text-nowrap text-right">
                                            <a class="btn btn-secondary btn-sm" href="{{ route('invoice.show', [$invoice->id]) }}">
                                                View
                                            </a>

                                            <div class="dropdown d-inline">
                                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="{{ route('invoice.edit', [$invoice->id]) }}">
                                                        Edit Header
                                                    </a>
                                                    @if (in_array($invoice->status , ['draft']))
                                                        <a class="dropdown-item" href="#"
                                                            onclick="
                                                                if(confirm('Do you want to delete this item?')){
                                                                    document.getElementById('form_delete_{{$invoice->id}}').submit();
                                                                }else return;
                                                            "
                                                        >
                                                            Delete
                                                        </a>
                                                        <form id="form_delete_{{$invoice->id}}"
                                                            action="{{ route('invoice.destroy', [$invoice->id]) }}"
                                                            method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                           </tbody>
                       </table>
                   </div>
               </div>
           </div>
       </div>
</div>
@endsection
