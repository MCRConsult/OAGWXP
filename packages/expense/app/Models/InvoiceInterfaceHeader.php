<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\EmployeesV;

class InvoiceInterfaceHeader extends Model
{
    protected $table = 'oagap_invoice_inf_temp';
    protected $connection = 'oracle';
    protected $primary_key = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $appends = ['status_icon', 'invoice_date_format'];

    public function hrEmployee()
    {
        return $this->belongsTo(EmployeesV::class, 'person_id', 'person_id');
    }

    public function scopeSearch($q, $search)
    {
        if ($search->type == 'INVOICE') {
            $invDateFrom = $search->invoice_date_from;
            $invDateTo = $search->invoice_date_to;
            if ($search->invoice_date_from && $search->invoice_date_to) {
                $q->whereRaw("trunc(invoice_date) >= TO_DATE('{$invDateFrom}','YYYY-mm-dd')")
                    ->whereRaw("trunc(invoice_date) <= TO_DATE('{$invDateTo}','YYYY-mm-dd')");
            }elseif ($search->invoice_date_from && !$search->invoice_date_to) {
                $q->whereRaw("trunc(invoice_date) >= TO_DATE('{$invDateFrom}','YYYY-mm-dd')");
            }else{
                $q;
            }

            if ($search->invoice_number) {
                $q->where('invoice_num', $search->invoice_number);
            }

            if ($search->voucher_number) {
                $q->where('voucher_num', $search->voucher_number);
            }

            if ($search->invoice_status == 'All' || $search->invoice_status == null) {
                $q;
            }else{
                $q->where('interface_status', $search->invoice_status);
            }
        }
        return $q;
    }

    public function getInvoiceDateFormatAttribute()
    {
        return date('d-m-Y', strtotime($this->invoice_date));
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
