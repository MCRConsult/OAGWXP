@extends('layouts.app')
@section('title', 'ผู้ใช้งาน')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('expense.settings.user.index') }}"><strong> ผู้ใช้งาน </strong></a>
    </li>
    <li class="breadcrumb-item active">
        <strong> {{ $user->hrEmployee->full_name }} </strong>
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row col-12" style="padding-right: 0px;">
                <div class="col-md-6">
                    <span class="d-inline">
                        <h5> <strong> รายละเอียดผู้ใช้งาน </strong> </h5>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="ibox float-e-margins">
                <user-show-component
                    p-form-url   = "{{ route('expense.settings.user.index') }}"
                    :user        = "{{ json_encode($user) }}"
                    :permission-groups = "{{ json_encode($permissionGroups) }}"
                    :permissions = "{{ json_encode($permissions) }}"
                ></user-show-component>
            </div>
        </div>
    </div>
@stop

@section('scripts')
@stop