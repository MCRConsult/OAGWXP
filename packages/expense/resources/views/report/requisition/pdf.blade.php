<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
    <title> หลักฐานเอกสารส่งเบิก </title>
    <style type="text/css">
        .page-break {
            page-break-before: always;
        }
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
            @if (count($requisitions) > 0)
                <tbody>
                    @php
                        $currentPage = 1;
                        $rowCount = 0;
                        $maxRowsPerPage = 4; // จำนวนแถวสูงสุดต่อหน้า
                        // ===========
                        $summary_standard_amount = 0;
                        $summary_advance_amount = 0;
                        $summary_actaul_amount = 0;
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
                                $currentPage++;
                            }
                        @endphp
                        
                        @if ($needPageBreak)
                            </tbody>
                            </table>
                            </main>
                            <div class="page-break"></div>
                            
                            <!-- เริ่มหน้าใหม่ -->
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
                        @endif
                        @if ($requisition->clear_reference_id && $param->req_number_from != $param->req_number_to)
                            @php
                                continue;
                            @endphp
                        @else
                            @foreach ($requisition->lines as $index => $line)
                                @php
                                    $summary_standard_amount += $requisition->invoice_type == 'STANDARD' && is_null($requisition->clear_flag)
                                                                ? $line->amount: 0;
                                    $summary_advance_amount += $requisition->invoice_type == 'PREPAYMENT'
                                                                || $requisition->invoice_type == 'STANDARD' 
                                                                    && $requisition->clear_flag == 'Y' && is_null($line->split_flag)
                                                                ? $line->amount: 0;
                                    $summary_actaul_amount += $requisition->clear_flag == 'Y'? $line->actual_amount: 0;
                                @endphp  
                                @if (is_null($requisition->clear_flag))
                                    <tr>
                                        <td style="font-size: 12px; border: 0.5px solid #000000; text-align: center; padding: 5px;">
                                            @if ($loop->first)
                                                {{ $day.' '.$month.' '.$year }}
                                            @endif
                                        </td>
                                        <td style="font-size: 12px; border: 0.5px solid #000000; text-align: center; padding: 5px;">
                                            @if ($loop->first)
                                                {{ $requisition->invoiceType->description }}
                                            @endif
                                        </td>
                                        <td style="font-size: 12px; border: 0.5px solid #000000; text-align: center; padding: 5px;">
                                            @if ($loop->first)
                                                {{ $requisition->document_category }}
                                            @endif
                                        </td>
                                        <td style="font-size: 12px; text-align: center; border: 0.5px solid #000000; padding: 5px;">
                                            @if ($loop->first)
                                                {{ $requisition->req_number }}
                                            @endif
                                        </td>
                                        <td style="font-size: 12px; text-align: center; border: 0.5px solid #000000; padding: 5px;">
                                            @if ($loop->first)
                                                {{ $requisition->user->hrEmployee->full_name }}
                                            @endif
                                        </td>
                                        <td style="font-size: 12px; border: 0.5px solid #000000; padding: 5px;">
                                            <div style="word-wrap: break-word; width: 190px;"> 
                                                {{ $line->expense_description }}
                                            </div>
                                        </td>
                                        <td style="font-size: 12px; text-align: left; border: 0.5px solid #000000; padding: 5px;">
                                            <div style="word-wrap: break-word; width: 280px;"> 
                                                {{ $line->expense_account }} <br> {{ expenseDesc($line->expense_account) }}
                                            </div>
                                        </td>
                                        <td style="font-size: 12px; text-align: right; border: 0.5px solid #000000; padding: 5px;">
                                            {{ $requisition->invoice_type == 'STANDARD'? number_format($line->amount, 2): '' }}
                                        </td>
                                        <td style="font-size: 12px; text-align: right; border: 0.5px solid #000000; padding: 5px;">
                                            {{ $requisition->invoice_type == 'PREPAYMENT'? number_format($line->amount, 2): '' }}
                                        </td>
                                        <td style="font-size: 12px; text-align: right; border: 0.5px solid #000000; padding: 5px;"> </td>
                                    </tr>
                                @else
                                    @if ($requisition->clear)
                                        <tr>
                                            <td style="font-size: 12px; border: 0.5px solid #000000; text-align: center; padding: 5px;">
                                                @if ($loop->first)
                                                    {{ $day.' '.$month.' '.$year }}
                                                @endif
                                            </td>
                                            <td style="font-size: 12px; border: 0.5px solid #000000; text-align: center; padding: 5px;">
                                                @if ($loop->first)
                                                    <div>
                                                        {{ $requisition->clear->invoiceType->description }}
                                                    </div>
                                                    <div>
                                                        {{ $requisition->invoiceType->description }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td style="font-size: 12px; border: 0.5px solid #000000; text-align: center; padding: 5px;">
                                                @if ($loop->first)
                                                    {{ $requisition->document_category }}
                                                @endif
                                            </td>
                                            <td style="font-size: 12px; text-align: center; border: 0.5px solid #000000; padding: 5px;">
                                                @if ($loop->first)
                                                    <div>
                                                        {{ $requisition->clear->req_number }}
                                                    </div>
                                                    <div>
                                                        {{ $requisition->req_number }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td style="font-size: 12px; text-align: center; border: 0.5px solid #000000; padding: 5px;">
                                                @if ($loop->first)
                                                    {{ $requisition->user->hrEmployee->full_name }}
                                                @endif
                                            </td>
                                            <td style="font-size: 12px; border: 0.5px solid #000000; padding: 5px;">
                                                <div style="word-wrap: break-word; width: 190px;"> 
                                                    {{ $line->expense_description }}
                                                </div>
                                            </td>
                                            <td style="font-size: 12px; text-align: left; border: 0.5px solid #000000; padding: 5px;">
                                                <div style="word-wrap: break-word; width: 280px;"> 
                                                    {{ $line->expense_account }} <br> {{ expenseDesc($line->expense_account) }}
                                                </div>
                                            </td>
                                            <td style="font-size: 12px; text-align: right; border: 0.5px solid #000000; padding: 5px;"> </td>
                                            <td style="font-size: 12px; text-align: right; border: 0.5px solid #000000; padding: 5px;">
                                                {{ is_null($line->split_flag)? number_format(prepamentDetail($requisition->clear, $line->seq_number), 2): '' }}
                                            </td>
                                            <td style="font-size: 12px; text-align: right; border: 0.5px solid #000000; padding: 5px;">
                                                {{ $requisition->clear_flag == 'Y'? number_format($line->actual_amount, 2): '' }}
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                               
                                @php $rowCount++; @endphp
                            @endforeach
                        @endif
                        @php $needPageBreak = false; @endphp
                    @endforeach
                    <tr>
                        <th colspan="7" style="font-size: 16px; text-align: right; border: 0.5px solid #000000; vertical-align: middle; padding: 5px;">
                           รวม
                        </th>
                        <th width="4%" style="font-size: 16px; text-align: right; border: 0.5px solid #000000; vertical-align: middle; padding: 5px;">
                            {{ number_format($summary_standard_amount, 2) }}
                        </th>
                        <th width="4%" style="font-size: 16px; text-align: right; border: 0.5px solid #000000; vertical-align: middle; padding: 5px;">
                            {{ number_format($summary_advance_amount, 2) }}
                        </th>
                        <th width="4.5%" style="font-size: 16px; text-align: right; border: 0.5px solid #000000; vertical-align: middle; padding: 5px;">
                            {{ number_format($summary_actaul_amount, 2) }}
                        </th>
                    </tr>
                    <tr>
                        <th colspan="10" style="font-size: 16px; text-align: right; border: 1px solid #ffffff;  border-top: 1px solid #000000; vertical-align: middle; padding: 5px;">
                            ผู้รับผิดชอบ : {{ $user->full_name }}
                        </th>
                    </tr>
                </tbody>
            @endif
        </table>
    </main>
</body>
</html>