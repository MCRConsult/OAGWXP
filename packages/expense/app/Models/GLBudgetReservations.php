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
    protected $appends = ['status_icon', 'reserve_date_format', 'transaction_number'];

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

    public function scopeSearch($q, $search)
    {
        if ($search->type == 'ENCUMBRANCE') {
            $reserveDateFrom = $search->reserve_date_from;
            $reserveDateTo = $search->reserve_date_to;
            if ($search->reserve_date_from && $search->reserve_date_to) {
                $q->whereRaw("trunc(reserve_date) >= TO_DATE('{$reserveDateFrom}','YYYY-mm-dd')")
                    ->whereRaw("trunc(reserve_date) <= TO_DATE('{$reserveDateTo}','YYYY-mm-dd')");
            }elseif ($search->reserve_date_from && !$search->reserve_date_to) {
                $q->whereRaw("trunc(reserve_date) >= TO_DATE('{$reserveDateFrom}','YYYY-mm-dd')");
            }else{
                $q;
            }

            if ($search->reserve_type) {
                $q->where('reserve_type', $search->reserve_type);
            }

            if ($search->status == 'All' || $search->status == null) {
                $q;
            }else{
                $q->where('reserve_status', $search->status);
            }
        }
        return $q;
    }

    public function getReserveDateFormatAttribute()
    {
        return date('d-m-Y', strtotime($this->reserve_date));
    }

    public function getStatusIconAttribute()
    {
        return $this->getStatusIcon($this->status);
    }

    function getStatusIcon()
    {
        $status = $this->reserve_status;
        $result = "";
        switch ($status) {
            case "S":
                $result = "<span class='badge badge-success' style='padding: 5px;'> สำเร็จ </span>";
                break;
            case "E":
                $result = "<span class='badge badge-danger' style='padding: 5px;'> ไม่สำเร็จ </span>";
                break;
        }
        return $result;
    }

    public function extractInvoiceNumber($fullString)
    {
        $parts = explode('-', $fullString);
        if (count($parts) >= 3) {
            return implode('-', array_slice($parts, 2));
        }
        return null;
    }
    
    public function getTransactionNumberAttribute()
    {
        $fullString = $this->batch_no;
        $trans_number = $this->extractInvoiceNumber($fullString);
        
        return $trans_number;
    }
}
