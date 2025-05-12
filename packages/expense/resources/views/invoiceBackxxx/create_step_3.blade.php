@extends('layouts.app')
@section('breadcrumb')
<li class="breadcrumb-item">
    <a href={{ route("invoice.index") }}>
        <strong>Invoices</strong>
    </a>
</li>
<li class="breadcrumb-item">
    <a href={{ route("invoice.show", $invoice->id) }}>
        <strong>{{ $invoice->inv_number }}</strong>
    </a>
</li>
<li class="breadcrumb-item">
    <a href={{ route("invoice.create.step3", $invoice->id) }}>
        <strong>Step 3</strong>
    </a>
</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h3>Invoice : {{ $invoice->inv_number }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        {!! Form::open(['route' => ['invoice.store.step3', $invoice->id], 'method' => 'post']) !!}
        @include('e-expenses.invoice._form_step_3')
        {!! form::close() !!}
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
