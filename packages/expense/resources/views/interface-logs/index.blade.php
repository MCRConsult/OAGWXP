@extends('layouts.app')
@section('breadcrumb')
<li class="breadcrumb-item">
    <a href={{ route("e-expense.interface-logs.index") }}>
        <strong>E-Expense Interfaces</strong>
    </a>
</li>
@endsection
@section('content')
<div class="row">
	<div class="col">
		<div class="card">
			<div class="card-header d-flex align-items-center" style="background-color: #ffffff;">
				<div class="col-md-8">
					<h1> E-Expense Interfaces Logs </h1>
				</div>
				<div class="col-md-4">
					<div class="row">
						<div class="col-sm-11"></div>
						<div class="col-sm-1" style="padding-right: 2px;">
							{{-- <a href="{{ route('interface') }}" class="btn btn-sm btn-info" style="float: right; font-size: 14px; color: #ffffff;background-color: #ffd400; border-color: #ffd400;"> Interface </a> --}}
						</div>
						{{-- <div class="col-sm-5" style="padding-left: 2px;">
							<a href="{{ route('interface.reverse') }}" class="btn btn-sm btn-info" style="float: left; font-size: 14px; color: #ffffff; background-color: #fb7f00; border-color: #fb7f00;"> Reverse </a>
						</div> --}}
					</div>
				</div>
            </div>
            @php
                $permission = auth()->user()->allowToInterfaceLogs();
                $checkReqPermission = checkPermission(auth()->user()->web_permission, 'REQUISITION') == 'REQUISITION';
                $checkInvPermission = checkPermission(auth()->user()->web_permission, 'INVOICE') == 'INVOICE';
            @endphp
            {!! Form::open(['route' => 'e-expense.interface-logs.index', 'id' => 'form-import','class' => 'form-horizontal', 'method'=>'get']) !!}
                <e-expenses-interface-logs
                    :request="{{ json_encode($request->all()) }}"
                    :check-req-permission="{{ json_encode($checkReqPermission) }}"
                    :check-inv-permission="{{ json_encode($checkInvPermission) }}"
                    inline-template>
                    <div>
                        <input type="hidden" name="type" v-model="activeName">
                        <el-tabs type="card" class="card-body" v-model="activeName">
                            @if ($checkReqPermission)
                                <el-tab-pane label="Budget Requisition" name="encumbrance">
                                    <div class="card-body">
                                        @include('e-expenses.interface-logs._search_form_encumbrance')
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <th width="3%" class="text-center"> Status </th>
                                                    <th width="11%" class="text-center"> Date/Time </th>
                                                    <th width="13%"> BR Number </th>
                                                    <th width="14%"> Batch Number </th>
                                                    <th > Message </th>
                                                    <th width="20%"> Action </th>
                                                </thead>
                                                <tbody>
                                                    @if (count($encumbrances) == 0)
                                                        <td colspan="5">
                                                            <div class="text-center">
                                                                <h3> NO DATA FOUND </h3>
                                                            </div>
                                                        </td>
                                                    @else
                                                        @foreach($encumbrances as $encumbrance)
                                                            <tr>
                                                                <td class="text-center">
                                                                    @if ($encumbrance->interface_flag == 'C')
                                                                        <span class="badge badge-success"> Success </span>
                                                                    @elseif($encumbrance->interface_flag == 'I')
                                                                        <span class="badge badge-info"> Inprocess </span>
                                                                    @else
                                                                        <span class="badge badge-danger"> Error </span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ date('d-m-Y H:i:s', strtotime($encumbrance->updated_at)) }}
                                                                </td>
                                                                <td class="no-wrap">
                                                                    <div title="{{ 'dr : '. $encumbrance->dr .', cr : '. $encumbrance->cr }}">
                                                                        {{ explode("JV_ENCUMBRANCE EXP_", $encumbrance->batch_name)[1] }}
                                                                        {{-- {{ $batch_no->interfaceable ? $batch_no->interfaceable->inv_number : '' }} --}}
                                                                        @if ($encumbrance->encumbrance_type == 'return')
                                                                            (RETURN)
                                                                        @else
                                                                            (RESERVE)
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                                <td class="no-wrap">
                                                                    {{ $encumbrance->batch_no }}
                                                                </td>
                                                                <td>
                                                                    {{ $encumbrance->interface_flag == 'C'? 'Completed': $encumbrance->interfaced_msg }}
                                                                </td>
                                                                <td>
                                                                    @if ($encumbrance->interface_flag != 'I' && $permission)
                                                                        <a class="btn btn-secondary btn-sm" href="{{ route('requisition.show', [$encumbrance->encumbrancable_id]) }}">
                                                                            View
                                                                        </a>
                                                                        <a href="{{ route('e-expense.interface.re-interface', ['batch_no' => $encumbrance->batch_no, 'type' => 'encumbrance']) }}" 
                                                                            onclick="return confirm('Please confirm to re-encumbrance.');" class="btn btn-sm btn-info" 
                                                                            style="font-size: 10px; color: #ffffff; background-color: #ffd400; border-color: #ffd400;"> 
                                                                            Re-Interface
                                                                        </a>
                                                                        <a href="{{ route('e-expense.interface.reverse', ['batch_no' => $encumbrance->batch_no, 'type' => 'encumbrance']) }}" 
                                                                            onclick="return confirm('Please confirm to reverse.');" class="btn btn-sm btn-danger"> 
                                                                            Reverse
                                                                        </a>
                                                                        <a href="{{ route('e-expense.interface.clear', ['batch_no' => $encumbrance->batch_no, 'type' => 'encumbrance']) }}" 
                                                                            onclick="return confirm('Please confirm to clear.');" class="btn btn-sm btn-success"> 
                                                                            Clear
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        @if(isset($encumbrances))
                                            <div class="ibox-content">
                                                <div class="text-right">
                                                    {!! $encumbrances->links() !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </el-tab-pane>
                            @endif
                            @if ($checkInvPermission)
                                <el-tab-pane label="Invoice" name="interface">
                                    <div class="card-body">
                                        @include('e-expenses.interface-logs._search_form_interface')
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <th width="5%" class="text-center"> Status </th>
                                                    <th width="11%" class="text-center"> Date/Time </th>
                                                    <th width="13%"> Invoice Number </th>
                                                    <th width="14%"> Batch Number </th>
                                                    <th > Message </th>
                                                    <th width="15%"> Action </th>
                                                </thead>
                                                <tbody>
                                                    @if (count($interfaces) == 0)
                                                        <td colspan="5">
                                                            <div class="text-center">
                                                                <h3> NO DATA FOUND </h3>
                                                            </div>
                                                        </td>
                                                    @else
                                                        @foreach($interfaces as $interface)
                                                            <tr>
                                                                <td class="text-center">
                                                                    @if ($interface->interface_flag == 'C')
                                                                        <span class="badge badge-success"> Success </span>
                                                                    @elseif($interface->interface_flag == 'I')
                                                                        <span class="badge badge-info"> Inprocess </span>
                                                                    @else
                                                                        <span class="badge badge-danger"> Error </span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ date('d-m-Y H:i:s', strtotime($interface->updated_at)) }}
                                                                </td>
                                                                <td class="no-wrap">
                                                                    {{ $interface->invoice_number }}
                                                                    {{-- {{ $interface->interfaceable ? $interface->interfaceable->inv_number : '' }} --}}
                                                                    @if ($interface->document_category == 'AU_CLEAR PREPAYMENT')
                                                                        (CLEAR)
                                                                    @elseif($interface->document_category == 'AU_PREPAYMENT')
                                                                        (ADV. PAY)
                                                                    @else
                                                                        (STD.)
                                                                    @endif
                                                                </td>
                                                                <td class="no-wrap">
                                                                    {{ $interface->batch_no }}
                                                                </td>
                                                                <td>
                                                                    {{ $interface->interface_flag == 'C'? 'Completed': $interface->interfaced_msg }}
                                                                </td>
                                                                <td>
                                                                    @if ($encumbrance->interface_flag != 'I' && $permission)
                                                                        <a class="btn btn-secondary btn-sm" href="{{ route('invoice.show', [$interface->interfaceable_id]) }}">
                                                                            View
                                                                        </a>
                                                                        <a href="{{ route('e-expense.interface.re-interface', ['batch_no' => $interface->batch_no, 'type' => 'invoice']) }}" 
                                                                            onclick="return confirm('Please confirm to re-interface.');" class="btn btn-sm btn-info" 
                                                                            style="font-size: 10px; color: #ffffff; background-color: #ffd400; border-color: #ffd400;"> 
                                                                            Re-Interface
                                                                        </a>
                                                                        <a href="{{ route('e-expense.interface.clear', ['batch_no' => $interface->batch_no, 'type' => 'invoice']) }}" 
                                                                            onclick="return confirm('Please confirm to clear.');" class="btn btn-sm btn-success"> 
                                                                            Clear
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        @if(isset($interfaces))
                                            <div class="ibox-content">
                                                <div class="text-right">
                                                    {!! $interfaces->links() !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </el-tab-pane>
                            @endif
                        </el-tabs>
                    </div>
                </e-expenses-interface-logs>
            {!! Form::close() !!}
		</div>
	</div>
</div>
@endsection
