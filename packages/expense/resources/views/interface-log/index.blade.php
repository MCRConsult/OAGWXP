@extends('layouts.app')
@section('title', 'ประวัติการอินเตอร์เฟซ')
@section('breadcrumb')
    <li class="breadcrumb-item active">
        <strong> ประวัติการอินเตอร์เฟซ </strong>
    </li>
@endsection
<style type="text/css">
    .sticky-col {
        position: sticky !important;
        background-color: #FFF;
        z-index: 9999;
        top:0px;
    }
</style>

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
            <invoice-interface-component
                p-form-url      = "{{ route('expense.invoice.interface-log') }}"
                :p-search       = "{{ json_encode(request()->all()) }}"
                :p-statuses     = "{{ json_encode($statuses) }}"
            ></invoice-interface-component>
            <div class="table-responsive mt-4" style="max-height: 600px;">
                <table class="table text-nowrap table-hover text-center" style="position: sticky; font-size: 14px;">
                    <thead>
                        <tr>
                            <th class="text-center sticky-col">
                                <div width="3%"> สถานะ </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div width="8%"> วันที่อินเตอร์เฟซ </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div width="10%"> เลขที่ใบสำคัญ </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div width="10%"> เลขที่เอกสาร </div>
                            </th>
                            <th class="text-center sticky-col">
                                <div width="20%"> รายละเอียด </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($interfaces as $interface)
                            <tr>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    {!! $interface->status_icon !!}
                                </td>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    {{ $interface->invoice_date_format }}
                                </td>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    {{ $interface->voucher_num }}
                                </td>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    {{ $interface->invoice_num }}
                                    <div style="color: #858585;">
                                        <small> Batch#: {{ $interface->web_batch_no }} </small>
                                    </div>
                                </td>
                                <td class="text-center text-nowrap" style="vertical-align: middle;">
                                    {{ $interface->interface_msg }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="pull-right">
                        @if (count($interfaces) > 0)
                            {{ $interfaces->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
@stop