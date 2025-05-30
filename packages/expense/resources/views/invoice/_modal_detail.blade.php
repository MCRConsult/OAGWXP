<div class="modal fade detail_{{ $line->id }}" id="detail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> รายละเอียดเพิ่มเติม </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 1.5rem;">
                <form id='detail-form'>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group text-left" style="padding: 5px;">
                                <label class="control-label" style="margin-bottom: 0.4rem;">
                                    <strong> ชื่อสั่งจ่าย </strong> &nbsp;
                                </label><br>
                                {{ optional($line->supplier)->vendor_name }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group text-left" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> เลขที่บัญชีธนาคาร <span class="text-danger"> *</span></strong>
                                </label><br>
                                {{ $line->bank_account_number }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group text-left" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> แผนงาน </strong>
                                </label><br>
                                {{ optional($line->budgetPlan)->description }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group text-left" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> ประเภทรายจ่าย </strong>
                                </label><br>
                                {{ optional($line->budgetType)->description }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group text-left" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> ประเภทค่าใช้จ่าย </strong>
                                </label><br>
                                {{ optional($line->expense)->description }}
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group text-left" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> รายการบัญชี </strong>
                                </label><br>
                                {{ $line->expense_account }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group text-left" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> จำนวนเงิน <span class="text-danger"> * </span></strong>
                                </label><br>
                                {{ number_format($line->amount, 2) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group text-left" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> เลขที่ใบเสร็จรับเงิน </strong>
                                </label><br>
                                {{ optional($line->arReceipt)->receipt_number ?? '-' }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group text-left" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> ภาษีมูลค่าเพิ่ม </strong>
                                </label><br>
                                {{ optional($line->tax)->tax ?? '-' }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group text-left" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> จำนวนเงินภาษีมูลค่าเพิ่ม </strong>
                                </label><br>
                                {{ number_format($line->tax_amount, 2) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group text-left" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> ภาษีหัก ณ ที่จ่าย </strong>
                                </label><br>
                                {{ optional($line->wht)->description ?? '-' }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group text-left" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> จำนวนเงินภาษีหัก ณ ที่จ่าย </strong>
                                </label><br>
                                {{ number_format($line->wht_amount, 2) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group text-left" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> เลขที่ใบเสร็จรับเงินคงเหลือ </strong>
                                </label><br>
                                {{ $line->remaining_receipt_number ?? '-' }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group text-left" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> คำอธิบายรายการ </strong>
                                </label><br>
                                {{ $line->description }}
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>