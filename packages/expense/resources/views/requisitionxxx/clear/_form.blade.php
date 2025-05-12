<div class="row">
    <div class="col-6">
        <h5 class="card-title d-inline">
            {{ $requisition->req_number }}
        </h5>
        <a href="#" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modalAttach{{ $requisition->id }}"
            style="margin-bottom: 5px;"
            title="Attach">
            Attach File
        </a>
    </div>
    <div class="col-6">
        <h5 class="text-right">Currency : {{ $requisition->currency }}</h5>
    </div>
</div>
<form-clear-requisition
:p-group-lines="{{ json_encode($groupRequisitionLines) }}"
:requisition="{{ json_encode($requisition) }}"
:banks="{{ json_encode($banks) }}"
inline-template>
    <div>
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-body" style="background: #eff4ff">
                        <strong>
                            Request : @{{ numberFormat(request_total) }}
                        </strong>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-body" style="background: #eff4ff">
                        <strong>
                            Actual : @{{ numberFormat(used_total) }}
                        </strong>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-body" style="background: #eff4ff">
                        <strong>
                            Diff : @{{ numberFormat(diff_total) }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive" style="max-height: 500px; overflow-x: scroll;">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th colspan="4" style="background-color: lightgrey;"></th>
                        <th colspan="3" style="background-color: #ccffff;" class="text-center">REQUEST</th>
                        <th colspan="3" style="background-color: #b3ffb3;" class="text-center">ACTUAL</th>
                        <th style="background-color: #ffff80;"></th>
                        <th colspan="8" style="background-color: #ffdab3;" class="text-center">INFORMATION</th>
                    </tr>
                    <tr>
                        <th width="7%" style="background-color: lightgrey;">No.</th>
                        <th width="10%" style="background-color: lightgrey;">Department</th>
                        <th width="10%" style="background-color: lightgrey;">Project</th>
                        <th width="15%" style="background-color: lightgrey;">Item</th>
                        <th width="10%" style="background-color: #ccffff;" class="text-right">Qty</th>
                        <th width="10%" style="background-color: #ccffff;" class="text-right">Price</th>
                        <th width="10%" style="background-color: #ccffff;" class="text-right">Total</th>
                        <th class="text-right" style="background-color: #b3ffb3;">Qty</th>
                        <th class="text-right" style="background-color: #b3ffb3;">Price</th>
                        <th class="text-right" style="background-color: #b3ffb3;">Total</th>
                        <th width="16%" style="background-color: #ffff80;" class="text-right">Diff</th>
                        <th width="16%" style="background-color: #ffdab3;">Pay for</th>
                        <th width="16%" style="background-color: #ffdab3;">Bank</th>
                        <th width="16%" style="background-color: #ffdab3;">Bank Branch</th>
                        <th width="16%" style="background-color: #ffdab3;">Account Number</th>
                        <th width="16%" style="background-color: #ffdab3;">Tax ID</th>
                        <th width="16%" style="background-color: #ffdab3;">Email</th>
                        <th width="16%" style="background-color: #ffdab3;">Adress1</th>
                        <th width="16%" style="background-color: #ffdab3;">Adress2</th>
                    </tr>
                </thead>
                <tbody v-for="(lines, key, parent_index) in pGroupLines">
                    <tr>
                        <td width="7%" style="background-color: lightgrey;">@{{ parent_index+1 }}</td>
                        <td style="background-color: lightgrey;"> @{{ key.split(':')[0] }}</td>
                        <td style="background-color: lightgrey;"> @{{ key.split(':')[1] }}</td>
                        <td style="background-color: lightgrey;"> @{{ key.split(':')[2] }}</td>
                        <td style="background-color: #ccffff;"></td>
                        <td style="background-color: #ccffff;"></td>
                        <td style="background-color: #ccffff;" class="text-right">
                            <strong>@{{ numberFormat(old_total['line'+key]) }}</strong>
                        </td>
                        <td style="background-color: #b3ffb3;"></td>
                        <td style="background-color: #b3ffb3;"></td>
                        <td style="background-color: #b3ffb3;" class="text-right">
                            <strong>@{{ numberFormat(total['line'+key]) }}</strong>
                        </td>
                        <td style="background-color: #ffff80;"></td>{{--  Diff  --}}
                        <td style="background-color: #ffdab3;">
                            <input v-if="diff_total < 0 && parent_index == 0" style="width: 200px" class="form-control" type="text" v-model="bank_account_name">
                        </td>
                        <td style="background-color: #ffdab3;">
                            <select v-if="diff_total < 0 && parent_index == 0" style="width: 250px;" class="form-control" v-model="bank">
                                <option
                                    v-for="bank in banks"
                                    :value="bank.bank_party_id"
                                    :key="bank.bank_party_id"
                                    :label="bank.bank_party_id+' : '+bank.description">
                                </option>
                            </select>
                        </td>
                        <td style="background-color: #ffdab3;">
                            <input v-if="diff_total < 0 && parent_index == 0" style="width: 200px" class="form-control" type="text" v-model="bank_branch">
                        </td>
                        <td style="background-color: #ffdab3;">
                            <input v-if="diff_total < 0 && parent_index == 0" style="width: 200px" class="form-control" type="text" v-model="account_number">
                        </td>
                        <td style="background-color: #ffdab3;">
                            <input v-if="diff_total < 0 && parent_index == 0" style="width: 200px" class="form-control" type="text" v-model="tax_id">
                        </td>
                        <td style="background-color: #ffdab3;">
                            <input v-if="diff_total < 0 && parent_index == 0" style="width: 200px" class="form-control" type="text" v-model="email">
                        </td>
                        <td style="background-color: #ffdab3;">
                            <input v-if="diff_total < 0 && parent_index == 0" style="width: 200px" class="form-control" type="text" v-model="address1" maxlength="255">
                        </td>
                        <td style="background-color: #ffdab3;">
                            <input v-if="diff_total < 0 && parent_index == 0" style="width: 200px" class="form-control" type="text" v-model="address2" maxlength="255">
                        </td>
                    </tr>
                    <template v-for="(line, index) in lines">
                        <line-clear :ref="'line'+key" 
                            :line="line"
                            :index="index+1"
                            :parent-index="parent_index+1"
                            :banks="{{ json_encode($banks) }}"
                            :p-bank-account-name="bank_account_name"
                            :p-bank="bank"
                            :p-bank-branch="bank_branch"
                            :p-account-number="account_number"
                            :p-tax-id="tax_id"
                            :p-email="email"
                            :p-address1="address1"
                            :p-address2="address2"
                            :uoms="{{ json_encode($uoms) }}"
                            @updatevalue='updateValue'>
                        </line-clear>
                    </template>
                </tbody>
            </table>
        </div>
        <br>
    </div>
</form-clear-requisition>
