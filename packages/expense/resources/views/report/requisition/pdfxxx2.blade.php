<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="{{ base_path() }}/public/css/pdf.css" rel="stylesheet">
    <style type="text/css">
    </style>
</head>
<body>
    <div class="row">
        <div class="col-sm-12 text-center" align="center">
            <div style="font-size: 24px;">
                <strong> หลักฐานเอกสารส่งเบิก </strong>
            </div>
        </div>
    </div>
    <main style="width: 100%; margin-bottom: 10px; margin-top: 10px;">
        <table class="table table-bordered" style="border-collapse: collapse; border: 0.5px solid #ddd; padding: 0px; margin: 0px;">
            <thead>
                <tr>
                    <th width="4%" rowspan="2" style="font-size: 15px; text-align: center; border: 0.5px solid #000000; vertical-align: middle; padding: 1px;">
                        ว.ด.ป
                    </th>
                    <th width="5%" rowspan="2" style="font-size: 15px; text-align: center; border: 0.5px solid #000000; vertical-align: middle; padding: 1px;">
                        ประเภท
                    </th>
                    <th width="5%" rowspan="2" style="font-size: 15px; text-align: center; border: 0.5px solid #000000; vertical-align: middle; padding: 1px;">
                        สำนักงานผู้เบิกจ่าย
                    </th>
                    <th width="5%" rowspan="2" style="font-size: 15px; text-align: center; border: 0.5px solid #000000; vertical-align: middle; padding: 1px;">
                        เลขที่เอกสารส่งเบิก
                    </th>
                    <th width="6%" rowspan="2" style="font-size: 15px; text-align: center; border: 0.5px solid #000000; vertical-align: middle; padding: 1px;">
                        เจ้าหนี้/ผู้ขอเบิก
                    </th>
                    <th width="8%" rowspan="2" style="font-size: 15px; text-align: center; border: 0.5px solid #000000; vertical-align: middle; padding: 1px;">
                        รายการ
                    </th>
                    <th width="10%" rowspan="2" style="font-size: 15px; text-align: center; border: 0.5px solid #000000; vertical-align: middle; padding: 1px;">
                        รายการบัญชี
                    </th>
                    <th width="15%" colspan="3" style="font-size: 15px; text-align: center; border: 0.5px solid #000000; vertical-align: middle; padding: 1px;">
                        จำนวนเงิน
                    </th>
                </tr>
                <tr>
                    <th width="4%" style="font-size: 15px; text-align: center; border: 0.5px solid #000000; vertical-align: middle; padding: 1px;">
                        ขอเบิกจ่ายเงิน/ <br> สำรองจ่าย
                    </th>
                    <th width="4%" style="font-size: 15px; text-align: center; border: 0.5px solid #000000; vertical-align: middle; padding: 1px;">
                        เงินยืม
                    </th>
                    <th width="4.5%" style="font-size: 15px; text-align: center; border: 0.5px solid #000000; vertical-align: middle; padding: 1px;">
                        เงินยืม
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $currentPage = 1;
                    $rowCount = 0;
                    $maxRowsPerPage = 5; // จำนวนแถวสูงสุดต่อหน้า
                @endphp
                @foreach ($requisitions as $lineIndex => $requisition)
                    @php
                        $day = date('d', strtotime($requisition->req_date));
                        $month = thaiMonth(date('m', strtotime($requisition->req_date)), $full = true);
                        $reqYear = $requisition->req_date;
                        $year = date('Y', strtotime($reqYear)) + 543;
                        
                        // ตรวจสอบว่าจำเป็นต้องขึ้นหน้าใหม่หรือไม่
                        $linesCount = count($requisition->lines);
                        $needPageBreak = ($rowCount + $linesCount) > $maxRowsPerPage && $rowCount > 0;
                        if ($needPageBreak) {
                            $rowCount = 0;
                        }
                    @endphp
                        @foreach ($requisition->lines as $line)
                            {{-- <div > --}}
                                <tr style="{{ $needPageBreak && $lineIndex === 0 ? 'page-break-after: always;' : '' }}">
                                    @if ($loop->first)
                                        <td rowspan="{{ $linesCount }}"
                                            style="font-size: 12px; border: 0.5px solid #000000; text-align: center; padding: 5px;">
                                            {{ $day.' '.$month.' '.$year }}
                                        </td>
                                        <td rowspan="{{ $linesCount }}"
                                            style="font-size: 12px; border: 0.5px solid #000000; text-align: center; padding: 5px;">
                                            {{ $requisition->invoiceType->description }}
                                        </td>
                                        <td rowspan="{{ $linesCount }}"
                                            style="font-size: 12px; border: 0.5px solid #000000; text-align: center; padding: 5px;">
                                            {{ $requisition->document_category }}
                                        </td>
                                        <td rowspan="{{ $linesCount }}"
                                            style="font-size: 12px;  text-align: center; border: 0.5px solid #000000; padding: 5px;">
                                            {{ $requisition->req_number }}
                                        </td>
                                        <td rowspan="{{ $linesCount }}"
                                            style="font-size: 12px;  text-align: center; border: 0.5px solid #000000; padding: 5px;">
                                            {{ $requisition->user->hrEmployee->full_name }}
                                        </td>
                                    @endif
                                    <td style="font-size: 12px; border: 0.5px solid #000000; padding: 5px;">
                                        <div style="word-wrap: break-word; width: 230px;"> 
                                            {{ $line->expense_description }}
                                        </div>
                                    </td>
                                    <td style="font-size: 12px; text-align: left; border: 0.5px solid #000000; padding: 5px;">
                                        <div style="word-wrap: break-word; width: 250px;"> 
                                            {{ $line->expense_account }}
                                        </div>
                                    </td>
                                    <td style="font-size: 12px; text-align: right; border: 0.5px solid #000000; padding: 5px;">
                                        @if ($requisition->clear_flag != 'Y')
                                            {{ $requisition->invoice_type == 'STANDARD'? number_format($line->amount, 2): '' }}
                                        @endif
                                    </td>
                                    <td style="font-size: 12px; text-align: right; border: 0.5px solid #000000; padding: 5px;">
                                        {{-- @if (is_null($line->split_flag))
                                            {{ $requisition->invoice_type == 'PREPAYMENT'? number_format($lineOri->amount, 2): '' }}
                                        @else --}}
                                            {{ $requisition->invoice_type == 'PREPAYMENT'? number_format($line->amount, 2): '' }}
                                        {{-- @endif --}}
                                    </td>
                                    <td style="font-size: 12px; text-align: right; border: 0.5px solid #000000; padding: 5px;">
                                        {{ $requisition->clear_flag == 'Y'? number_format($line->actual_amount, 2): '' }}
                                    </td>
                                </tr>
                            {{-- </div> --}}
                            @php $rowCount++; @endphp
                        @endforeach
                    
                    @php $needPageBreak = false; @endphp
                @endforeach
            </tbody>
        </table>
    </main>
</body>
</html>
