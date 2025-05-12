@extends('layouts.app')

@section('breadcrumb')
    {{-- <li class="breadcrumb-item"> --}}
        {{-- <a href="{{ route('asap.index', ['type' => $asap->type]) }}">ASAP</a> --}}
    {{-- </li> --}}
    <li class="breadcrumb-item">
        <a href={{ route("invoice.index", []) }}>
            <strong>Invoices</strong>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href={{ route("invoice.show", [$invoice->id]) }}>
            <strong>{{$invoice->inv_number}}</strong>
        </a>
    </li>
    <li class="breadcrumb-item">
        Submit To Approval
    </li>
@endsection

@section('content')
<div class="row">
    <div class="col text-truncate">
        <div class="page-title">
            <h3>Submit To Approval</h3>
        </div>
        <div class="card">
            <div class="card-body">

                <h3 class="text-center mb-4">Submit To Approval by following Hierarchy</h3>
                <div class="row">
                    <div class="col-12 col-md-6 offset-md-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="10px">Sequence</th>
                                    <th>Position</th>
                                </tr>
                            </thead>
                            @forelse ($invoice->getTypeApproval() as $list)
                            <tr>
                                <td>{{ $list->seq_number }}</td>
                                <td>
                                    {{ $list->role->name }}
                                    @include('project.submit-to-approvals._users', [
                                        'departmentCode' => $invoice->getDepartment(),
                                        'approvable'    => $invoice
                                    ])
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="100" class="text-centerre">Hierarchy Not Found</td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
                @if ($invoice->canSubmit() && $invoice->getTypeApproval())
                {!! Form::open(['route' => ['invoice.submit-to-approval.store', $invoice->id], 'method' => 'post'
                    ]) !!}
                    <div class="text-center">
                        {!! Form::submit('Submit To Approval', ['class' => 'btn btn-primary']) !!}
                    </div>
                {!! Form::close() !!}
                
                @endif
            </div>
        </div>
    </div>
</div>
@endsection