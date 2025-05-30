<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class GLBudgetReservations extends Model
{
    protected $table = 'oaggl_budget_reservations';
    protected $connection = 'oracle';

    // public function insertGlReserve()
    // {
    //     self::insert([
    //         'reserve_date'          => ''
    //         , 'reserve_type'        => 'RESERVE'
    //         , 'amount'              => ''
    //         , 'description'         => ''
    //         , 'source_table_name'   => ''
    //         , 'source_table_id'     => ''
    //         , 'period_name'         => ''
    //         , 'org_id'              => ''
    //         , 'account_code'        => ''
    //         , 'batch_no'            => ''
    //     ]);

    //     $glReserve = self::where('batch_no', $xxx)->first();
    //     return $glReserve;
    // }
}
