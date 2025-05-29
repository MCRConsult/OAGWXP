<?php

namespace Packages\expense\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;

class InvoiceHeader extends Model
{
    protected $table = 'oagwxp_invoice_headers';
    protected $connection = 'oracle_oagwxp';
    protected $appends = ['status_text', 'status_icon', 'invoice_date_format', 'clear_date_format'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function lines()
    {
        return $this->hasMany(InvoiceLine::class, 'invoice_header_id');
    }

    public function requisition()
    {
        return $this->hasOne(RequisitionHeader::class, 'invoice_reference_id', 'id');
    }

    public function invoiceType()
    {
        return $this->hasOne(InvoiceType::class, 'lookup_code', 'invoice_type');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'vendor_id', 'supplier_id');
    }

    public function paymentMethod()
    {
        return $this->hasOne(PaymentMethod::class, 'payment_method_code', 'payment_method')->select('description');
    }

    public function paymentTerm()
    {
        return $this->hasOne(PaymentTerm::class, 'term_id', 'payment_term')->select('description');
    }

    public function finalJudgment()
    {
        return $this->hasOne(FlexValueV::class, 'flex_value', 'final_judgment')->where('flex_value_set_name', 'OAG_VALUE_SET_Y_N')->select('description');
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
                'Packages\expense\app\Models\InvoiceHeader',
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

    public function getStatusTextAttribute()
    {
        return $this->getStatusText($this->status);
    }

    function getStatusText()
    {
        $status = $this->status;
        $result = "";
        switch ($status) {
            case "NEW":
                $result = 'รอเบิกจ่าย';
                break;
            case "INTERFACED":
                $result = 'เบิกจ่ายแล้ว';
                break;
            case "CANCELLED":
                $result = 'ยกเลิก';
                break;
            default:
                $result = 'รายการใหม่';
                break;
        }
        return $result;
    }

    function getStatusIcon()
    {
        $status = $this->status;
        $result = "";
        switch ($status) {
            case "NEW":
                $result = "<span class='badge badge-success' style='padding: 5px;'> รอเบิกจ่าย </span>";
                break;
            case "CONFIRM":
                $result = "<span class='badge badge-warning' style='padding: 5px;'> รอเบิกจ่าย </span>";
                break;
            case "INTERFACED":
                $result = "<span class='badge badge-warning' style='padding: 5px;'> เบิกจ่ายแล้ว </span>";
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

    public function getInvoiceDateFormatAttribute()
    {
        return date('d-m-Y', strtotime($this->invoice_date));
    }

    public function getClearDateFormatAttribute()
    {
        return $this->clear_date? date('d-m-Y', strtotime($this->clear_date)): '';
    }

    public function scopeSearch($q, $search)
    {
        $cols = ['voucher_number', 'invoice_type', 'status'];
        foreach ($search as $key => $value) {
            $value = trim($value);
            if ($value) {
                if (in_array($key, $cols)) {
                    $q->where($key, 'like', "%$value%");
                } else if ($key == 'invoice_date') {
                    $date = date('Y-m-d', strtotime($value));
                    $q->whereDate('invoice_date', $date);
                }
            }
        }
        return $q;
    }
}
