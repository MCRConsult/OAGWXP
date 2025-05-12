@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href={{ route("requisition.index") }}>
            <strong>BUDGET REQUISITION</strong>
        </a>
    </li>
    <li class="breadcrumb-item"><a href={{ route("requisition.show", $requisition->id) }}><strong>{{ $requisition->req_number }}</strong></a></li>
    <li class="breadcrumb-item">
        <a href={{ route("clear-requisition.index", $requisition->id) }}>
            <strong>Clear Requisition</strong>
        </a>
    </li>
@endsection
@section('content')
    <div class="row">
        <div class="col text-truncate">
            <div class="card">
                <div class="card-body" >
                    <div class="form-group">
                        {!! Form::open(['route' => ['clear-requisition.store', $requisition->id], 'method' => 'post']) !!}
                        @include('e-expenses.requisition.clear._form')
                        @if ($requisition->status_clear == 'waiting clear')
                            <div align="center">
                                {!! Form::submit('Save', ['class' => 'btn btn-primary ']) !!}
                            </div>
                        @endif
                        {!! form::close() !!}
                    </div>
                    @include('e-expenses.requisition._modal_attach')
                </div>
                {{-- @include('e-expenses.requisition._item_line') --}}
            </div>
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
