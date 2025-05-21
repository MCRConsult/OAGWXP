<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionLine extends Model
{
    protected $table = 'oagwxp_requisition_lines';
    protected $connection = 'oracle_oagwxp';

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

    public function expenseType()
    {
        return $this->hasOne(MTLCategoriesV::class, 'category_concat_segs', 'expense_type');
    }
}
