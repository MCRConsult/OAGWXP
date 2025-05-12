@php
    $condition = in_array($requisition->status , ['draft', 'hold']) 
                    || ($requisition->status == 'rejected')
                    || ($requisition->status == 'approved' && $requisition->status_clear == 'waiting clear');
@endphp
<div class="modal fade" id="modalAttach{{$requisition->id}}" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Attach File</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                @forelse ($requisition->getMedia() as $media)
                    <div class="text-nowrap">
                        <a href="{{ $media->getFullUrl() }}" target="_blank">{{ $media->file_name }}</a>
                        @if ( $condition )
                            <form action="{{ route('requisition.delete-attach', [$requisition->id, $media->id]) }}" method="POST" onsubmit="return confirm('Please confirm to delete this item.');" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit">
                                    <i class="fas fa-trash text-danger"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p>No File</p>
                @endforelse
            </div>
            @if ( $condition )
                {!! Form::open(['route' => ['requisition.attach', $requisition->id], 'method' => 'POST', 'files' => true] ) !!}
                    <div class="modal-body">
                        {{ Form::file('files[]', ['multiple']) }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary btn-sm">Save</button>
                    </div>
                {!! Form::close() !!}
            @endif
        </div>
    </div>
</div>