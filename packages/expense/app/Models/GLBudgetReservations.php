<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class GLBudgetReservations extends Model
{
    protected $table = 'oaggl_budget_reservations';
    protected $connection = 'oracle';

    // public function insertGlReserve($header, $line, $user)
    // {
    //     self::insert([
    //         'reserve_date'          => date('Y-m-d')
    //         , 'reserve_type'        => 'RESERVE'
    //         , 'amount'              => $line->amount
    //         , 'description'         => $line->description
    //         , 'source_table_name'   => $line->getTable();
    //         , 'source_table_id'     => $line->id
    //         , 'period_name'         => date('M-y')
    //         , 'org_id'              => $user->org_id
    //         , 'account_code'        => $line->expense_account
    //         , 'batch_no'            => ''
    //     ]);

    //     $glReserve = self::where('batch_no', $xxx)->first();
    //     return $glReserve;
    // }
}
