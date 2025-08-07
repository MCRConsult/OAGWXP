@extends('layouts.app')
@section('title', 'ผู้ใช้งาน')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('expense.settings.permission.index') }}"><strong> สิทธิ์เข้าถึงการใช้งาน </strong></a>
    </li>
    <li class="breadcrumb-item active">
        <strong> รายละเอียดสิทธิ์เข้าถึงการใช้งาน </strong>
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row col-12" style="padding-right: 0px;">
                <div class="col-md-6">
                    <span class="d-inline">
                        <h5> <strong> รายละเอียดสิทธิ์เข้าถึงการใช้งาน </strong> </h5>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="ibox float-e-margins">
                <permission-show-component
                    p-form-url    = "{{ route('expense.settings.permission.index') }}"
                    :p-permission = "{{ json_encode($permission) }}"
                ></permission-show-component>
            </div>
        </div>
    </div>
@stop

@section('scripts')
@stop