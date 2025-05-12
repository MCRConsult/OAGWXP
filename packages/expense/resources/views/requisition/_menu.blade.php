<div class="tw-font-bold tw-text-lg tw-text-grey-dark mb-2">MENU</div>
<nav class="nav flex-column">
    <ol class="list-group">
        <a class="nav-link">
            Summary
        </a>
        {{-- <a class="nav-link {{ Request::routeIs('requisition.show') ? 'active-c' : '' }}" href="{{ route("requisition.show", [$requisition->id]) }}">
            Summary
        </a>
        @if (in_array($requisition->status , ['draft', 'hold', 'rejected']) && $requisition->status_clear != 'waiting clear')
            <a class="nav-link {{ Request::routeIs('requisition-line.index') ? 'active-c' : '' }}" href="{{ route('requisition-line.index', [$requisition->id]) }}">
                Edit Line
            </a>
        @endif
        @if (in_array($requisition->status , ['approved', 'hold', 'rejected'])
            && $requisition->status_clear == 'waiting clear'
            && !$requisition->canReEnc())
            <a class="nav-link {{ Request::routeIs('clear-requisition.index') ? 'active-c' : '' }}" href="{{ route('clear-requisition.index', [$requisition->id]) }}">
                Clear
            </a>
        @endif
        <a href="#" class="nav-link" data-toggle="modal" data-target="#modalAttach{{ $requisition->id }}"
            title="Attach">
            Attach File
        </a>
        @if (in_array($requisition->status , ['rejected', 'hold', 'Rejected by Accounting']))
            <a href="#" class="nav-link" data-toggle="modal" data-target="#modalReasons{{ $requisition->id }}"
                title="Reasons">
                Reasons
            </a>
        @endif
        @if ($requisition->canSubmit())
        <hr class="my-2 tw-w-full">
            <a class="btn btn-success nav-link" href="{{ route('requisition.submit-to-approval.index', [$requisition->id]) }}">
                Submit to Approve
            </a>
        @elseif($requisition->canApprove())
        <hr class="my-2 tw-w-full">
            <a class="btn btn-secondary nav-link" href="{{ route('requisition.approve.index', [$requisition->id]) }}">
                To be approved
            </a>
        @endif
        @if ($requisition->req_type == 'Prepayment')
            <hr class="my-2 tw-w-full">
            @if ($requisition->status_clear != null)
                <a class="btn btn-secondary nav-link" href="{{ route('requisition.pdf', [$requisition->id, 'type' => 'Adv. Payment']) }}" target="_bank">
                    PDF - Adv. Payment
                </a>
                <hr class="my-2 tw-w-full">
                <a class="btn btn-secondary nav-link" href="{{ route('requisition.pdf', [$requisition->id, 'type' => 'Clear Adv. Payment']) }}" target="_bank">
                    PDF - Clear Payment
                </a>
            @else
                <a class="btn btn-secondary nav-link" href="{{ route('requisition.pdf', [$requisition->id, 'type' => 'Adv. Payment']) }}" target="_bank">
                    PDF
                </a>
            @endif
        @else
            <hr class="my-2 tw-w-full">
            <a class="btn btn-secondary nav-link" href="{{ route('requisition.pdf', [$requisition->id, 'type' => 'Standard']) }}" target="_bank">
                PDF
            </a>
        @endif --}}
        {{-- @if($requisition->canReEnc() && auth()->user()->allowToInterfaceLogs())
            <hr class="my-2 tw-w-full">
            <form method="post" action="{{ route('requisition.re-enc', [$requisition->id]) }}">
                @csrf
                <button class="btn btn-success nav-link"
                    onclick="
                    if(confirm('Please confirm to re-encumbrance.') === true){
                        this.disabled = true;
                        this.form.submit();
                    }else return false;"
                    style="width: 100%">
                    Re-Encumbrance
                </button>
            </form>
        @endif --}}
    </ol>
</nav>
{{-- @include('e-expenses.requisition._modal_attach') --}}
{{-- @include('e-expenses.requisition._modal_reasons') --}}
