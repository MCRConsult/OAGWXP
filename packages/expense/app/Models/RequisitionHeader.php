<?php

namespace Packages\expense\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;

class RequisitionHeader extends Model
{
    protected $table = 'oagwxp_requisition_headers';
    protected $connection = 'oracle_oagwxp';
    protected $dates = ['req_date', 'clear_date'];
    protected $appends = ['status_icon', 'req_date_format'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function lines()
    {
        return $this->hasMany(RequisitionLine::class, 'req_header_id');
    }

    public function invoiceType()
    {
        return $this->hasOne(InvoiceType::class, 'lookup_code', 'invoice_type');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'vendor_id', 'supplier_id');
    }

    public function budgetSource()
    {
        return $this->hasOne(FlexValueV::class, 'flex_value', 'budget_source')->where('flex_value_set_name', 'OAG_GL_BUDGET_SOURCE');
    }

    public function paymentType()
    {
        return $this->hasOne(LookupV::class, 'lookup_code', 'payment_type')->where('lookup_type', 'OAG_AP_PAYMENT_TYPE')->select('description');
    }

    public function getInvRef($invType)
    {
        if($invType == 'PREPAYMENT') {
            return 'ADV';
        }else {
            return 'INV';
        }
    }

    public static function genDocumentNo($orgId, $prefix)
    {
        $date = now()->addYear(543)->format('y');
        do {
            $runningTranId = \Packages\expense\app\Models\TransactionSeq::getTranID(
                $orgId,
                'Packages\expense\app\Models\RequisitionHeader',
                $date,
                $prefix
            );
            $doc = $date.'-'.$prefix.'-'.str_pad($runningTranId, 10, '0', STR_PAD_LEFT);
        } while(self::checkDupDocumentNo($orgId, $doc));

        $result = $doc;
        return $result;
    }

    private static function checkDupDocumentNo($orgId,$invDocNo)
    {
        return false;
    }

    public function getStatusIconAttribute()
    {
        return $this->getStatusIcon($this->status);
    }

    function getStatusIcon()
    {
        $status = $this->status;
        $result = "";
        switch ($status) {
            case "COMPLETED":
                $result = "<span class='badge badge-success' style='padding: 5px;'> รอเบิกจ่าย </span>";
                break;
            case "PENDING":
                $result = "<span class='badge badge-warning' style='padding: 5px;'> รอจัดสรร </span>";
                break;
            case "HOLD":
                $result = "<span class='badge badge-warning' style='padding: 5px;'> รอตรวจสอบ </span>";
                break;
            case "CANCELLED":
                $result = "<span class='badge badge-danger' style='padding: 5px;'> ยกเลิก </span>";
                break;
            default:
                $result = "<span class='badge badge-secondary' style='padding: 5px;'> รายการใหม่ </span>";
                break;
        }
        return $result;
    }

    public function getReqDateFormatAttribute()
    {
        return date('d-m-Y', strtotime($this->req_date));
    }

    public function scopeSearch($q, $search)
    {
        $cols = ['req_number', 'invoice_type', 'status'];
        foreach ($search as $key => $value) {
            $value = trim($value);
            if ($value) {
                if (in_array($key, $cols)) {
                    $q->where($key, 'like', "%$value%");
                } else if ($key == 'req_date') {
                    // $date = \DateTime::createFromFormat(trans('date.format'), $value);
                    $date = date('Y-m-d', strtotime($value));
                    $q->whereDate('req_date', $date);
                }
            }
        }
        return $q;
    }
}
