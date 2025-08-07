<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class GLAccountHierarchyV extends Model
{
    protected $table = 'oaggl_account_hierarchies_v';
    protected $connection = 'oracle';

    public function findFund($orgId, $account)
    {
        $currDate = date('d-m-Y');
        $fund = collect(\DB::select("
                            select apps.oaggl_process.find_budget(p_org_id => {$orgId}
                                , p_concatenated_segments => '{$account}'
                                , p_date => to_date('{$currDate}','DD-MM-YYYY')) avaliable_budget from dual
                        "));
        return  $fund->first()->avaliable_budget;
    }
}
