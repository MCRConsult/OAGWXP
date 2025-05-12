<div class="card-body">
    <h3 class="card-title">Pay For</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="7%">#</th>
                <th width="20%">Pay For</th>
                <th width="20%">Bank Name</th>
                <th width="20%">Bank Account Number</th>
                <th class="text-right">Amount</th>
                <th width="15%">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requisitionPays as $pay)
                <tr>
                    <td>
                        {{ $loop->iteration }}
                    </td>
                    <td>
                        {{ $pay->bank_account_name }}
                    </td>
                    <td>
                        {{ $pay->bank->value_description }}
                    </td>
                    <td>
                        {{ $pay->bank_account_number }}
                    </td>
                    <td class="text-right">
                        {{ number_format($pay->amount,2) }}
                    </td>
                    <td>
                        <div class="form-inline">
                            <a href="#" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#pay{{ $pay->id }}"
                                title="Edit">
                                Edit
                            </a>
                            <form onsubmit="return confirm('Do you want to delete this item?')" action="{{ route('requisition-pay.destroy', [$requisition->id, $pay->id]) }}" method="post">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                        @include('e-expenses.requisition._modal_edit_pay')
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>