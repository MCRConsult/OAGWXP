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
        {!! Form::open(['name' => 'requisition_form', 'route' => ['requisition.update', $requisition->id], 'method' => 'post']) !!}
        @method('PUT')
        @include('e-expenses.requisition._form')
        {!! form::close() !!}
    </div>
</div>
@endsection
