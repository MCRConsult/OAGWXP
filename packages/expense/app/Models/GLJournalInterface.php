<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class GLJournalInterface extends Model
{
    protected $table = 'OAGGL_JOURNAL_INTERFACE';
    protected $connection = 'oracle';
    protected $primary_key = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $appends = ['status_icon', 'req_date_format'];

    public function scopeSearch($q, $search)
    {
        if ($search->type == 'JOURNAL') {
            $reqDateFrom = $search->req_date_from;
            $reqDateTo = $search->req_date_to;
            if ($search->req_date_from && $search->req_date_to) {
                $q->whereRaw("trunc(default_effective_date) >= TO_DATE('{$reqDateFrom}','YYYY-mm-dd')")
                    ->whereRaw("trunc(default_effective_date) <= TO_DATE('{$reqDateTo}','YYYY-mm-dd')");
            }elseif ($search->req_date_from && !$search->req_date_to) {
                $q->whereRaw("trunc(default_effective_date) >= TO_DATE('{$reqDateFrom}','YYYY-mm-dd')");
            }else{
                $q;
            }

            if ($search->req_number) {
                $q->where('reference2', $search->req_number);
            }

            if ($search->journal_status == 'All' || $search->journal_status == null) {
                $q;
            }else{
                $q->where('interface_status', $search->journal_status);
            }
        }
        return $q;
    }

    public function getReqDateFormatAttribute()
    {
        return date('d-m-Y', strtotime($this->default_effective_date));
    }

    public function getStatusIconAttribute()
    {
        return $this->getStatusIcon($this->status);
    }

    function getStatusIcon()
    {
        $status = $this->interface_status;
        $result = "";
        switch ($status) {
            case "S":
                $result = "<span class='badge badge-primary' style='padding: 5px;'> ตั้งเบิก </span>";
                break;
            case "E":
                $result = "<span class='badge badge-danger' style='padding: 5px;'> ตั้งเบิกไม่สำเร็จ </span>";
                break;
            default:
                $result = "<span class='badge badge-success' style='padding: 5px;'> ยังไม่ส่งขอเบิก </span>";
                break;
        }
        return $result;
    }
}
