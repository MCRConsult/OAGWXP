<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionLine extends Model
{
    protected $table = 'oagwxp_requisition_lines';
    protected $connection = 'oracle_oagwxp';

    public function header()
    {
        return $this->hasOne(RequisitionHeader::class, 'id', 'req_header_id');
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
}
