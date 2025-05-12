@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href={{ route("invoice.index", []) }}>
            <strong>Invoices</strong>
        </a>
    </li>
    <li class="breadcrumb-item">
        Requisition
    </li>
@endsection
@section('content')
    <create-invoice
        :requisitions = "{{ json_encode($requisitions) }}"
        :department-set = "{{ json_encode($departmentSet) }}"
        :campus-set = "{{ json_encode($campusSet) }}"
        :au-account-set = "{{ json_encode($auAccountSet) }}"
        :departments = "{{ json_encode($departments) }}"
        {{-- :suppliers = "{{ json_encode($suppliers) }}" --}}
        :terms = "{{ json_encode($terms) }}"
        :currencies = "{{ json_encode($currencies) }}"
        :payments = "{{ json_encode($payments) }}"
        :types = "{{ json_encode($types) }}"
        :statuses = "{{ json_encode($statuses) }}"
        :campuses = "{{ json_encode($campuses) }}"
        :ou="{{ json_encode(session('operating_unit_name')) }}"
        :user-id="{{ json_encode(auth()->user()->id) }}"
        :dates="{{ json_encode($dates) }}"
        :cashier-points="{{ json_encode($cashierPoint) }}"
    > </create-invoice>
@endsection