@if ($approveHeader->isNotRejected())
    @if ($canUserApprove && $approveHeader->isNotRejected())
    <h5 align="center" class="mb-3">Approval Action for this request</h5>
    <div class="row inline">
        <div class="col-md-6 text-right ml-0">
            {!! Form::open(['route' => ['approve.update', $approveHeader->id], 'method' => "PATCH" ]) !!}
            {!! Form::hidden('approval_result', 'approved') !!}
            <button class="tw-bg-blue hover:tw-bg-blue-dark tw-text-white tw-font-bold tw-py-2 tw-px-4 rounded"><i
                    class="fas fa-check-circle"></i>
                Approve
            </button>
            {!! Form::close() !!}
        </div>
        <div class="col-md-6">
            {!! Form::open(['route' => ['approve.update', $approveHeader->id], 'method' => "PATCH" ]) !!}
            {!! Form::hidden('approval_result', 'rejected') !!}
            <button class="tw-bg-red-dark hover:tw-bg-red-darker tw-text-white tw-font-bold tw-py-2 tw-px-4 rounded">
                <i class="fas fa-times-circle"></i>
                Reject
            </button>
            {!! Form::close() !!}
        </div>
    </div>
    @endif
@endif
