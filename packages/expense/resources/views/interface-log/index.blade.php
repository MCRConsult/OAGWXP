@extends('layouts.app')
@section('title', 'ประวัติการอินเตอร์เฟซ')
@section('breadcrumb')
    <li class="breadcrumb-item active">
        <strong> ประวัติการอินเตอร์เฟซ </strong>
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row col-12" style="padding-right: 0px;">
                <div class="col-md-6">
                    <span class="d-inline">
                        <h5> <strong> ประวัติการอินเตอร์เฟซ </strong> </h5>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="ibox float-e-margins">
                <invoice-interface-log-component
                    p-form-url      = "{{ route('expense.invoice.interface-log') }}"
                    :p-search       = "{{ json_encode(request()->all()) }}"
                    :p-statuses     = "{{ json_encode($statuses) }}"
                    :p-interfaces   = "{{ json_encode($interfaces) }}"
                ></invoice-interface-log-component>
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