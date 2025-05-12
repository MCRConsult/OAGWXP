@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('requisition.index') }}"><strong>BUDGET REQUISITION</strong></a>
    </li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <span class="d-inline">
                    <h1>BUDGET REQUISITION</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => ['requisition.index'], 'method' => 'get']) !!}
            <div class="row">
                <req-search
                    :search-data="{{ json_encode($searchData) }}"
                    :status-set="{{ json_encode($statusSet) }}"
                    :type-set="{{ json_encode($typeSet) }}">
                </req-search>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary" style="margin-top: 23px;">Search</button>
                </div>
                <div class="col-md-1">
                    <a class="btn btn-info" href="{{ route('requisition.index') }}" style="margin-top: 23px;">Reset</a>
                </div>
            </div>
        {!! form::close() !!}
    </div>
    <div class="card-body">
            <div class="row">
               <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-8">
                            {{ $requisitions->links() }}
                        </div>
                        <div class="col-md-4">
                            <div class="text-right">
                                <a class="btn btn-secondary" href="{{ route('requisition.sync', []) }}">
                                    <i class="fas fa-sync-alt"></i><span> Refresh </span>
                                </a>
                                <a class="btn btn-primary" href="{{ route('requisition.create', []) }}">
                                    <i class="fas fa-plus"></i><span> New Requisition </span>
                                </a>
                            </div>
                       </div>
                   </div>
                   <div class="table-responsive" style="min-height: 200px; max-height: 700px; overflow-x: scroll;">
                       <table class="table table-sm" style="margin-top: 20px">
                           <thead>
                               <tr>
                                    <th width="11%">Number</th>
                                    <th width="8%">Date</th>
                                    <th width="8%">Clear Date</th>
                                    <th>Create By</th>
                                    <th width="9%">Type</th>
                                    <th width="25%">Description</th>
                                    <th>Status</th>
                                    <th></th>
                               </tr>
                           </thead>
                           <tbody>
                               @foreach ($requisitions as $requisition)
                                   <tr>
                                        <td>
                                            {{ $requisition->req_number }}
                                            {{-- $requisition->canReEnc() --}}
                                            {{-- $requisition->alertClearDate() --}}
                                            @if ($requisition->status_clear == 'waiting clear')
                                                <i class="fa fa-exclamation-circle d-inline" aria-hidden="true"
                                                style="color: {{ $requisition->alertClearDate() ? 'red' : 'black' }};"></i>
                                            @endif
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime($requisition->req_date)) }}
                                        </td>
                                        <td>
                                            {{ $requisition->clear_date ? date('d-m-Y', strtotime($requisition->clear_date)) : '' }}
                                        </td>
                                        <td>
                                            {{ $requisition->user ? $requisition->user->fullname : '' }}
                                        </td>
                                        <td>
                                            {{ $requisition->getReqType() }}
                                        </td>
                                        <td>
                                            {{ $requisition->description }}
                                        </td>
                                        <td>
                                            {{ $requisition->status }}
                                        </td>
                                        <td class="text-nowrap text-right">
                                            <a class="btn btn-secondary btn-sm" href="{{ route('requisition.show', [$requisition->id]) }}">
                                                View
                                            </a>
                                            <div class="dropdown d-inline">
                                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    @if ($requisition->req_type == 'Prepayment')
                                                        @if ($requisition->status_clear != null)
                                                            <a class="dropdown-item" href="{{ route('requisition.pdf', [$requisition->id, 'type' => 'Adv. Payment']) }}" target="_bank">
                                                                PDF - Adv. Payment
                                                            </a>
                                                            <a class="dropdown-item" href="{{ route('requisition.pdf', [$requisition->id, 'type' => 'Clear Adv. Payment']) }}" target="_bank">
                                                                PDF - Clear Payment
                                                            </a>
                                                        @else
                                                            <a class="dropdown-item" href="{{ route('requisition.pdf', [$requisition->id, 'type' => 'Adv. Payment']) }}" target="_bank">
                                                                PDF
                                                            </a>
                                                        @endif
                                                    @else
                                                        <a class="dropdown-item" href="{{ route('requisition.pdf', [$requisition->id, 'type' => 'Standard']) }}" target="_bank">
                                                            PDF
                                                        </a>
                                                    @endif
                                                    @if (in_array($requisition->status , ['draft', 'rejected', 'hold']) && $requisition->status_clear != 'waiting clear')
                                                        <a class="dropdown-item" href="{{ route('requisition.edit', [$requisition->id]) }}">
                                                            Edit Header
                                                        </a>
                                                    @endif
                                                    @if ($requisition->req_type == 'Prepayment' && in_array($requisition->status , ['hold', 'approved', 'rejected'])
                                                        && $requisition->status_clear == 'waiting clear' && !$requisition->canReEnc())
                                                        <a class="dropdown-item" href="{{ route('clear-requisition.index', [$requisition->id]) }}" target="_bank">
                                                            Clear
                                                        </a>
                                                    @endif
                                                    @if($requisition->canReEnc() && auth()->user()->id == 1)
                                                        <a class="dropdown-item" href="#"
                                                            onclick="
                                                                if(confirm('Please confirm to duplicate.')){
                                                                    document.getElementById('form_reEnc_{{$requisition->id}}').submit();
                                                                }else return;
                                                            "
                                                        >
                                                        Re-Encumbrance
                                                        </a>
                                                        <form id="form_reEnc_{{$requisition->id}}"
                                                            action="{{ route('requisition.re-enc', [$requisition->id]) }}"
                                                            method="post">
                                                            @csrf
                                                        </form>
                                                    @endif
                                                    {{-- <a class="dropdown-item" href="#"
                                                        onclick="
                                                            if(confirm('Please confirm to duplicate.')){
                                                                document.getElementById('form_duplicate_{{$requisition->id}}').submit();
                                                            }else return;
                                                        "
                                                    >
                                                        Duplicate
                                                    </a>
                                                    <form id="form_duplicate_{{$requisition->id}}"
                                                        action="{{ route('requisition.duplicate', [$requisition->id]) }}"
                                                        method="post">
                                                        @csrf
                                                    </form> --}}
                                                    @if (in_array($requisition->status , ['draft', 'approved', 'rejected']))
                                                        <a class="dropdown-item" href="#"
                                                            onclick="
                                                                if(confirm('Do you want to cancel this item?')){
                                                                    document.getElementById('form_cancel_{{$requisition->id}}').submit();
                                                                }else return;
                                                            "
                                                        >
                                                            Cancel
                                                        </a>
                                                        <form id="form_cancel_{{$requisition->id}}"
                                                            action="{{ route('requisition.destroy', [$requisition->id]) }}"
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
