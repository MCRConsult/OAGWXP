{{-- @php
    $create_by_user = $req->user;
@endphp
<table class="table">
    <tr>
        <td>
            <div >
                <div class="b" style="padding-left: 2px;">
                    CONDITION : EXPENSES FROM BUDGET
                </div>
                <div class="b" style="padding-left: 15px;">
                    PLEASE PAY FOR :-
                </div>
                <div style="padding-left: 2rem;">
                    @if($type == 'Clear Adv. Payment' && $totalActualPrice < $totalPrice)
                        <div>
                            <span class="underline" style="padding-left: 2px; width: 650px;">
                                 
                            </span>
                        </div>
                        <div class="underline" style="padding-left: 2px; width: 650px;">
                        </div>
                        <div></div>
                        <div class="underline" style="padding-left: 2px; width: 650px;">
                        </div>
                        <div></div>
                    @elseif (count($payForList) > 3)
                        <div>
                            <span class="underline" style="padding-left: 2px; width: 650px;">
                                ตามรายละเอียดแนบ
                            </span>
                        </div>
                        <div class="underline" style="padding-left: 2px; width: 650px;">
                        </div>
                        <div></div>
                        <div class="underline" style="padding-left: 2px; width: 650px;">
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
                                    <span class="underline" style="padding-left: 2px; width: 650px;">
                                    </span>
                                </div>
                            @elseif ($type == 'Clear Adv. Payment')
                                @if( $lines->sum('actual_total_price') - $lines->sum('total_price') != 0)
                                    @php
                                        $j++;
                                    @endphp
                                    @if($lines->sum('actual_total_price') > $lines->sum('total_price'))
                                        <div>
                                            <span class="underline" style="padding-left: 2px; width: 650px;"> {{$index}} :  
                                                {{ number_format( abs( $lines->sum('actual_total_price') - $lines->sum('total_price') ), 2) }}
                                            </span>
                                        </div>
                                    @else
                                        <div>
                                            <span class="underline" style="padding-left: 2px; width: 650px;">
                                            </span>
                                        </div>
                                    @endif
                                @endif
                            @else
                                @php
                                    $j++;
                                @endphp
                                <div>
                                    <span class="underline" style="padding-left: 2px; width: 650px;"> {{$index}} :  
                                        {{ number_format($lines->sum('total_price'), 2) }}
                                    </span>
                                </div>
                            @endif
                        @endforeach
                        @for ($i = $j; $i < 3; $i++)
                            <div class="underline" style="width: 650px;">
                            </div>
                            <div></div>
                        @endfor
                    @endif
                </div>
                <div >
                    <span class="underline" style="padding-left: 5px;width: 99%; height: 15px"></span>
                </div>
                <div style="padding-top: 5px; padding-left: 15px;">
                    <div class="inline-block b">
                        DESCRIPTION : 
                        
                    </div>
                    <span class="underline" style="padding-left: 2px; margin-top: 2px; width: 575px;">{{ $req->description }}</span>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div style="padding-top: 25px;">
                <div class="inline-block" style="width: 40%">
                    <div>
                        <span class="b">REQUESTED BY : </span><span style="{{strlen($create_by_user->fullName) > 31 ? 'font-size: 14.5px;' : ''}}">{{ $create_by_user->fullName }}</span>
                    </div>
                    <div class="text-center">
                        {{date('M d, Y', strtotime($req->req_date))}}
                    </div>
                    <div>
                        <span class="b">CHECKED BY : </span><span style="{{strlen($userName) > 31 ? 'font-size: 14.5px;' : ''}}">{{ $userName }}</span>
                    </div>
                    <div class="text-center">
                        {{date('M d, Y', strtotime($req->req_date))}}
                    </div>
                </div>
                <div class="inline-block" style="width: 29%">
                    <div class="text-center b">
                        DEAN / DIRECTOR
                    </div>
                    <div class="text-center" style="padding-top: 25px;">
                        .............................................................
                    </div>
                    <div class="text-center">
                        ( ............................................... )
                    </div>
                    <div class="text-center">
                        {{date('M d, Y', strtotime($req->req_date))}}
                    </div>
                </div>
                <div class="inline-block" style="width: 29%">
                    <div class="text-center b">
                        VICE PRESIDENT
                    </div>
                    <div class="text-center" style="padding-top: 25px;">
                        .............................................................
                    </div>
                    <div class="text-center">
                        ( ............................................... )
                    </div>
                    <div class="text-center">
                        {{date('M d, Y', strtotime($req->req_date))}}
                    </div>
                </div>
                <div class="b" style="padding-left: 25px;">
                    EXTENSION NUMBER: {{ $req->extension_number }}
                </div>
            </div>
        </td>
    </tr>
</table> --}}
{{-- <div>
    <div class="footer-top">
        
    </div>
</div>
<div>
    <div class="footer-bottom" style="padding-bottom: -10px;">
        
    </div>
</div> --}}