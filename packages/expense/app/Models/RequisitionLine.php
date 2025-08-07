<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionLine extends Model
{
    protected $table = 'oagwxp_requisition_lines';
    protected $connection = 'oracle_oagwxp';
    protected $appends = ['remaining_rceipt_detail'];

    public function header()
    {
        return $this->hasOne(RequisitionHeader::class, 'id', 'req_header_id');
    }

    public function clearLine()
    {
        return $this->hasOne(self::class, 'id', 'clear_reference_line_id');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'vendor_id', 'supplier_id');
    }

    public function budgetPlan()
    {
        return $this->hasOne(MTLCategoriesV::class, 'category_concat_segs', 'budget_plan');
    }

    public function budgetType()
    {
        return $this->hasOne(MTLCategoriesV::class, 'category_concat_segs', 'budget_type');
    }

    public function expense()
    {
        return $this->hasOne(MTLCategoriesV::class, 'category_concat_segs', 'expense_type');
    }

    public function vehicleOilType()
    {
        return $this->hasOne(FlexValueV::class, 'flex_value', 'vehicle_oil_type')->where('flex_value_set_name', 'OAG_VEH_OIL_TYPE');
    }

    public function utilityType()
    {
        return $this->hasOne(FlexValueV::class, 'flex_value', 'utility_type')->where('flex_value_set_name', 'OAG_AP_PUBLIC_UTILITIES');
    }

    public function utilityDetail()
    {
        return $this->hasOne(FlexValueV::class, 'flex_value', 'utility_detail')->where('flex_value_set_name', 'OAG_AP_BUILDING/CODE/DAD');
    }

    public function contract()
    {
        return $this->hasOne(ARPOATT1V::class, 'attribute1', 'contract_number');
    }

    public function getRemainingRceiptDetailAttribute()
    {
        if ($this->header->budget_source == '510') {
            $receipt = OAGARBudgetReceiptV::where('cash_receipt_id', $this->remaining_receipt_id)->first();
        }else{
            $receipt = GuaranteeReceiptV::selectRaw('receipt_number, activity_name description')->where('cash_receipt_id', $this->remaining_receipt_id)->first();
        }
        return optional($receipt)->receipt_number.' : '.optional($receipt)->description;
    }
}
