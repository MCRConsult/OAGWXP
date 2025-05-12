<div class="modal fade detail_{{ $line->id }}" id="detail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> รายละเอียดเพิ่มเติม </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id='detail-form'>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> ทะเบียนรถยนต์ </strong>
                                </label><br>
                                {{ $line->vehicle_no }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> เลขที่กรมธรรม์ </strong>
                                </label><br>
                                {{ $line->policy_no }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> ประเภทน้ำมัน </strong>
                                </label><br>
                                {{ $line->vehicle_oil_type }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> ประเภทค่าสาธารณูปโภค </strong>
                                </label><br>
                                {{ $line->utility_type }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> อาคาร/รหัสลูกค้า/ธพส. </strong>
                                </label><br>
                                {{ $line->utility_detail }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> เลขที่ใบแจ้งหนี้ </strong>
                                </label><br>
                                {{ $line->invoice_no }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> วันที่ใบแจ้งหนี้ </strong>
                                </label><br>
                                {{ date('d-m-Y', strtotime($line->invoice_date)) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> จำนวนหน่วยที่ใช้ </strong>
                                </label><br>
                                {{ $line->unit_quantity }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> วันที่รับ </strong>
                                </label><br>
                                {{ date('d-m-Y', strtotime($line->receipt_date)) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="padding: 5px;">
                                <label class="control-label">
                                    <strong> เลขที่หนังลือ </strong>
                                </label><br>
                                {{ $line->receipt_no }}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>