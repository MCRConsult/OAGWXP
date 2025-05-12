<div class="card">
    <div class="card-body">
        <h5>
            Status Approve Timeline
        </h5>
        <table class="table">
            <tbody>
                @foreach ($approveHeader
                    ->approvalLines()
                    ->orderBy('seq', 'asc')
                    ->get() as $approvalLine )
                <tr>
                    <td class="bg-black" width="10%">
                        <i class="fas fa-arrow-alt-circle-right tw-text-teal"></i>
                        <td class="pl-0 pr-0" width="70%">
                            <label class="tw-text-grey-darker tw-font-medium">
                                {{ $approvalLine->role->name }}
                            </label>

                            @foreach (
                                $approvalLine->getApprovableUsersFromDepartment($invoice->getDepartment()) as $user)
                                <div><i class="fa fa-user tw-text-grey-dark mr-1"></i> {{ $user->lastname }}</div>
                            @endforeach
                        </td>
                        <td width="20%">
                            <span class="{{ $approvalLine->statusBadge() ?? '' }}">
                                {{ $approvalLine->status }}
                            </span>
                        </td>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>