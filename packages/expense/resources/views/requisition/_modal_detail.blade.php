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
                        <div class="col-md-3 text-left">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label" style="margin-bottom: 0.4rem;">
                                    <strong> ชื่อสั่งจ่าย </strong> &nbsp;
                                </label><br>
                                {{ $line->supplier->vendor_name }}
                            </div>
                        </div>
                        <div class="col-md-3 text-left">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> เลขที่บัญชีธนาคาร </strong>
                                </label><br>
                                {{ $line->bank_account_number }}
                            </div>
                        </div>
                        <div class="col-md-3 text-left">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> แผนงาน </strong>
                                </label><br>
                                {{ $line->budgetPlan->description }}
                            </div>
                        </div>
                        <div class="col-md-3 text-left">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> ประเภทรายจ่าย </strong>
                                </label><br>
                                {{ $line->budgetType->description }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 text-left">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> ประเภทค่าใช้จ่าย </strong>
                                </label><br>
                                {{ $line->expenseType->description }}
                            </div>
                        </div>
                        <div class="col-md-9 text-left">
                            <div class="form-group text-left" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> รายการบัญชี </strong>
                                </label><br>
                                {{ $line->expense_account }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 text-left">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> จำนวนเงิน </strong>
                                </label><br>
                                {{ number_format($line->amount, 2) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> คำอธิบายรายการ </strong>
                                </label><br>
                                {{ $line->description }}
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> ทะเบียนรถยนต์ </strong>
                                </label><br>
                                {{ $line->vehicle_number ?? '-' }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> เลขที่กรมธรรม์ </strong>
                                </label><br>
                                {{ $line->policy_number ?? '-' }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> ประเภทน้ำมัน </strong>
                                </label><br>
                                {{ optional($line->vehicleOilType)->description ?? '-' }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> ประเภทค่าสาธารณูปโภค </strong>
                                </label><br>
                                {{ optional($line->utilityType)->description ?? '-' }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> อาคาร/รหัสลูกค้า/ธพส. </strong>
                                </label><br>
                                {{ optional($line->utilityDetail)->description ?? '-' }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> เลขที่ใบแจ้งหนี้ </strong>
                                </label><br>
                                {{ $line->invoice_number ?? '-' }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> วันที่ใบแจ้งหนี้ </strong>
                                </label><br>
                                {{ $line->invoice_date? date('d-m-Y', strtotime($line->invoice_date)): '-' }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> จำนวนหน่วยที่ใช้ </strong>
                                </label><br>
                                {{ $line->unit_quantity ?? '-'}}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> วันที่รับ </strong>
                                </label><br>
                                {{ $line->receipt_date? date('d-m-Y', strtotime($line->receipt_date)): '-' }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> เลขที่หนังสือ </strong>
                                </label><br>
                                {{ $line->receipt_number ?? '-' }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> เลขที่ใบเสร็จรับเงินคงเหลือ </strong>
                                </label><br>
                                {{ $line->remaining_receipt_number ?? '-' }}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>