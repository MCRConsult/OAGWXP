<div class="modal fade" id="pay{{$pay->id}}" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('requisition-pay.update', [$requisition->id, $pay->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h4 class="modal-title">Edit Pay</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label class="required">Bank Account Name</label>
                                        <div class="input-group">
                                            <input class="form-control" value="{{ $pay->bank_account_name }}" type="text" name="bank_account_name" id="bank_account_name" placeholder="Account Name" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <label class="required">Bank Name</label>
                                        <div class="input-group">
                                            <select name="bank_name" required class="form-control">
                                                <option value=""></option>
                                                @foreach ($banks as $bank)
                                                    <option value="{{ $bank->flex_value_id }}"
                                                    {{ $pay->bank_name == $bank->flex_value_id ? 'selected' : '' }}
                                                        >{{ $bank->value_description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label class="required" class="w-100">
                                            Bank Account Number
                                        </label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" value="{{ $pay->bank_account_number }}" name="bank_account_number" id="bank_account_number" autocomplete="off" maxlength="15" placeholder="xxxxxxxxxxx" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <label class="required" class="w-100">
                                            Amount
                                        </label>
                                        <div class="input-group">
                                            <input class="form-control" type="number" value="{{ $pay->amount }}" name="amount" id="amount" placeholder="Amount" autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>