<div class="form-group">
    {{-- message error --}}
    <div v-if="errors.any()" class="col-12">
        <div class="alert alert-danger mt-1">
            <ul class="mb-0">
                <li v-for="error in errors.all()">@{{ error }}</li>
            </ul>
        </div>
    </div>

    <div v-if="errorMessages.length > 0" class="col-12">
        <div class="alert alert-danger mt-1">
            <ul class="mb-0">
                <li v-for="error in errorMessages">@{{ error }}</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div :class="showCheckFund ? 'col-6' : 'col-12'">
            <template v-if="requisition.petty_cash == '1'">
                <div class="row">
                    <div class="col-12" style="padding: 0 !important;">
                        <div class="form-group col-12">
                            <label class="required">Item</label>
                            <div class="tw-rounded-lg">
                                <input type="hidden" v-model="item" name="item_id">
                                <el-select required class="w-100" size="medium" v-model="item" filterable clearable
                                    placeholder="Select" v-validate="'required'" name="Item">
                                    <el-option-group
                                        v-for="(index) in pettyCashItems"
                                        :key="index.category_id"
                                        :label="index.description">
                                        <el-option
                                            v-for="item in index.index_child"
                                            :key="item.category_id"
                                            :label="item.description"
                                            :value="item.category_id">
                                        </el-option>
                                    </el-option-group>
                                </el-select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4" style="padding: 0 !important;">
                        <div class="form-group col-12">
                            <label class="w-100 required">Pay For</label>
                            <div class="input-group">
                                <input type="hidden" name="bank_account_name" v-model="bank_account_name">
                                <el-select
                                    class="w-100"
                                    v-model="bank_account_name"
                                    filterable
                                    allow-create
                                    remote
                                    :disabled="checkPayFor"
                                    size="medium"
                                    placeholder="Name"
                                    :remote-method="remoteMethodSupplier"
                                    :loading="loadingSupplier">
                                    <el-option
                                        v-for="supplier in suppliers"
                                        :key="supplier.supplier_number"
                                        :label="supplier.alt_supplier_name + ' | ' + supplier.supplier_number"
                                        :value="supplier.alt_supplier_name">
                                    </el-option>
                                </el-select>
                            </div>
                        </div>
                    </div>
                    <div class="col-4" style="padding: 0 !important;">
                        <div class="form-group col-12">
                            <label class="w-100 required"> Tax ID </label>
                            <div class="input-group">
                                <el-input placeholder="xxxxxxxxxxx" size="medium" name="Tax ID" v-model="tax_payer_id"
                                autocomplete="off" v-validate="'required'" maxlength="13" :disabled="!checkMultiple && checkWithdraw && !checkOutsource"></el-input>
                            </div>
                        </div>
                    </div>
                    <div class="col-4" style="padding: 0 !important;">
                        <div class="form-group col-12">
                            <label class="w-100 required">
                                E-mail
                            </label>
                            <div class="input-group">
                                <el-input type="email" size="medium" placeholder="example@example.com" name="email" v-model="email"
                                autocomplete="off" v-validate="'required'" name="E-mail" :disabled="!checkMultiple && checkWithdraw && !checkOutsource"></el-input>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" v-model="c_item" name="c_item">
                        <el-checkbox class="w-100" v-model="c_item" border size="medium">Select Deduct (เลือกรายการหัก)</el-checkbox>
                    </div>
                </div>
                <div class="row" v-if="!c_item">
                    <div class="col-4" style="padding: 0 !important;">
                        <div class="form-group col-12">
                            <label>Department</label>
                            <div class="tw-rounded-lg">
                                <el-select class="w-100" size="medium" v-model="department" filterable placeholder="Select" >
                                    <el-option
                                        v-for="department in departments"
                                        :key="department.flex_value"
                                        :label="department.flex_value+' : '+department.value_description"
                                        :value="department.flex_value">
                                        <span style="padding-right:20px">@{{ department.flex_value+' : '+department.value_description }}</span>
                                    </el-option>
                                </el-select>
                            </div>
                        </div>
                    </div>
                    <div class="col-4" style="padding: 0 !important;">
                        <div class="form-group col-12">
                            <label class="w-100 required">Project</label>
                            <input type="hidden" name="project_id" v-model="project">
                            <div class="input-group">
                                <el-select class="w-100" size="medium" v-model="project" :loading="loadingProject" filterable
                                    placeholder="Select" :disabled="!department" 
                                    v-validate="'required'" name="Project">
                                    <el-option
                                        v-for="project in projects"
                                        :key="project.id"
                                        :label="project.project_number+' : '+project.name"
                                        :value="project.id">
                                        <span style="padding-right:20px">@{{ project.project_number+' : '+project.name }}</span>
                                    </el-option>
                                </el-select>
                            </div>
                        </div>
                    </div>
                    <div class="col-4" style="padding: 0 !important;">
                        <div class="form-group col-12">
                            <label class="required">Item</label>
                            <div class="tw-rounded-lg">
                                <input type="hidden" name="budget_id" v-model="budget">
                                <el-select class="w-100" size="medium" v-model="budget" :loading="loadingBudgets" filterable
                                    placeholder="Select" :disabled="!project" required v-validate="'required'" name="Item">
                                    <el-option-group
                                        v-for="(budgets,index) in groupBudgets"
                                        :key="index"
                                        :label="index">
                                        <el-option
                                            v-for="budget in sortBudgets(budgets)"
                                            :key="budget.id"
                                            :label="budget.index_number + '.' + budget.item_number + ' : '
                                                    + ( budget.description
                                                        ? (budget.item ? budget.item.description + ' - ' + budget.description : budget.description)
                                                        : (budget.item ? budget.item.description : '') )"
                                            :value="budget.id">
                                            <span style="padding-right:20px">@{{ budget.index_number + '.' + budget.item_number + ' : '
                                                + ( budget.description
                                                    ? (budget.item ? budget.item.description + ' - ' + budget.description : budget.description)
                                                    : (budget.item ? budget.item.description : '') ) }}</span>
                                        </el-option>
                                    </el-option-group>
                                </el-select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="c_item">
                    <div class="col-12" style="padding: 0 !important;">
                        <div class="form-group col-12">
                            <label class="required">Item</label>
                            <div class="tw-rounded-lg">
                                <input type="hidden" v-model="item" name="item_id">
                                <el-select required class="w-100" size="medium" v-model="item" filterable clearable
                                    placeholder="Select" v-validate="'required'" name="Item">
                                    <el-option-group
                                        v-for="(index) in additionItems"
                                        :key="index.category_id"
                                        :label="index.description">
                                        <el-option
                                            v-for="item in index.index_child"
                                            :key="item.category_id"
                                            :label="item.description"
                                            :value="item.category_id">
                                        </el-option>
                                    </el-option-group>
                                </el-select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="!showDescription">
                    <div class="col-4" style="padding: 0 !important;">
                        <div class="form-group col-12">
                            <label class="w-100 required">Pay For</label>
                            <div class="input-group">
                                <input type="hidden" name="bank_account_name" v-model="bank_account_name">
                                <el-select
                                    class="w-100"
                                    v-model="bank_account_name"
                                    filterable
                                    allow-create
                                    remote
                                    :disabled="checkPayFor"
                                    size="medium"
                                    placeholder="Name"
                                    :remote-method="remoteMethodSupplier"
                                    :loading="loadingSupplier">
                                    <el-option
                                        v-for="supplier in suppliers"
                                        :key="supplier.supplier_number"
                                        :label="supplier.alt_supplier_name + ' | ' + supplier.supplier_number"
                                        :value="supplier.alt_supplier_name">
                                    </el-option>
                                </el-select>
                            </div>
                        </div>
                    </div>
                    <div class="col-4" style="padding: 0 !important;">
                        <div class="form-group col-12">
                            <label class="w-100 required"> Tax ID </label>
                            <div class="input-group">
                                <el-input placeholder="xxxxxxxxxxx" size="medium" name="Tax ID" v-model="tax_payer_id"
                                autocomplete="off" v-validate="'required'" maxlength="13" :disabled="!checkMultiple && checkWithdraw && !checkOutsource"></el-input>
                            </div>
                        </div>
                    </div>
                    <div class="col-4" style="padding: 0 !important;">
                        <div class="form-group col-12">
                            <label class="w-100 required">
                                E-mail
                            </label>
                            <div class="input-group">
                                <el-input type="email" size="medium" placeholder="example@example.com" name="email" v-model="email"
                                autocomplete="off" v-validate="'required'" name="E-mail" :disabled="!checkMultiple && checkWithdraw && !checkOutsource"></el-input>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="showDescription">
                    <div class="col-12" style="padding: 0 !important;">
                        <div class="form-group col-12">
                            <label class="w-100">
                                Description
                            </label>
                            <div class="input-group">
                                <el-input type="textarea" placeholder="Description" name="description" v-model="description" autocomplete="off"
                                maxlength="255"></el-input>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <div class="col-6">
            <planned-budget class="p-3 tw-border tw-rounded tw-bg-blue-lightest" :selected-item="selectedBudget"
                :show="showCheckFund"  @fundweb="availableAmount">
            </planned-budget>
        </div>
    </div>
    <div class="row" v-if="showDescription">
        <div class="col-4" style="padding: 0 !important;">
            <div class="form-group col-12">
                <label class="w-100 required">Pay For</label>
                <div class="input-group">
                    <el-select
                        class="w-100"
                        v-model="bank_account_name"
                        filterable
                        allow-create
                        remote
                        :disabled="checkPayFor"
                        size="medium"
                        placeholder="Name"
                        :remote-method="remoteMethodSupplier"
                        :loading="loadingSupplier">
                        <el-option
                            v-for="supplier in suppliers"
                            :key="supplier.supplier_number"
                            :label="supplier.alt_supplier_name + ' | ' + supplier.supplier_number"
                            :value="supplier.alt_supplier_name">
                        </el-option>
                    </el-select>
                </div>
            </div>
        </div>
        <div class="col-4" style="padding: 0 !important;" v-if="!checkMultiple && checkWithdraw && !checkOutsource">
            <div class="form-group col-12">
                <label class="w-100 required"> Tax ID </label>
                <div class="input-group">
                    <el-input placeholder="xxxxxxxxxxx" size="medium" name="Tax ID" v-model="tax_payer_id"
                    autocomplete="off" maxlength="13" :disabled="!checkMultiple && checkWithdraw && !checkOutsource"></el-input>
                </div>
            </div>
        </div>
        <div class="col-4" style="padding: 0 !important;" v-else>
            <div class="form-group col-12">
                <label class="w-100 required"> Tax ID </label>
                <div class="input-group">
                    <el-input placeholder="xxxxxxxxxxx" size="medium" name="Tax ID" v-model="tax_payer_id"
                    autocomplete="off" v-validate="'required'" maxlength="13" :disabled="!checkMultiple && checkWithdraw && !checkOutsource"></el-input>
                </div>
            </div>
        </div>
        <div class="col-4" style="padding: 0 !important;" v-if="!checkMultiple && checkWithdraw && !checkOutsource">
            <div class="form-group col-12">
                <label class="w-100 required">
                    E-mail
                </label>
                <div class="input-group">
                    <el-input type="email" size="medium" placeholder="example@example.com" name="email" v-model="email"
                    autocomplete="off" name="E-mail" :disabled="!checkMultiple && checkWithdraw && !checkOutsource"></el-input>
                </div>
            </div>
        </div>
        <div class="col-4" style="padding: 0 !important;" v-else>
            <div class="form-group col-12">
                <label class="w-100 required">
                    E-mail
                </label>
                <div class="input-group">
                    <el-input type="email" size="medium" placeholder="example@example.com" name="email" v-model="email"
                    autocomplete="off" v-validate="'required'" name="E-mail" :disabled="!checkMultiple && checkWithdraw && !checkOutsource"></el-input>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-4" style="padding: 0 !important;">
            <div class="form-group col-12">
                <label>Bank Name</label>
                <div class="input-group">
                    <input type="hidden" v-model="bank" name="bank_name">
                    <el-select
                        class="w-100"
                        size="medium"
                        v-model="bank"
                        clearable
                        filterable
                        :disabled="!checkMultiple && checkWithdraw && !checkOutsource"
                        remote
                        placeholder="Please enter a keyword"
                        :remote-method="remoteMethodBank"
                        :loading="loadingBank">
                        <el-option
                            v-for="bank in banks"
                            :key="bank.bank_party_id"
                            :label="bank.flex_value_meaning+' : '+bank.description"
                            :value="bank.bank_party_id">
                        </el-option>
                    </el-select>
                </div>
            </div>
        </div>
        <div class="col-4" style="padding: 0 !important;">
            <div class="form-group col-12">
                <label>Bank branch <span style="font-size: x-small;color: red;">ระบุ 0+รหัสสามตัวแรกของ Bank Account</span></label>
                <div class="input-group">
                    <el-input class="w-100" name="bank_branch" size="medium" v-model="bank_branch"
                        :disabled="true"
                        {{-- :disabled="!checkMultiple && checkWithdraw && !checkOutsource" --}}
                    ></el-input>
                    {{-- <input type="hidden" v-model="bank_branch" name="bank_branch">
                    <el-select
                        class="w-100"
                        :disabled="!listBankBranchs.length"
                        v-model="bank_branch">
                        <el-option
                            v-for="branch in listBankBranchs"
                            :key="branch.branch_party_id"
                            :label="branch.branch_number+' : '+branch.bank_branch_name"
                            :value="branch.branch_party_id">
                        </el-option>
                    </el-select> --}}
                </div>
            </div>
        </div>
        <div class="col-4" style="padding: 0 !important;">
            <div class="form-group col-12">
                <label class="w-100">
                    Bank Account Number
                </label>
                <div class="input-group">
                    <el-input placeholder="xxxxxxxxxxx" name="bank_account_number" size="medium" v-model="bank_account_number"
                    autocomplete="off" maxlength="15" :disabled="!checkMultiple && checkWithdraw && !checkOutsource"></el-input>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6" style="padding: 0 !important;">
            <div class="form-group col-12">
                <label class="w-100">
                    Address1
                </label>
                <div class="input-group">
                    <el-input type="textarea" placeholder="Address1" name="address1" v-model="address1" autocomplete="off"
                    maxlength="150" :disabled="!checkMultiple && checkWithdraw && !checkOutsource"></el-input>
                </div>
            </div>
        </div>
        <div class="col-6" style="padding: 0 !important;">
            <div class="form-group col-12">
                <label class="w-100">
                    Address2
                </label>
                <div class="input-group">
                    <el-input type="textarea" placeholder="Address2" name="address2" v-model="address2" autocomplete="off"
                    maxlength="150" :disabled="!checkMultiple && checkWithdraw && !checkOutsource"></el-input>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-4" style="padding: 0 !important;">
            <div class="form-group col-12">
                <label class="required">
                    Quantity
                </label>
                {{-- <input type="hidden" v-model="form.totalMultiUnit" name="multiUnit_d"> --}}
                <form-multi-quantity v-model='totalAmountFromMultiUnit'
                    @update="updateQuantity"
                    :multi-qty-default="{{ old('multiple_quantity', json_encode([])) }}"
                    :uoms="{{ json_encode($uoms) }}"
                    @updateuom="updateUom"
                    @updatemulti="updateMulti">
                </form-multi-quantity>
                <div class="input-group">
                    <vue-numeric v-model="form.quantity" name="quantity" thousand-separator=""
                        :empty-value="{{ old('quantity', 0) }}" :minus="false" :precision="2" :min="1"
                        :max="999999.99" autocomplete="off" class="form-control"
                        v-validate="'required'" name="Quantity"></vue-numeric>
                    <div class="input-group-append">
                        <el-select name="uom_code" v-model="form.uom_code" filterable
                            size="medium" required v-validate="'required'">
                            <el-option
                                v-for="uom in uoms"
                                :key="uom.uom_code"
                                :label="uom.description"
                                :value="uom.uom_code">
                            </el-option>
                        </el-select>
                    </div>
                </div>
            </div>
{{-- -------------------------------------------------------------------------------------- --}}
            {{-- <div class="form-group col-12">
                <label class="required" class="w-100">
                    Quantity
                </label>
                <form-multi-quantity v-model='totalAmountFromMultiUnit'
                    @update="updateQuantity"
                    :multi-qty-default="{{ old('multiple_quantity', json_encode([])) }}"
                    :uoms="{{ json_encode($uoms) }}"
                    @updateuom="updateUom">
                </form-multi-quantity>
                <div class="input-group">
                    <vue-numeric v-model="form.quantity" name="quantity" thousand-separator=""
                        :empty-value="{{ old('quantity', 0) }}" :minus="false" :precision="2" :min="1"
                        :max="999999.99" autocomplete="off" class="form-control"
                        v-validate="'required'" name="Quantity"></vue-numeric>
                    <div class="input-group-append">
                        <el-select name="UOM" v-model="form.uom" filterable
                            size="medium" required v-validate="'required'">
                            <el-option
                                v-for="uom in uoms"
                                :key="uom.uom_code"
                                :label="uom.description"
                                :value="uom.uom_code">
                            </el-option>
                        </el-select>
                    </div>
                </div>
            </div> --}}
{{-- -------------------------------------------------------------------------------------- --}}
            {{-- <div class="form-group col-12">
                <label class="required" class="w-100">
                    Quantity
                </label>
                <div class="input-group">
                    <vue-numeric v-model="form.quantity" name="quantity" thousand-separator=""
                        :empty-value="{{ old('quantity', 0) }}" :minus="false" :precision="2" :min="1"
                        :max="999999.99" autocomplete="off" class="form-control"
                        v-validate="'required'" name="Quantity"></vue-numeric>
                    <div class="input-group-append">
                        <el-select name="UOM" v-model="form.uom" filterable
                            size="medium" required v-validate="'required'">
                            <el-option
                                v-for="uom in uoms"
                                :key="uom.uom_code"
                                :label="uom.description"
                                :value="uom.uom_code">
                            </el-option>
                        </el-select>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="col-4" style="padding: 0 !important;">
            <div class="form-group col-12">
                <label class="required">Price</label>
                <div class="input-group">
                    <vue-numeric v-model="form.price" name="price" thousand-separator=""
                        :empty-value="{{ old('price', 0) }}" :minus="c_item" :precision="2" :min="c_item ? -999999999.99 : 0"
                        :max="c_item ? 0 : 9999999999.99" autocomplete="off" class="form-control" v-validate="'required'"
                        name="Price"></vue-numeric>
                    <div class="input-group-append">
                        <span class="input-group-text">Baht</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4" style="padding: 0 !important;">
            <div class="form-group col-12">
                <label class="d-block text-right">Total Amount</label>
                <div class="text-right h5 pt-1">@{{ totalAmount }} Baht</div>
            </div>
        </div>
    </div>
    <div align="right">
        <button class='btn btn-primary' @click.prevent="submitToTemp"> Save </button>
    </div>
</div>
