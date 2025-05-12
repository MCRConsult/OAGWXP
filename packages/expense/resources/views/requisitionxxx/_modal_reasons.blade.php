@php
    $reasons = $requisition->reasons;
@endphp
<div class="modal fade" id="modalReasons{{$requisition->id}}" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Rejected,Hold Reasons</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                @if (count($reasons))
                    <table class="table">
                        <tr>
                            <th width="8%">
                                No.
                            </th>
                            <th>
                                Type
                            </th>
                            <th width="50%">
                                Reason
                            </th>
                            <th width="10%">
                                
                            </th>
                        </tr>
                        @foreach ($reasons as $reason)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    {{ $reason->reason_type }}
                                </td>
                                <td>
                                    {{ $reason->description }}
                                </td>
                                <td>
                                    
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <p>No Reasons</p>
                @endif
            </div>
        </div>
    </div>
</div>