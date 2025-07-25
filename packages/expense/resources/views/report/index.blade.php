@extends('layouts.app')
@section('title', 'รายงาน')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <strong> รายงาน </strong>
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row col-12" style="padding-right: 0px;">
                <div class="col-md-6">
                    <span class="d-inline">
                        <h5> <strong> {{ $reportName }} </strong> </h5>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="ibox float-e-margins">
                @if ($type == 'REQUISITION')
                    <requisition-report-component
                        p-form-url  = "{{ route('expense.report.requisition') }}"
                        p-token     = "{{ csrf_token() }}"
                        :p-search   = "{{ json_encode(request()->all()) }}"
                    ></requisition-report-component>
                @endif
                {{-- ========================================================================= --}}
                @if ($type == 'INVOICE')
                    <invoice-report-component
                        p-form-url  = "{{ route('expense.report.invoice') }}"
                        p-token     = "{{ csrf_token() }}"
                        :p-search   = "{{ json_encode(request()->all()) }}"
                    ></invoice-report-component>
                @endif
            </div>
        </div>
    </div>
@stop

@section('scripts')
@stop