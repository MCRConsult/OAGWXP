@extends('layouts.app')
@section('title', 'รายงาน')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('expense.report.index') }}"><strong> รายงาน </strong></a>
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row col-12" style="padding-right: 0px;">
                <div class="col-md-6">
                    <span class="d-inline">
                        <h5> <strong> รายงานทะเบียนคุมหลักฐานขอเบิก </strong> </h5>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="ibox float-e-margins">
                {{-- <report-component
                    p-form-url          = "{{ route('expense.requisition.index') }}"
                    p-token             = "{{ csrf_token() }}"
                    :p-search           = "{{ json_encode(request()->all()) }}"
                    :p-invoice-types    = "{{ json_encode($invoiceTypes) }}"
                    :p-statuses         = "{{ json_encode($statuses) }}"
                    p-date-js-format    = "{{ trans('date.js-format') }}"
                ></report-component> --}}
            </div>
        </div>
    </div>
@stop

@section('scripts')
@stop