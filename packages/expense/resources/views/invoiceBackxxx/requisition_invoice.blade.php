@extends('layouts.app')
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href={{ route("invoice.index", []) }}>
            <strong>Invoices</strong>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href={{ route("invoice.show", [$invoice->id]) }}>
            <strong>{{ $invoice->inv_number }}</strong>
        </a>
    </li>
    <li class="breadcrumb-item">
        Requisition
    </li>
@endsection
@section('content')
    <requisition :requisitions = "{{ json_encode($requisitions) }}"
        :department-set = "{{ json_encode($departmentSet) }}"
        :campus-set = "{{ json_encode($campusSet) }}"
        :departments = "{{ json_encode($departments) }}"
        :lines-use = "{{ json_encode($linesUse) }}"
        :invoice = "{{ json_encode($invoice) }}"
        :types = "{{ json_encode($types) }}"
        :statuses = "{{ json_encode($statuses) }}"
        :campuses = "{{ json_encode($campuses) }}"
        :dates="{{ json_encode($dates) }}"
        :user-id="{{ json_encode(auth()->user()->id) }}"
    ></requisition>
@endsection