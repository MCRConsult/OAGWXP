@extends('layouts.app')
@section('breadcrumb')
<li class="breadcrumb-item">
    Requisition
</li>
@endsection
@section('content')
    @if(session()->has('message'))
<div class="mb-3">
    <div class="alert alert-success alert-dismissible fade show">
        <div>
            {{ session()->get('message') }}
        </div>
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">
                ×
            </span>
        </button>
    </div>
</div>
@endif
<h3 class="sub-title mb-3 tw-text-grey-darker">
    Requisition
</h3>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="tw-text-grey-darker">
                </div>
            </div>
            @include('shared.approves._form')
        </div>
        @if ($currentApproveLine->group_approve_role_id ?? [])
            <hr>
            {!! Form::open(['route' => ['memo.store', $approveHeader->id], 'method' => 'POST', 'files' => true] ) !!}
                <h4 class="tw-text-grey-darker">Memo</h4>
                @include('asap.approves.memos._form_memos')
                <div class="text-right">
                    <button class="tw-bg-blue hover:tw-bg-blue-dark tw-text-white tw-font-bold tw-py-2 tw-px-4 rounded">
                        Save Memo
                    </button>
                </div>
            {!! Form::close() !!}
            <hr>
        @endif
{{--         @if ($approveHeader->memos)
            @include('asap.approves.memos._list_memos')
        @endif --}}
    </div>
    <div class="col-md-4 ">
        @include('e-expenses.requisition.approves._time_line')
    </div>
</div>
@endsection

