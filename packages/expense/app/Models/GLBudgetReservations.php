<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class GLBudgetReservations extends Model
{
    protected $table = 'oaggl_budget_reservations';
    protected $connection = 'oracle';
    protected $primary_key = null;
    public $incrementing = false;
    public $timestamps = false;

    public function callReserveBudget($batch)
    {
        $db = \DB::connection('oracle')->getPdo();
        $sql = "
            declare
                v_status                    varchar2(20);
                v_error                     varchar2(2000);
                begin
                    oaggl_process.reserve_budget(p_batch    => '{$batch}'
                                                , p_status  => :v_status
                                                , p_error   => :v_error
                                            );
                end;
        ";

        logger($sql);
        $stmt = $db->prepare($sql);
        $result = [];
        $stmt->bindParam(':v_status', $result['status'], \PDO::PARAM_STR, 20);
        $stmt->bindParam(':v_error', $result['error_msg'], \PDO::PARAM_STR, 2000);
        $stmt->execute();

        return $result;
    }
}
