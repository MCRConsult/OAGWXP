@php
    $total = 0;   
@endphp
<h3 class="text-center b">
    รายละเอียดแนบใบสำคัญรอจ่าย
    <br>
    {{ $req->ou }}
</h3>
<table class="table">
    <thead>
        <tr>
            <th class="text-center word-wrap" width="60px;">
                ลำดับที่
            </th>
            <th class="text-center word-wrap" width="100px">
                เลขที่
                <br>
                ใบแจ้งหนี้
            </th>
            <th class="text-center word-wrap" width="250px">
                ชื่อสั่งจ่าย
            </th>
            <th class="text-center word-wrap" width="120px">
                จำนวน
                <br>
                เงินที่จ่าย
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payForList as $index => $list)
            @php
                $detail = explode("$$", $index);
            @endphp
            <tr>
                <td class="text-center">
                    {{ $loop->iteration }}
                </td>
                <td class="text-center">
                    {{ $req->req_number }}
                </td>
                <td>
                    {{ $detail[0] }}{{ isset($detail[1]) ? ' : '.$detail[1] : '' }}
                </td>
                <td class="text-right">
                    {{ number_format($list->sum('total_price'), 2) }}
                </td>
            </tr>
            @php
                $total += $list->sum('total_price');
            @endphp
        @endforeach
        <tr>
            <td colspan="3" class="text-right">
                รวม
            </td>
            <td class="text-right">
                {{ number_format($total, 2) }}
            </td>
        </tr>
    </tbody>
</table>