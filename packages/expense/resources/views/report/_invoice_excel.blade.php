<div>
    <table>
        <tr>
            <th style="text-align: right; font-size: 16px;" colspan="10">
                <strong> ทบ.อส.1 </strong>
            </th>
        </tr>
        <tr> </tr>
        <tr>
            <th style="text-align: center; font-size: 16px;" colspan="10">
                <strong> ทะเบียนคุมหลักฐานขอเบิก (ทบ.อส.1) </strong>
            </th>
        </tr>
    </table>
    <table class="table table-responsive-sm table-bordered" style="border: 1px solid #000000;">
        <thead>
            @php
                $year = date('Y', strtotime($invDateTo)) + 543;
            @endphp
            <tr>
                <th style="border: 1px solid #000000; text-align: center; vertical-align: middle;" colspan="2">
                    <strong> พ.ศ. {{ $year }} </strong>
                </th>
                <th style="border: 1px solid #000000; text-align: center; vertical-align: middle;" rowspan="2">
                    <strong> ลำดับที่เอกสารขอเบิก </strong>
                </th>
                <th style="border: 1px solid #000000; text-align: center; vertical-align: middle;" rowspan="2">
                    <strong> เจ้าหนี้/ผู้ขอเบิก </strong>
                </th>
                <th style="border: 1px solid #000000; text-align: center; vertical-align: middle;" rowspan="2">
                    <strong> รายการ </strong>
                </th>
                <th style="border: 1px solid #000000; text-align: center; vertical-align: middle;" rowspan="2">
                    <strong> จำนวนเงิน </strong>
                </th>
                <th style="border: 1px solid #000000; text-align: center; vertical-align: middle;" rowspan="2">
                    <strong> ลายมือชื่อผู้รับหลักฐาน </strong>
                </th>
                <th style="border: 1px solid #000000; text-align: center; vertical-align: middle;" colspan="2">
                    <strong> การอ้างอิงจากระบบ GFMIS </strong>
                </th>
                <th style="border: 1px solid #000000; text-align: center; vertical-align: middle;" rowspan="2">
                    <strong> หมายเหตุ </strong>
                </th>
            </tr>
            <tr>
                <th style="border: 1px solid #000000; text-align: center; vertical-align: middle;">
                    <strong> เดือน </strong>
                </th>
                <th style="border: 1px solid #000000; text-align: center; vertical-align: middle;">
                    <strong> วันที่ </strong>
                </th>
                <th style="border: 1px solid #000000; text-align: center; vertical-align: middle;">
                    <strong> เลขที่ฎีกา </strong>
                </th>
                <th style="border: 1px solid #000000; text-align: center; vertical-align: middle;">
                    <strong> เลขที่เอกสาร JV </strong>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoices as $index => $invoice)
                @php
                    $day = date('d', strtotime($invoice->invoice_date));
                    $month = thaiMonth(date('m', strtotime($invoice->invoice_date)), $full = true);
                @endphp
                <tr>
                    <td style="border: 1px solid #000000; text-align: center;"> {{ $month }} </td>
                    <td style="border: 1px solid #000000; text-align: center;"> {{ $day }} </td>
                    <td style="border: 1px solid #000000; text-align: center;"> {{ optional($invoice)->req_number }} </td>
                    <td style="border: 1px solid #000000; text-align: center;"> {{ $invoice->supplier_name }} </td>
                    <td style="border: 1px solid #000000; text-align: center;"> {{ $invoice->description }} </td>
                    <td style="border: 1px solid #000000; text-align: center;"> {{ number_format($invoice->amount, 2) }} </td>
                    <td style="border: 1px solid #000000; text-align: center;"> {{ $invoice->user->hrEmployee->last_name }} </td>
                    <td style="border: 1px solid #000000; text-align: center;"> {{ $invoice->voucher_number }} </td>
                    <td style="border: 1px solid #000000; text-align: center;"> {{ $invoice->gfmis_document_number }} </td>
                    <td style="border: 1px solid #000000; text-align: center;"> {{ $invoice->note }} </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>