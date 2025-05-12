@php
    $create_by_user = $req->user;
    $departmentCode = $create_by_user->getDepartment();
    $userName = null;
    ///'Clear Adv. Payment'
    $approveHeader = $type === 'Adv. Payment' && $req->hasPrepayApproval()
                    ? $req->approval()->where('type', 'Prepay Approval')->where('status', 'approved')->latest()->first()
                    : $req->approval()->where('type', '!=', 'Prepay Approval')->where('status', '!=', 'rejected')->latest()->first();
    $approveLines = collect();
    $canUserApprove = false;
    if ($approveHeader) {
        $canUserApprove = $approveHeader->canApproveByUser(auth()->user());
        $approveLines = $approveHeader
                    ->approvalLines()
                    ->orderBy('seq', 'asc')
                    ->get();
    }
    $campus = $req->getCampus();
    $blankName = '...............................................................';
    $blankDate = '................................';
@endphp
<table class="table">
    <thead>
        <tr>
            <th colspan="3" style="font-weight: normal;">
                <div class="text-center">
                    <div class="inline b">
                        DATE :
                    </div>
                    <div class="inline text-center" style="padding-right : 15px;">
                        {{ date('M d, Y', strtotime( $type === 'Clear Adv. Payment' 
                                                            ? ( $approveHeader 
                                                                ? $approveHeader->created_at
                                                                : 'now')
                                                            : $req->req_date)) 
                        }}
                    </div>
                    &nbsp;
                    <div class="inline b">
                        NO. :
                    </div>
                    <div class="inline text-center">
                        {{$req->req_number}}
                    </div>
                    &nbsp;
                    <div class="inline b" style="padding-left : 15px;">
                        TYPE :
                    </div>
                    <div class="inline text-center">
                        {{$req->getReqTypeForPDF($type)}}
                        @if ($req->withdraw == 'Withdraw')
                            - เบิกแทน
                        @endif
                        @if ($req->petty_cash == '1')
                            - Petty Cash
                        @endif
                    </div>
                </div>
                <div style="padding-left: 1.5rem;">
                    <div>
                        <div class="inline-block b">
                            NAME & SURNAME :
                        </div>
                        <div class="inline-block" style="font-size: 16px; padding-top: 2px;">
                            {{ $req->user ? $req->user->fullName : null }}
                            {{-- ASST.PROF.DR. ANILKUMAR KOTHALIL GOPALAKRISHNAN --}}
                        </div>
                        <div class="inline-block b" style="padding-left: 2px;">
                            ID CODE. :
                        </div>
                        <div class="inline-block" style="font-size: 16px; padding-top: 2px;">
                            {{$req->user->employee->employee_number}}
                        </div>
                    </div>
                    <div>
                        <div class="inline-block b">
                            POSITION :
                        </div>
                        <div class="inline-block" style="font-size: 16px; padding-top: 1px;">
                                {{$req->position}}
                        </div>
                    </div>
                    <div>
                        <div class="inline-block b">
                            FACULTY / DEPARTMENT :
                        </div>
                        <div class="inline-block" style="text-align: left; width: 70%; font-size: 16px; padding-top: 1px;">
                            {{-- ABAC Social Innovation in Management and Business Analysis Research Center : M15140600 --}}
                            {{$req->department()->value_description}} : {{ ($req->department) }}
                        </div>
                    </div>
                    <div>
                        <div class="inline-block b">
                            JOB / PROJECT TITLE :
                        </div>
                        @if ($projectName && $projectNumber)
                            <div class="inline-block" style="text-align: left; width: 77%; {{strlen($projectName) > 90 ? 'font-size: 14.5px; padding-top: 2px;' : ''}}">
                                {{ $projectName }} : {{ $projectNumber }}
                            </div>
                        @else
                            <div></div>
                        @endif
                    </div>
                    <div>
                        <div class="inline-block" style="text-align: left; width: 60%">
                            <span class="b"> CAMPUS : </span>
                            <span>{{ $campus->flex_value . ' : ' . $campus->value_description }}</span>
                            {{-- <span>{{ $req->cashier ? $req->cashier->description : ''}}</span> --}}
                        </div>
                        <div class="inline-block" style="text-align: left; width: 30%">
                            <span class="b">OU : </span>
                            <span>{{ $req->ou }}</span>
                        </div>
                    </div>
                    @if ($req->req_type == 'Prepayment')
                        <div>
                            <div class="inline-block b">
                                ADVANCE BORROWER :
                            </div>
                            <div class="inline-block" style="width: 200px;">
                                {{$req->vendor ? $req->vendor->alt_supplier_name : $req->pay_for}}
                            </div>
                            <div class="inline-block b">
                                CLEAR DATE :
                            </div>
                            <div class="inline-block" style="width: 200px;">
                                {{date('M d, Y', strtotime($req->clear_date))}}
                            </div>
                        </div>
                    @endif
                </div>
                <div class="inline-block b" style="padding-left: 2px;">
                    TO DIRECTOR, OFFICE OF FINANCIAL MANAGEMENT
                </div>
            </th>
        </tr>
        <tr>
            <th class="text-center">
                ITEM
            </th>
            <th class="text-center" width="72%">
                BUDGET TITLE (INDEX TITLE)
            </th>
            {{-- <th class="text-center" width="7%">
                INDEX.
            </th>
            <th class="text-center" width="20%">
                BUDGET IN DETAILS
            </th> --}}
            <th class="text-center" width="24%">
                AMOUNT
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($groupDatas as $groupDepartmentName => $groupProjects)
            <tr>
                <td class="text-center">
                    {{-- {{ $loop->iteration }} --}}
                </td>
                <td class="b">
                    {{-- ABAC Social Innovation in Management and Business Analysis Research Center : M15140600 --}}
                    {{ $groupDepartmentName }}
                </td>
                {{-- <td class="text-center">

                </td>
                <td >

                </td> --}}
                <td class="text-right">

                </td>
            </tr>
            @php
                $currentLine++;
            @endphp
            @foreach ($groupProjects as $groupProjectName => $groupItems)
                <tr>
                    <td class="text-center">

                    </td>
                    <td style="padding-left: 0.5rem;">
                        {{-- Advising, monitoring, and learning assessment for Student Organization and Student Activity Units : J362005100 --}}
                        {{ $groupProjectName }}
                    </td>
                    {{-- <td class="text-center">

                    </td>
                    <td >

                    </td> --}}
                    <td class="text-right">

                    </td>
                </tr>
                @php
                    $currentLine++;
                @endphp
                @foreach ($groupItems as $groupItemName => $items)
                    <tr>
                        <td class="text-center">

                        </td>
                        <td style="padding-left: 1rem;">
                            {{ $groupItemName }}
                        </td>
                        {{-- <td class="text-center">

                        </td>
                        <td >

                        </td> --}}
                        <td class="text-right">

                        </td>
                    </tr>
                    @php
                        $currentLine++;
                    @endphp
                    @foreach ($items as $index => $item)
                        <tr>
                            @php
                                $count_actual_quantity = $item->sum('actual_quantity');
                                $sum_actual_total_price = $item->sum('actual_total_price');
                                $count_quantity = $item->sum('quantity');
                                $sum_total_price = $item->sum('total_price');
                                $sum_actual_total_price = $item->sum('actual_total_price');
                                $item = $item->first();
                            @endphp
                            @if ($item->budget)
                                <td class="text-center">
                                    {{$item->budget->index_number}}.{{$item->budget->item_number}}
                                </td>
                            @else
                                <td class="text-center">

                                </td>
                            @endif
                            <td style="font-size: {{ mb_strlen($index, 'UTF-8') > 90 ? '16px' : '18px'}}; word-wrap: break-word;">
                                {{ $item->budget ? $item->budget->item->description : ($item->item ? $item->item->description : '') }}
                                @if ($item->budget)
                                    @if ($item->budget->description)
                                        - {{ $item->budget->description }}
                                    @endif
                                    @if ($item->description)
                                        : {{ $item->description }}
                                    @endif
                                @endif
                                @if ( $type == 'Clear Adv. Payment' )
                                    {{-- {{ $count_actual_quantity ?? 0 }} --}}
                                    {{-- @if ($item->uom)
                                        {{$item->mtlUom->description}}
                                    @else
                                        @if ($item->budget->mtlUom)
                                            {{$item->budget->mtlUom->description}}
                                        @endif
                                    @endif
                                    @ {{ number_format($item->actual_price, 2) ?? 0}} Baht --}}

                                    {{-- --------------------------------------------------------------------------------------------- --}}
                                    @if ($item->actual_multiple_quantity)
                                        {{ $item->readable_actual_multiple_quantity}}
                                        @ {{ number_format($item->actual_price,2) }} Baht

                                    @else
                                        {{-- {{  number_format($item->quantity,2) }} {{ $item->mtlUom->description }}

                                        <span class="tw-text-grey-dark">x</span> {{ number_format($item->price,2) }} baht --}}
                                        {{ $count_actual_quantity ?? 0 }}
                                        @if ($item->uom)
                                            {{$item->mtlUom->description}}
                                        @else
                                            @if ($item->budget->mtlUom)
                                                {{$item->budget->mtlUom->description}}
                                            @endif
                                        @endif
                                        @ {{ number_format($item->actual_price, 2) ?? 0}} Baht
                                    @endif

                                @else
                                    {{-- {{ $count_quantity ?? 0 }} --}}
                                    {{-- @if ($item->uom)
                                        {{$item->mtlUom->description}}
                                    @else
                                        @if ($item->budget->mtlUom)
                                            {{$item->budget->mtlUom->description}}
                                        @endif
                                    @endif
                                    @ {{ number_format($item->price, 2) ?? 0 }} Baht --}}
                                    {{-- --------------------------------------------------------------------------------------------- --}}
                                    @if ($item->multiple_quantity)
                                        {{ $item->readable_multiple_quantity}}
                                        @ {{ number_format($item->price,2) }} Baht

                                    @else
                                        {{-- {{  number_format($item->quantity,2) }} {{ $item->mtlUom->description }}

                                        <span class="tw-text-grey-dark">x</span> {{ number_format($item->price,2) }} baht --}}
                                        {{ $count_quantity ?? 0 }}
                                        @if ($item->uom)
                                            {{$item->mtlUom->description}}
                                        @else
                                            @if ($item->budget->mtlUom)
                                                {{$item->budget->mtlUom->description}}
                                            @endif
                                        @endif
                                        @ {{ number_format($item->price, 2) ?? 0 }} Baht

                                    @endif
                                @endif
                            </td>
                            <td class="text-right">
                                @if ($type == 'Clear Adv. Payment')
                                    {{ number_format($sum_actual_total_price, 2) }}
                                @else
                                    {{ number_format($sum_total_price, 2) }}
                                @endif
                            </td>
                        </tr>
                        @php
                            $currentLine += mb_strlen($index, 'UTF-8') > 210 ? 3 : (mb_strlen($index, 'UTF-8') > 90 ?  2 : 1);
                        @endphp
                    @endforeach
                @endforeach
            @endforeach
        @endforeach
        @for ($line = $currentLine; $line < $maxLine; $line++)
            <tr>
                <td class="text-center">

                </td>
                <td style="padding-left: 3rem;">

                </td>
                <td class="text-right">

                </td>
            </tr>
        @endfor
        <tr>
            <td class="text-center b" colspan="2">
                Total
            </td>
            @if($checkLast)
                <td class="text-right b">
                    {{ number_format( $type == 'Clear Adv. Payment' ? $total_actual : $total, 2) }}
                </td>
            @else
                <td class="text-right b">

                </td>
            @endif

        </tr>
        @if( $type == 'Clear Adv. Payment' )
            <tr>
                <td class="text-center b" colspan="2">
                    AMOUNT OF ADVANCED PAYMENT
                </td>
                <td class="text-right b">
                    {{ number_format($total, 2) }}
                </td>
            </tr>
            @if ($total_actual - $total != 0)
                <tr>
                    <td class="text-center b" colspan="2">
                        {{ $total_actual > $total ? 'AMOUNT PAY BACK' : 'AMOUNT GRANTED' }}
                    </td>
                    <td class="text-right b">
                        {{ number_format( abs($total_actual - $total) , 2) }}
                    </td>
                </tr>
            @else
            <tr>
                <td class="text-center b" colspan="2">
                    AMOUNT
                </td>
                <td class="text-right b">
                    {{ number_format( abs($total_actual - $total) , 2) }}
                </td>
            </tr>
            @endif
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">
                <div>
                    <div class="b" style="padding-left: 2px;">
                        CONDITION : EXPENSES FROM BUDGET
                    </div>
                    <div class="b" style="padding-left: 15px;">
                        PLEASE PAY FOR :-
                    </div>
                    <div style="padding-left: 2rem;">
                        @if($type == 'Clear Adv. Payment' && $totalActualPrice < $totalPrice)
                            <div>
                                <span class="underline" style="padding-left: 2px; height: 15px; width: 95%;">
                                </span>
                            </div>
                            <div class="underline" style="padding-left: 2px; width: 95%;">
                            </div>
                            <div></div>
                            <div class="underline" style="padding-left: 2px; width: 95%;">
                            </div>
                            <div></div>
                        @elseif (count($payForList) > 3)
                            <div>
                                <span class="underline" style="padding-left: 2px; width: 95%;">
                                    ตามรายละเอียดแนบ
                                </span>
                            </div>
                            <div class="underline" style="padding-left: 2px; width: 95%;">
                            </div>
                            <div></div>
                            <div class="underline" style="padding-left: 2px; width: 95%;">
                            </div>
                            <div></div>
                        @else
                            @php
                                $j = 0;
                            @endphp
                            @foreach ($payForList as $index => $lines)
                                @if ($req->withdraw == 'Withdraw' && $req->outsourcing == '0')
                                    @php
                                        $j++;
                                    @endphp
                                    <div>
                                        <span class="underline" style="padding-left: 2px; width: 95%;">
                                        </span>
                                    </div>
                                @elseif ($type == 'Clear Adv. Payment')
                                    @if( $lines->sum('actual_total_price') - $lines->sum('total_price') != 0)
                                        @php
                                            $j++;
                                        @endphp
                                        @if($lines->sum('actual_total_price') > $lines->sum('total_price'))
                                            @php
                                                $detail = explode("$$", $index);
                                            @endphp
                                            <div>
                                                <span class="underline" style="padding-left: 2px; width: 95%;"> {{ isset($detail[0]) ? $detail[0] : '' }} :
                                                    {{ number_format( abs( $lines->sum('actual_total_price') - $lines->sum('total_price') ), 2) }} Baht {{ isset($detail[1]) ? ' : '.$detail[1] : '' }}
                                                </span>
                                            </div>
                                        @else
                                            <div>
                                                <span class="underline" style="padding-left: 2px; width: 95%;">
                                                </span>
                                            </div>
                                        @endif
                                    @endif
                                @else
                                    @php
                                        $j++;
                                        $detail = explode("$$", $index);
                                    @endphp
                                    <div>
                                        <span class="underline" style="width: 95%;"> {{ isset($detail[0]) ? $detail[0] : '' }} :
                                            {{ number_format($lines->sum('total_price'), 2) }} Baht {{ isset($detail[1]) ? ' : '.$detail[1] : '' }}
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                            @for ($i = $j; $i < 3; $i++)
                                <div class="underline" style="height: 15px; width: 95%;">
                                </div>
                            @endfor
                        @endif
                    </div>
                    <div >
                        <span class="underline" style="padding-left: 5px; height: 15px; width: 100%;"></span>
                    </div>
                    <div style="padding-top: 5px; padding-left: 15px;">
                        <span class="b" style="vertical-align: top;"> DESCRIPTION : </span>
                        <span class="underline" style="padding-left: 2px; margin-top: 2px; width: 80%; over-flown:hidden; word-wrap:break-word;">{{ $req->description }}</span>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div style="padding-top: 25px; font-size: 17px;">
                    <div class="inline-block" style="width: 37%">
                        <div class="b">
                            REQUESTED BY :
                        </div>
                        @php
                            $submitUser = $approveHeader
                                        ? ($approveHeader->submittedBy ? $approveHeader->submittedBy->fullName : null)
                                        : null;
                        @endphp
                        <div class="text-center" style="{{strlen($submitUser) > 31 ? 'font-size: 14.5px;' : ''}}">
                            {{ $submitUser ?? $blankName }}
                        </div>
                        {{-- <div class="text-center" style="{{strlen($create_by_user->fullName) > 31 ? 'font-size: 14.5px;' : ''}}">
                            {{ $create_by_user->fullName }}
                        </div> --}}
                        <div class="text-center">
                            {{ $approveHeader ? date('M d, Y', strtotime($approveHeader->created_at)) : $blankDate }}
                        </div>
                        @php
                            $approveLine = $approveLines->where('seq', 2)->first();
                            $list = $hierarchies->where('seq_number', 2)->first();
                            $userName = $approveLine
                                        ? ($approveLine->status == 'approved' ? ($approveLine->user ? $approveLine->user->fullName : null) : null)
                                        : null;
                                        // : ($list
                                        //     ? ( $list->getApprovableUsers($list->department_flag == true ? $departmentCode : null)->count()
                                        //         ? $list->getApprovableUsers($list->department_flag == true ? $departmentCode : null)->last()->fullName
                                        //         : ( $list->deapermentUser($departmentCode)->count()
                                        //             ? $list->deapermentUser($departmentCode)->last()->fullName
                                        //             : ($list->userRoleInDepartments($list->role_id, $departmentCode)->count() == 1
                                        //                 ? $list->userRoleInDepartments($list->role_id, $departmentCode)->first()->fullName
                                        //                 : null)))
                                        //     : null);
                        @endphp
                        <div class="b">
                            CHECKED BY :
                            {{-- <span style="font-size: 14.5px"> --}}
                        </div>
                        <div class="text-center" style="{{strlen($userName) > 31 ? 'font-size: 14.5px;' : ''}}">
                            {{-- <div class="text-center" style="font-size: 14.5px"> --}}
                            {{ $userName ?? $blankName }}
                            {{-- ASST.PROF.DR. ANILKUMAR KOTHALIL GOPALAKRISHNAN --}}
                        </div>
                        <div class="text-center">
                            {{ $approveLine 
                                ? $approveLine->status == 'approved' 
                                    ? date('M d, Y', strtotime($approveLine->status_updated_at)) 
                                    : $blankDate
                                : $blankDate }}
                        </div>
                    </div>
                    <div class="inline-block" style="width: 30.5%">
                        @php
                            $approveLine = $approveLines->where('seq', 3)->first();
                            $list = $hierarchies->where('seq_number', 3)->first();
                            $userName = $approveLine
                                        ? ($approveLine->status == 'approved' ? ($approveLine->user ? $approveLine->user->fullName : null) : null)
                                        : null;
                                        // : ($list
                                        //     ? ( $list->getApprovableUsers($list->department_flag == true ? $departmentCode : null)->count()
                                        //         ? $list->getApprovableUsers($list->department_flag == true ? $departmentCode : null)->last()->fullName
                                        //         : ( $list->deapermentUser($departmentCode)->count()
                                        //             ? $list->deapermentUser($departmentCode)->last()->fullName
                                        //             : ($list->userRoleInDepartments($list->role_id, $departmentCode)->count() == 1
                                        //                 ? $list->userRoleInDepartments($list->role_id, $departmentCode)->first()->fullName
                                        //                 : null)))
                                        //     : null);
                        @endphp
                        <div class="text-center b">
                            DEAN / DIRECTOR
                        </div>
                        <div class="text-center" style="padding-top: 25px;">
                            {{ $blankName }}
                        </div>
                        <div class="text-center">
                            ({{ $userName ?? $blankName }})
                        </div>
                        <div class="text-center">
                            {{ $approveLine 
                                ? $approveLine->status == 'approved' 
                                    ? date('M d, Y', strtotime($approveLine->status_updated_at)) 
                                    : $blankDate
                                : $blankDate }}
                        </div>
                    </div>
                    <div class="inline-block" style="width: 30.5%">
                        @php
                            $approveLine = $approveLines->where('seq', 4)->first();
                            $list = $hierarchies->where('seq_number', 4)->first();
                            $userName = $approveLine
                                        ? ($approveLine->status == 'approved' ? ($approveLine->user ? $approveLine->user->fullName : null) : null)
                                        : null;
                                        // : ($list
                                        //     ? ( $list->getApprovableUsers($list->department_flag == true ? $departmentCode : null)->count()
                                        //         ? $list->getApprovableUsers($list->department_flag == true ? $departmentCode : null)->last()->fullName
                                        //         : ( $list->deapermentUser($departmentCode)->count()
                                        //             ? $list->deapermentUser($departmentCode)->last()->fullName
                                        //             : ($list->userRoleInDepartments($list->role_id, $departmentCode)->count() == 1
                                        //                 ? $list->userRoleInDepartments($list->role_id, $departmentCode)->first()->fullName
                                        //                 : null)))
                                        //     : null);
                        @endphp
                        <div class="text-center b" style="font-size: 14.5px;">
                            V.P. FOR STRATEGIC ADVANCEMENT <br>/ THE UNIVERSITY REGISTRAR <br>/ VICE PRESIDENT
                        </div>
                        <div class="text-center" style="padding-top: 25px;">
                            {{ $blankName }}
                        </div>
                        <div class="text-center">
                            ({{ $userName ?? $blankName }})
                        </div>
                        <div class="text-center">
                            {{ $approveLine 
                                ? $approveLine->status == 'approved' 
                                    ? date('M d, Y', strtotime($approveLine->status_updated_at)) 
                                    : $blankDate
                                : $blankDate }}
                        </div>
                    </div>
                    <div class="b" style="padding-left: 25px;">
                        EXTENSION NUMBER: {{ $req->extension_number }}
                    </div>
                </div>
            </td>
        </tr>
    </tfoot>
</table>

