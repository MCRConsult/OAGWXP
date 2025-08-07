@extends('layouts.app')
@section('title', 'ผู้ใช้งาน')
@section('breadcrumb')
    <li class="breadcrumb-item active">
        <strong> ผู้ใช้งาน </strong>
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row col-12" style="padding-right: 0px;">
                <div class="col-md-6">
                    <span class="d-inline">
                        <h5> <strong> ผู้ใช้งาน </strong> </h5>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="ibox float-e-margins">
                <user-component
                    p-form-url  = "{{ route('expense.settings.user.index') }}"
                    :p-search   = "{{ json_encode(request()->all()) }}"
                    :p-statuses = "{{ json_encode($statuses) }}"
                    {{-- DATA --}}
                    :p-users    = "{{ json_encode($users) }}"
                ></user-component>
            </div>
        </div>
    </div>
@stop

<style type="text/css">
    .sticky-col {
        position: sticky !important;
        background-color: #FFF;
        z-index: 9999;
        top:0px;
    }
</style>

@section('scripts')
@stop