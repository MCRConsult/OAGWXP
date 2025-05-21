<?php

namespace Packages\expense\app\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Packages\expense\app\Models\RequisitionHeader;
use Packages\expense\app\Models\LookupV;
use Packages\expense\app\Models\POExpenseAccountRuleV;
use Packages\expense\app\Models\GLPeriod;

class RequisitionController extends Controller
{
    public function getRequisition(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $reqNumber = RequisitionHeader::selectRaw('distinct req_number')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(req_number) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('req_number')
                        ->get();

        return response()->json(['data' => $reqNumber]);
    }

    public function getDocumentCategory(Request $request)
    {
        $budgetSource = $request->budget_source;
        $default = [];
        // if (auth()->user()->org_id == 82) {
            $default = LookupV::selectRaw('distinct lookup_code, tag')
                            ->where('lookup_type', 'OAG_AP_SOURCE_CATEGORY')
                            ->where('lookup_code', $budgetSource)
                            ->first();
        // }

        return response()->json(['data' => $default]);
    }

    public function getExpenseAccount(Request $request)
    {
        $header = $request->header;
        $line = $request->line;
        $docCate = explode('_', $header['document_category']);
        $user = auth()->user();
        $employee = $user->hrEmployee;
        $accountRules = POExpenseAccountRuleV::where('item_category', $line['expense_type'])
                                            ->orderBy('segment_num')
                                            ->get()
                                            ->pluck('segment_value', 'segment_num');
        //YEAR
        $year = strtoupper(date('M-y'));
        $period = GLPeriod::selectRaw("period_year+543 period_year")->where('period_name', $year)->first();
        $concatenatedSegments = '';
        $segments = [];
        // SEGMENT1
        $segments[1] = $employee->segment1;
        // SEGMENT2
        $segments[2] = $employee->segment2;
        // SEGMENT3
        $segments[3] = date('y', strtotime($period->period_year));
        // SEGMENT4
        $segments[4] = $header['budget_source'];
        // SEGMENT5-8
        $segments[5] = null;
        $segments[6] = null;
        $segments[7] = null;
        $segments[8] = null;
        if ($header['budget_source'] == 510) {
            $segments[5] = isset($accountRules[5])? $accountRules[5]: '00000';
            $segments[6] = isset($accountRules[6])? $accountRules[6]: '00000';
            $segments[7] = isset($accountRules[7])? $accountRules[7]: '00000';
            $segments[8] = isset($accountRules[8])? $accountRules[8]: '000000';
        }elseif(in_array($header['budget_source'], [520, 530, 550])){
            $segments[5] = '00000';
            $segments[6] = isset($accountRules[6])? $accountRules[6]: '00000';
            $segments[7] = '00000';
            $segments[8] = isset($accountRules[8])? $accountRules[8]: '000000';
        }elseif($header['budget_source'] == 540){
            $segments[5] = isset($accountRules[5])? $accountRules[5]: '00000';
            $segments[6] = isset($accountRules[6])? $accountRules[6]: '00000';
            $segments[7] = isset($accountRules[7])? $accountRules[7]: '00000';
            $segments[8] = isset($accountRules[8])? $accountRules[8]: '000000';
        }elseif($docCate[1] == 'สบพ.'){
            $segments[5] = isset($accountRules[5])? $accountRules[5]: '00000';
            $segments[6] = isset($accountRules[6])? $accountRules[6]: '00000';
            $segments[7] = isset($accountRules[7])? $accountRules[7]: '00000';
            $segments[8] = isset($accountRules[8])? $accountRules[8]: '000000';
        }else{
            $segments[5] = isset($accountRules[5])? $accountRules[5]: '00000';
            $segments[6] = '00000';
            $segments[7] = '00000';
            $segments[8] = isset($accountRules[8])? $accountRules[8]: '000000';
        }
        // SEGMENT9
        $segments[9] = '00000000000000000000';
        // SEGMENT10
        $segments[10] = isset($accountRules[10])? $accountRules[10]: '0000000000';
        // SEGMENT11
        $segments[11] = isset($accountRules[11])? $accountRules[11]: '000000';
        // SEGMENT12
        $segments[12] = '00';
        // SEGMENT13
        $segments[13] = '00';

        $concatenatedSegments = $segments[1].'.'.$segments[2].'.'.$segments[3].'.'.$segments[4].'.'.$segments[5].'.'.$segments[6].'.'.$segments[7].'.'.$segments[8].'.'.$segments[9].'.'.$segments[10].'.'.$segments[11].'.'.$segments[12].'.'.$segments[13];

        return ['expense_account' => $concatenatedSegments];
    }
}
