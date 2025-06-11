<?php

namespace Packages\expense\app\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Packages\expense\app\Models\POExpenseAccountRuleV;
use Packages\expense\app\Models\GLPeriod;

class AccountController extends Controller
{
    public function getExpenseAccount(Request $request)
    {
        $header = $request->header;
        $line = $request->line;
        $docCate = explode('_', $header['document_category']);
        $user = auth()->user();
        $employee = $user->hrEmployee;
        $exp = isset($line['expense_type'])? $line['expense_type']: '';
        $expenseRules = POExpenseAccountRuleV::where('item_category', $exp)
                                            ->orderBy('segment_num')
                                            ->get()
                                            ->pluck('segment_value', 'segment_num');
        logger($expenseRules);
        logger( $exp);
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
        // SEGMENT5-9
        $segments[5] = null;
        $segments[6] = null;
        $segments[7] = null;
        $segments[8] = null;
        $segments[9] = null;
        if ($header['budget_source'] == 510) {
            $segments[5] = isset($expenseRules[5])? $expenseRules[5]: '00000';
            $segments[6] = isset($expenseRules[6])? $expenseRules[6]: '00000';
            $segments[7] = isset($expenseRules[7])? $expenseRules[7]: '00000';
            $segments[8] = isset($expenseRules[8])? $expenseRules[8]: '000000';
            $segments[9] = '00000000000000000000';
        }elseif(in_array($header['budget_source'], [520, 530, 550])){
            $segments[5] = '00000';
            $segments[6] = isset($expenseRules[6])? $expenseRules[6]: '00000';
            $segments[7] = '00000';
            $segments[8] = isset($expenseRules[8])? $expenseRules[8]: '000000';
            $segments[9] = '00000000000000000000';
        }elseif($header['budget_source'] == 540){
            $segments[5] = isset($expenseRules[5])? $expenseRules[5]: '00000';
            $segments[6] = isset($expenseRules[6])? $expenseRules[6]: '00000';
            $segments[7] = isset($expenseRules[7])? $expenseRules[7]: '00000';
            $segments[8] = isset($expenseRules[8])? $expenseRules[8]: '000000';
            $segments[9] = '00000000000000000000';
        }elseif($docCate[1] == 'สบพ.'){
            $segments[5] = isset($expenseRules[5])? $expenseRules[5]: '00000';
            $segments[6] = isset($expenseRules[6])? $expenseRules[6]: '00000';
            $segments[7] = isset($expenseRules[7])? $expenseRules[7]: '00000';
            $segments[8] = isset($expenseRules[8])? $expenseRules[8]: '000000';
            $segments[9] = '00000000000000000000';
        }else{
            $segments[5] = isset($expenseRules[5])? $expenseRules[5]: '00000';
            $segments[6] = null;
            $segments[7] = null;
            $segments[8] = isset($expenseRules[8])? $expenseRules[8]: '000000';
            $segments[9] = null;
        }
        // SEGMENT10
        $segments[10] = isset($expenseRules[10])? $expenseRules[10]: null;
        // SEGMENT11
        $segments[11] = isset($expenseRules[11])? $expenseRules[11]: null;
        // SEGMENT12
        $segments[12] = '00';
        // SEGMENT13
        $segments[13] = '00';

        $concatenatedSegments = $segments[1].'.'.$segments[2].'.'.$segments[3].'.'.$segments[4].'.'.$segments[5].'.'.$segments[6].'.'.$segments[7].'.'.$segments[8].'.'.$segments[9].'.'.$segments[10].'.'.$segments[11].'.'.$segments[12].'.'.$segments[13];

        return ['expense_account' => $concatenatedSegments];
    }
}
