@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href={{ route("requisition.index") }}>
            <strong>BUDGET REQUISITION</strong>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href={{ route("requisition-pay.index", $requisition->id) }}>
            <strong>Pay For</strong>
        </a>
    </li>
@endsection
@section('content')
    <div class="row">
        <div style="width: 200px">
            @include('e-expenses.requisition._menu')
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body" style="background: #eff4ff">
                    <div class="form-group">
                        {!! Form::open(['route' => ['requisition-pay.store', $requisition->id], 'method' => 'post']) !!}
                        @include('e-expenses.requisition.pay._form')
                        <div align="right">
                            {!! Form::submit('Save', ['class' => 'btn btn-primary ']) !!}
                        </div>
                        {!! form::close() !!}
                    </div>
                </div>
                @include('e-expenses.requisition._item_pay', ['type' => 'Expenses',])
            </div>
        </div>
    </div>
@endsection
