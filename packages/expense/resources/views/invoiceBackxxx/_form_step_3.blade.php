@php
    $allowToSeeCoa = auth()->user()->allowToSeeCoa();
@endphp

<form-step-3 inline-template>
<div>
    <div class="card-body">
        <h5 class="card-title">Detail</h5>
        <invoice-form-line 
        ref="tableline"
        :p-group-lines="{{ json_encode($groupLinesSelected) }}"
        :invoice="{{ json_encode($invoice) }}"
        :p-has-add-line-by-invoice="{{ json_encode($hasAddLineByInvoice) }}"
        :project-set="{{ json_encode($projectSet) }}"
        inline-template>
            <div v-loading="loading">
                <div class="table-responsive" style="max-height: 500px; overflow-x: scroll;">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th width="7%">#</th>
                                <th>Item</th>
                                <th>Fund</th>
                                <th>Campus</th>
                                <th>Department</th>
                                <th>Project</th>
                                <th>Account</th>
                                <th>Budget</th>
                                <th>Reserve1</th>
                                <th class="text-right">Request Amount</th>
                                @if ($invoice->status_clear == 'waiting clear')
                                    <th>Actual Total Price</th>
                                @endif
                                <th class="text-right">Exclude VAT</th>
                                <th>VAT Code</th>
                                <th class="text-right">VAT Amount</th>
                                @if ($invoice->status_clear == 'waiting clear')
                                    <th class="text-right">Diff</th>
                                @endif
                                <th class="text-right">Total</th>
                                <th>WHT Code</th>
                                <th class="text-right">WHT Amount</th>
                                <th>Pay For</th>
                                <th>Bank</th>
                                <th>Bank Branch</th>
                                <th>Account Number</th>
                                <th>Tax ID</th>
                                <th>E-mail</th>
                                <th>Address1</th>
                                <th>Address2</th>
                                <th v-if="hasAddLineByInvoice"></th>
                            </tr>
                        </thead>
                        <tbody v-for="(lines, key, parent_index) in groupLines">
                            <tr>
                                <td :colspan="colspanSize" class="h5">
                                    Index @{{ parent_index+1 }} @{{ key }}
                                </td>
                                <td class="text-right h5">
                                    @{{ numberFormat(total['line'+key]) }}
                                </td>
                            </tr>
                            <template v-for="(line, index) in lines">
                                {{-- ref="line" --}}
                                <line-form :ref="'line'+key" :line="line"
                                :vat-codes="{{ json_encode($vatCodes) }}"
                                :wht-codes="{{ json_encode($whtCodes) }}"
                                :see-coa="{{ json_encode($allowToSeeCoa) }}"
                                :banks="{{ json_encode($banks) }}"
                                :invoice="{{ json_encode($invoice) }}"
                                :department-set="{{ json_encode($departmentSet) }}"
                                :department-set-for-deduct="{{ json_encode($departmentSetForDeduct) }}"
                                :project-set="accountProjectSet"
                                :fund-set="{{ json_encode($fundSet) }}"
                                :campus-set="{{ json_encode($campusSet) }}"
                                :has-add-line-by-invoice="hasAddLineByInvoice"
                                :index="index+1"
                                :parent-index="parent_index+1"
                                @updatevalue='updateValue'
                                @updatetable='updateTable'></line-form>
                                {{-- <template v-if="line.distributions.length > 0">
                                    <sub-line-form 
                                        :distributions="line.distributions"
                                        :vat-codes="{{ json_encode($vatCodes) }}"
                                        :wht-codes="{{ json_encode($whtCodes) }}"
                                        :see-coa="{{ json_encode($allowToSeeCoa) }}">
                                    </sub-line-form>
                                </template> --}}
                            </template>
                        </tbody>
                    </table>
                </div>
                {{-- @if($invoice->type == 'Standard') --}}
                    <modal-addition-item :reqs="{{ json_encode($reqs) }}" 
                        :addition-items="{{ json_encode($additionItems) }}" 
                        :invoice="{{ json_encode($invoice) }}"
                        :line-lists="{{ json_encode($lineLists) }}"
                        :banks="{{ json_encode($banks) }}"
                        @updatetable='updateTable'>
                    </modal-addition-item>
                {{-- @endif --}}
                <br>
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5>Summary Invoice</h5>
                                    </div>
                                </div>
                                <div class="row text-right">
                                    <div class="col-5">
                                        <strong>Total Request Amount :</strong>
                                    </div>
                                    <div class="col-7">
                                        <strong> @{{ numberFormat(total_rq) }} Baht </strong>
                                        {{-- <div class="text-right h5 pt-1">@{{ totalAmount }} Baht</div> --}}
                                    </div>
                                </div>
                                @if ($invoice->status == "waiting clear" && $invoice->status_clear == "waiting clear")
                                    <div class="row text-right">
                                        <div class="col-5">
                                            <strong>Total Actual Amount :</strong>
                                        </div>
                                        <div class="col-7">
                                            <strong> @{{ numberFormat(total_actual_rq) }} Baht </strong>
                                            {{-- <div class="text-right h5 pt-1">@{{ totalAmount }} Baht</div> --}}
                                        </div>
                                    </div>
                                    <div class="row text-right">
                                        <div class="col-5">
                                            <strong>Total Diff :</strong>
                                        </div>
                                        <div class="col-7">
                                            <strong> @{{ numberFormat(total_diff) }} Baht </strong>
                                            {{-- <div class="text-right h5 pt-1">@{{ totalAmount }} Baht</div> --}}
                                        </div>
                                    </div>
                                @endif
                                <div class="row text-right">
                                    <div class="col-5">
                                        <strong>Total Deduct :</strong>
                                    </div>
                                    <div class="col-7">
                                        <strong> @{{ numberFormat(total_deduct) }} Baht </strong>
                                        {{-- <div class="text-right h5 pt-1">@{{ totalAmount }} Baht</div> --}}
                                    </div>
                                </div>
                                <div class="row text-right">
                                    <div class="col-5">
                                        <strong>Total Request Net :</strong>
                                    </div>
                                    <div class="col-7">
                                        <strong> @{{ numberFormat(total_rq_net) }} Baht </strong>
                                        {{-- <div class="text-right h5 pt-1">@{{ totalAmount }} Baht</div> --}}
                                    </div>
                                </div>
                                <div class="row text-right">
                                    <div class="col-5">
                                        <strong>Total Exclude VAT :</strong>
                                    </div>
                                    <div class="col-7">
                                        <strong> @{{ numberFormat(total_ex_vat) }} Baht </strong>
                                        {{-- <div class="text-right h5 pt-1">@{{ totalAmount }} Baht</div> --}}
                                    </div>
                                </div>
                                <div class="row text-right">
                                    <div class="col-5">
                                        <strong>Total VAT :</strong>
                                    </div>
                                    <div class="col-7">
                                        <strong> @{{ numberFormat(total_vat) }} Baht </strong>
                                        {{-- <div class="text-right h5 pt-1">@{{ totalAmount }} Baht</div> --}}
                                    </div>
                                </div>
                                <div class="row text-right">
                                    <div class="col-5">
                                        <strong>Total Amount :</strong>
                                    </div>
                                    <div class="col-7">
                                        <strong> @{{ numberFormat(total_amount) }} Baht </strong>
                                    </div>
                                </div>
                                <div class="row text-right">
                                    <div class="col-5">
                                        <strong>Total WHT :</strong>
                                    </div>
                                    <div class="col-7">
                                        <strong> @{{ numberFormat(total_wht) }} Baht </strong>
                                        {{-- <div class="text-right h5 pt-1">@{{ totalAmount }} Baht</div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </invoice-form-line>
    </div>

    {{-- <div class="card-body">
        <h5 class="card-title">Pay For</h5>
        <invoice-form-pay ref="tablepay" :pays="{{ json_encode($pays) }}" inline-template>
            <div>
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th width="7%">#</th>
                            <th width="15%">Pay For</th>
                            <th width="25%">Bank Name</th>
                            <th width="15%">Bank Account Number</th>
                            <th>PND Type</th>
                            <th>WHT Amount</th>
                            <th>WHT Base</th>
                            <th width="15%" class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pays as $index => $pay)
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
                                <td >
                                    {{ $pay->bank_account_number }}
                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <div class="row">
                                        <input type="hidden" name="pay_amount[{{$index}}][data]" 
                                        value='{"account_name":"{{$pay->bank_account_name}}","bank_name":"{{$pay->bank->value_description}}","account_number":"{{$pay->bank_account_number}}"}'>
                                        <div class="col-12">
                                            <input class="form-control" type="number" 
                                            name="pay_amount[{{$index}}][amount]" 
                                            v-model="pay_amount[{{ $index }}]" min="0" 
                                            :key="{{ $index }}"
                                            required>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div>
                    <label class="d-block text-right">Total Amount</label>
                    <div class="text-right h5 pt-1">@{{ totalAmount }} Baht</div>
                </div>
            </div>
        </invoice-form-pay>
    </div> --}}

    <div align="center" v-show="buttonVisible">
        {!! Form::submit('Save', ['class' => 'btn btn-primary ']) !!}
    </div>
</div>
</form-step-3>