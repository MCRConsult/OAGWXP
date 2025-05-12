@extends('layouts.app')
@section('breadcrumb')
@endsection
@section('content')
<div class="row">
    <div style="width: 200px">
        @include('e-expenses.requisition._menu')
    </div>
    <div class="col">
        {!! Form::open(['route' => ['requisition.submit-to-approval.store', $requisition->id], 'method' => 'post'
                        ]) !!}
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-1">Submit To Approval by following Hierarchy {{ $requisition->getDepartmentCode() }}</h3>
                <h5 align="center" class="pt-0">Hierarchy : {{ !!$requisition->getTypeApproval()
                                                                    ? $requisition->getTypeApproval()->first()->hiereachyName->name ?? ''
                                                                    : ''}}</h5>
                @if (! $requisition->getTypeApproval())
                    <h5 class="text-muted font-weight-bold text-center"> Not set up Hierarchy</h5>
                @else
                    <div class="row">
                        <div class="col-12 col-md-6 offset-md-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="10px">Sequence</th>
                                        <th>Position</th>
                                    </tr>
                                </thead>
                                @forelse ($requisition->getTypeApproval() ?? [] as $list)
                                    <tr>
                                        <td>{{ $list->seq_number }}</td>
                                        <td>
                                            {{ $list->role->name }}
                                            @include('submit-to-approvals._users', [
                                                'departmentCode'  => $requisition->getDepartmentCode(),
                                                'approvable'      => $requisition,
                                                'departmentUunit' => $requisition->replaceType(),
                                            ])
                                        </td>
                                    </tr>
                                @empty
                                     <tr>
                                        <td colspan="2"> No data </td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>

                    @if ($requisition->canSubmit())
                        <div class="text-center">
                            {!! Form::submit('Submit To Approval', ['class' => 'btn btn-primary', 'onclick' => 'this.form.submit(); this.disabled=true;']) !!}
                        </div>
                    @endif
                @endif
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
