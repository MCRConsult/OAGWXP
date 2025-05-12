@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href={{ route("invoice.index", []) }}>
            <strong>Invoices</strong>
        </a>
    </li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h1>Invoices</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        {!! Form::open(['name' => 'invoice_edit_form','route' => ['invoice.update', $invoice->id], 'method' => 'put']) !!}
            <header-invoice :invoice = "{{ json_encode($invoice) }}"
                {{-- :suppliers = "{{ json_encode($suppliers) }}" --}}
                :terms = "{{ json_encode($terms) }}"
                :currencies = "{{ json_encode($currencies) }}"
                :payments = "{{ json_encode($payments) }}"
                :ou="{{ json_encode(session('operating_unit_name')) }}"
                :au-account-set = "{{ json_encode($auAccountSet) }}"
                :cashier-points="{{ json_encode($cashierPoint) }}"
            >
            </header-invoice>
        {!! form::close() !!}
    </div>
</div>
@endsection