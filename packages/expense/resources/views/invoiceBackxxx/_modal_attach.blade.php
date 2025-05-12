<div class="modal fade" id="modalAttach{{$invoice->id}}" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Attach File</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                @php
                    $groupRequisitions = $invoice->lines()->with('requisition')->get()->groupBy('req_id');
                    $requisitions = [];
                    foreach ($groupRequisitions as $key => $reqs) {
                        foreach ($reqs as $key => $req) {
                            $requisitions[] = $req->requisition;
                            break;
                        }
                    }
                @endphp
                
                @forelse ($requisitions as $requisition)
                    <h6>{{ $requisition->req_number }}</h6>
                    <ul>
                        @forelse ($requisition->getMedia() as $media)
                            <li class="text-nowrap">
                                <a href="{{ $media->getFullUrl() }}" target="_blank">{{ $media->file_name }}</a>
                            </li>
                        @empty
                                <li>No File</li>
                        @endforelse
                    </ul>
                @empty
                    <p>No File</p>
                @endforelse
            </div>
        </div>
    </div>
</div>