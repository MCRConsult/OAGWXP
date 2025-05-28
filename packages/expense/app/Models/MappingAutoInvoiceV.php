<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class MappingAutoInvoiceV extends Model
{
    protected $table = 'oagap_web_mapping_auto_inv_v';
    protected $connection = 'oracle';
    protected $appends = ['source_type', 'status_icon', 'req_date_format'];

    public function getReqDateFormatAttribute()
    {
        return date('d-m-Y');
    }

    public function getSourceTypeAttribute()
    {
        return 'RECEIPT';
    }

    public function invoiceType()
    {
        return $this->hasOne(InvoiceType::class, 'lookup_code', 'invoice_type');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'vendor_id', 'supplier_id');
    }

    public function getStatusIconAttribute()
    {
        return $this->getStatusIcon('DISBURSEMENT');
    }

    function getStatusIcon($status)
    {
        $result = "";
        switch ($status) {
            case "DISBURSEMENT":
                $result = "<span class='badge badge-success' style='padding: 5px;'> รอเบิกจ่าย </span>";
                break;
            case "ALLOCATE":
                $result = "<span class='badge badge-warning' style='padding: 5px;'> รอจัดสรร </span>";
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
}
