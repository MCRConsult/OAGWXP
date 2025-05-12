<div class="row" style="height: 90px;">
    <img src="{{ base_path() }}/public/images/ABAC_Logo_New.png" style="padding-left: 20px; width: 89.5px; height:63.5;"/>
    <div class="row" align="center">
        <h1>
            ASSUMPTION UNIVERSITY
        </h1>
        <br>
        <h1 style="padding-top: 10px">
            BUDGET REQUISITION
        </h1>
    </div>
</div>
{{-- <div class="header-box">
    <div class="text-center" style="padding-top: 5px;">
        <div class="inline b">
            DATE : 
        </div>
        <div class="inline text-center" style="padding-right : 50px;">
            {{date('M d, Y', strtotime($req->req_date))}}
        </div>
        &nbsp;
        <div class="inline b">
            NO. : 
        </div>
        <div class="inline text-center">
            {{$req->req_number}}
        </div>
        &nbsp;
        <div class="inline b" style="padding-left : 50px;">
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
        <div class="inline-block b">
            NAME & SURNAME : 
        </div>
        <div class="inline-block" style="padding-top: 2px; {{strlen($req->user->fullName) > 31 || strlen($req->position) > 31 ? 'font-size: 14.5px;' : ''}}">
            {{$req->user->fullName}}
        </div>
        <div class="inline-block b" style="padding-left: 3px;">
            ID CODE. : 
        </div>
        <div class="inline-block" style="padding-top: 2px; {{strlen($req->user->fullName) > 31 || strlen($req->position) > 31 ? 'font-size: 14.5px;' : ''}}">
            {{$req->user->employee->employee_number}}
        </div>
        <div class="inline-block b" style="padding-left: 3px;">
            POSITION : 
        </div>
        <div class="inline-block" style="width: 200px; padding-top: 2px; {{strlen($req->user->fullName) > 31 || strlen($req->position) > 31 ? 'font-size: 14.5px;' : ''}}">
                {{$req->position}}
        </div>
        <div>
            <div class="inline-block b">
                FACULTY / DEPARTMENT : 
            </div>
            <div class="inline-block" style="width: 500px;">
                {{$req->department()->value_description}} : {{ ($req->department) }}
            </div>
        </div>
        <div>
            <div class="inline-block b">
                JOB / PROJECT TITLE : 
            </div>
            @if ($projectName && $projectNumber)
                <div class="inline-block" style="width: 500px;">
                    {{ $projectName }} : {{ $projectNumber }}
                </div>
            @else
                
            @endif
        </div>
        <div>
            <div class="inline-block b">
                CAMPUS : 
            </div>
            <div class="inline-block" style="width: 400px;">
                {{ $req->cashier ? $req->cashier->flex_value_meaning . ' : ' . $req->cashier->description : ''}}
            </div>
            <div class="inline-block" style="width: 80px;">
                    
            </div>
            <div class="inline-block b">
                OU : 
            </div>
            <div class="inline-block" style="width: 50px;">
                {{ $req->ou }}
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
    <div class="inline-block b" style="padding-left: 2px; padding-bottom: 2px">
        TO DIRECTOR, OFFICE OF FINANCIAL MANAGEMENT
    </div>
</div> --}}
