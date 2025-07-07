@extends('layouts.app')
@section('title', 'ผู้ใช้งาน')
@section('breadcrumb')
    <li class="breadcrumb-item active">
        <strong> สิทธิ์เข้าถึงการใช้งาน </strong>
    </li>
    <div class="col-md-9 ml-5" style="padding-right: 0px;">
        <div class="text-right m-t-lg" style="padding-right: 0px;">
            <a href="{{ route('expense.settings.permission.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> สร้างสิทธิ์เข้าถึงการใช้งาน
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row col-12" style="padding-right: 0px;">
                <div class="col-md-6">
                    <span class="d-inline">
                        <h5> <strong> สิทธิ์เข้าถึงการใช้งาน </strong> </h5>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="ibox float-e-margins">
                <permission-component
                    p-form-url    = "{{ route('expense.settings.permission.index') }}"
                    :p-permissions = "{{ json_encode($permissions) }}"
                ></permission-component>
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