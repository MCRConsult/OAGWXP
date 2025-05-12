<div class="form-group">
    <div class="row">
        <div class="col-md-5">
            <div class="form-row">
                <div class="form-group col-12">
                    <label class="required">Pay For</label>
                    <div class="input-group">
                        <input class="form-control" type="text" name="bank_account_name" id="bank_account_name" placeholder="Account Name" autocomplete="off" required>
                    </div>
                </div>
                <div class="form-group col-12">
                    <label class="required">Bank Name</label>
                    <div class="input-group">
                        <select name="bank_name" required class="form-control">
                            <option value=""></option>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank->flex_value_id }}">{{ $bank->flex_value }}, {{ $bank->value_description }}</option>
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
                        <input class="form-control" type="text" name="bank_account_number" id="bank_account_number" autocomplete="off" maxlength="15" placeholder="xxxxxxxxxxx" required>
                    </div>
                </div>
                <div class="form-group col-12">
                    <label class="required" class="w-100">
                        Amount
                    </label>
                    <div class="input-group">
                        <input class="form-control" type="number" name="amount" id="amount" placeholder="Amount" autocomplete="off" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>