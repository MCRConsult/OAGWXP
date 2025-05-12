<div class="tw-font-bold tw-text-lg tw-text-grey-dark mb-2">MENU</div>
<nav class="nav flex-column">
    <ol class="list-group">
        <a class="nav-link {{ Request::routeIs('invoice.show') ? 'active-c' : '' }}" href="{{ route("invoice.show", [$invoice->id]) }}">
            Summary
        </a>
        @if ( in_array($invoice->status, ['draft']) )
            <a class="nav-link {{ Request::routeIs('invoice.create.step2') ? 'active-c' : '' }}" href="{{ route('invoice.create.step2', [$invoice->id]) }}">
                Change Requisition
            </a>
        @endif
        @if ( in_array($invoice->status, ['draft', 'waiting clear']) )
            <a class="nav-link {{ Request::routeIs('invoice.create.step3') ? 'active-c' : '' }}" href="{{ route('invoice.create.step3', [$invoice->id]) }}">
                Edit Line
            </a>
        @endif
        <a href="#" class="nav-link" data-toggle="modal" data-target="#modalAttach{{ $invoice->id }}"
            title="Attach">
            Attach File
        </a>
        {{-- @if ($invoice->canSubmit()) --}}
        @if ( in_array($invoice->status, ['draft', 'waiting clear']) )
            <hr class="my-2 tw-w-full">
            <form method="post" action="{{ route('invoice.interface', [$invoice->id]) }}">
                @csrf
                <button class="btn btn-success nav-link"
                    onclick="
                    if(confirm('Please confirm to Interface.') === true){
                        this.disabled = true; 
                        this.form.submit();
                    }else return false;"
                    style="width: 100%">
                    Submit to Interface
                </button>
            </form>
        @endif
        @if ( in_array($invoice->status, ['waiting clear']) )
            <hr class="my-2 tw-w-full">
            <a href="#" class="btn btn-warning nav-link" data-toggle="modal" data-target="#modalHold{{ $invoice->id }}"
                title="Hold">
                Hold
            </a>
            @include('e-expenses.invoice._modal_hold')
        @endif
        {{-- @if ( in_array($invoice->status, ['incomplete']) && auth()->user()->allowToInterfaceLogs() )
            <hr class="my-2 tw-w-full">
            <form method="post" action="{{ route('invoice.re-interface', [$invoice->id]) }}">
                @csrf
                <button class="btn btn-success nav-link"
                    onclick="
                    if(confirm('Please confirm to Re-Interface.') === true){
                        this.disabled = true; 
                        this.form.submit();
                    }else return false;"
                    style="width: 100%">
                    Re-Interface
                </button>
            </form>
        @endif --}}

        {{-- @elseif($invoice->canApprove()) --}}
            {{-- <a class="btn btn-secondary nav-link" href="{{ route('invoice.approve.index', [$invoice->id]) }}">
                Approve
            </a> --}}
        {{-- @endif --}}
    </ol>
</nav>
@include('e-expenses.invoice._modal_attach')