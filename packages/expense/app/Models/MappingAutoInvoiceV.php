<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

use Packages\expense\app\Models\POExpenseAccountRuleV;
use Packages\expense\app\Models\GLPeriod;

class MappingAutoInvoiceV extends Model
{
    protected $table = 'oagap_web_mapping_auto_inv_v';
    protected $connection = 'oracle_oagwxp';
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
        return $this->getStatusIcon('NEW');
    }

    public function invoiceLine()
    {
        return $this->hasOne(InvoiceLine::class, 'reference_req_number', 'req_number')
            ->whereHas('invoice', function ($query) {
                $query->whereRaw("status <> 'CANCELLED'");
            });
    }

    public function scopeSearch($q, $search)
    {
        $cols = ['req_number', 'invoice_type', 'status', 'document_category'];
        foreach ($search as $key => $value) {
            $value = trim($value);
            if ($value) {
                if (in_array($key, $cols)) {
                    $q->where($key, 'like', "%$value%");
                } else if ($key == 'req_date') {
                    $date = date('Y-m-d', strtotime($value));
                    $q->whereDate('req_date', $date);
                }
            }
        }
        return $q;
    }

    function getStatusIcon($status)
    {
        $result = "";
        switch ($status) {
            case "NEW":
                $result = "<span class='badge badge-success' style='padding: 5px;'> รอเบิกจ่าย </span>";
                break;
            case "INTERFACED":
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

    public function mappingExpenseAccount($header, $expeseType)
    {
        $docCate = explode('-', $header->document_category);
        $user = auth()->user();
        $employee = $user->hrEmployee;
        $expenseRules = POExpenseAccountRuleV::where('item_category', $expeseType)
                                            ->orderBy('segment_num')
                                            ->get()
                                            ->pluck('segment_value', 'segment_num');
        //YEAR
        $year = strtoupper(date('M-y'));
        $period = GLPeriod::selectRaw("period_year+543 period_year")->where('period_name', $year)->first();
        $concatenatedSegments = '';
        $segments = [];
        // SEGMENT1
        $segments[1] = $employee->segment1;
        // SEGMENT2
        $segments[2] = $employee->segment2;
        // SEGMENT3
        $segments[3] = date('y', strtotime($period->period_year));
        // SEGMENT4
        $segments[4] = isset($expenseRules[4])? $expenseRules[4]: '000';
        // SEGMENT5-9
        $segments[5] = null;
        $segments[6] = null;
        $segments[7] = null;
        $segments[8] = null;
        $segments[9] = null;
        if ($segments[4] == 510) {
            $segments[5] = isset($expenseRules[5])? $expenseRules[5]: '00000';
            $segments[6] = isset($expenseRules[6])? $expenseRules[6]: '00000';
            $segments[7] = isset($expenseRules[7])? $expenseRules[7]: '00000';
            $segments[8] = isset($expenseRules[8])? $expenseRules[8]: '000000';
            $segments[9] = '00000000000000000000';
        }elseif(in_array($segments[4], [520, 530, 550])){
            $segments[5] = '00000';
            $segments[6] = isset($expenseRules[6])? $expenseRules[6]: '00000';
            $segments[7] = '00000';
            $segments[8] = isset($expenseRules[8])? $expenseRules[8]: '000000';
            $segments[9] = '00000000000000000000';
        }elseif($segments[4] == 540){
            $segments[5] = isset($expenseRules[5])? $expenseRules[5]: '00000';
            $segments[6] = isset($expenseRules[6])? $expenseRules[6]: '00000';
            $segments[7] = isset($expenseRules[7])? $expenseRules[7]: '00000';
            $segments[8] = isset($expenseRules[8])? $expenseRules[8]: '000000';
            $segments[9] = '00000000000000000000';
        }elseif($docCate[1] == 'สบพ.'){
            $segments[5] = isset($expenseRules[5])? $expenseRules[5]: '00000';
            $segments[6] = isset($expenseRules[6])? $expenseRules[6]: '00000';
            $segments[7] = isset($expenseRules[7])? $expenseRules[7]: '00000';
            $segments[8] = isset($expenseRules[8])? $expenseRules[8]: '000000';
            $segments[9] = '00000000000000000000';
        }else{
            $segments[5] = isset($expenseRules[5])? $expenseRules[5]: '00000';
            $segments[6] = isset($expenseRules[6])? $expenseRules[6]: '00000';
            $segments[7] = isset($expenseRules[7])? $expenseRules[7]: '00000';
            $segments[8] = isset($expenseRules[8])? $expenseRules[8]: '000000';
            $segments[9] = '00000000000000000000';
        }
        // SEGMENT10
        $segments[10] = isset($expenseRules[10])? $expenseRules[10]: '0000000000';
        // SEGMENT11
        $segments[11] = isset($expenseRules[11])? $expenseRules[11]: '000000';
        // SEGMENT12
        $segments[12] = '00';
        // SEGMENT13
        $segments[13] = '00';

        $concatenatedSegments = $segments[1].'.'.$segments[2].'.'.$segments[3].'.'.$segments[4].'.'.$segments[5].'.'.$segments[6].'.'.$segments[7].'.'.$segments[8].'.'.$segments[9].'.'.$segments[10].'.'.$segments[11].'.'.$segments[12].'.'.$segments[13];

        return $concatenatedSegments;
    }
}
