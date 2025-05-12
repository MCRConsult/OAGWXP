@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href={{ route("requisition.index") }}>
            <strong>BUDGET REQUISITION</strong>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href={{ route("requisition-line.index", $requisition->id) }}>
            <strong>Requisition Lines</strong>
        </a>
    </li>
@endsection
@section('content')
    @php
        $allowWithdraw = auth()->user()->allowToWithdraw();
    @endphp
    <div class="row">
        <div style="width: 200px">
            @include('e-expenses.requisition._menu')
        </div>
        <div class="col text-truncate">
            <requisition-form-line
                :old="{{ json_encode(Session::getOldInput()) }}"
                :departments="{{ json_encode($departments) }}"
                :check-user-department = "{{ json_encode($checkUserDepartment) }}"
                :requisition="{{ json_encode($requisition) }}"
                :ou="{{ json_encode(session('operating_unit_name')) }}"
                :addition-items="{{ json_encode($additionItems) }}"
                :petty-cash-items="{{ json_encode($pettyCashItems) }}"
                :uoms="{{ json_encode($uoms) }}"
                :group-req-lines="{{ json_encode($groupRequisitionLinesNew) }}"
                inline-template>
                <div class="card" v-loading="loadingSubmitForm">
                    @if ( in_array($requisition->status, ['draft', 'rejected']) )
                        <div class="card-body" style="background: #eff4ff">
                            <div class="form-group">
                                {{-- {!! Form::open(['route' => ['requisition-line.store', $requisition->id], 'method' => 'post']) !!} --}}
                                {{-- FORM --}}
                                @include('e-expenses.requisition.line._form')
                                {{-- <div align="right">
                                    {!! Form::submit('Save', ['class' => 'btn btn-primary ']) !!}
                                </div>
                                {!! form::close() !!} --}}
                            </div>
                        </div>
                    @endif

                    {{-- @include('e-expenses.requisition._item_line') --}}

                    <create-requisition-line
                        v-if="!loadingSubmitForm"
                        :group-lines="groupLines"
                        :requisition="{{ json_encode($requisition) }}"
                        :departments="{{ json_encode($departments) }}"
                        :check-user-department = "{{ json_encode($checkUserDepartment) }}"
                        :petty-cash-items="{{ json_encode($pettyCashItems) }}"
                        :addition-items="{{ json_encode($additionItems) }}"
                        :ou="{{ json_encode(session('operating_unit_name')) }}"
                        :uoms="{{ json_encode($uoms) }}"
                        @updatevalue='update'>
                    </create-requisition-line>
                </div>
            </requisition-form-line>
        </div>
    </div>
@endsection
@section('footer-js')
	<script type="text/javascript">
	    setTimeout( function() {
	        var body = $('body');
	        if (body.hasClass('sidebar-fixed')) {
	            if (body.hasClass('sidebar-lg-show')) {
	                body.removeClass('sidebar-lg-show');
	            }
	        } else {
	            if (body.hasClass('sidebar-lg-show')) {
	                body.removeClass('sidebar-lg-show');
	            }
	        }
	    }, 500);
	</script>
@endsection
