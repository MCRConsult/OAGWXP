<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceLine extends Model
{
    protected $table = 'oagwxp_invoice_lines';
    protected $connection = 'oracle_oagwxp';

    public function invoice()
    {
        return $this->hasOne(InvoiceHeader::class, 'id', 'invoice_header_id');
    }

    public function requisitionLine()
    {
        return $this->hasOne(RequisitionLine::class, 'invl_reference_id', 'id');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'vendor_id', 'supplier_id');
    }

    public function expense()
    {
        return $this->hasOne(MTLCategoriesV::class, 'category_concat_segs', 'expense_type');
    }

    public function budgetPlan()
    {
        return $this->hasOne(MTLCategoriesV::class, 'category_concat_segs', 'budget_plan');
    }

    public function budgetType()
    {
        return $this->hasOne(MTLCategoriesV::class, 'category_concat_segs', 'budget_type');
    }

    public function tax()
    {
        return $this->hasOne(Tax::class, 'tax', 'tax_code')->selectRaw('tax, tax_id');
    }

    public function wht()
    {
        return $this->hasOne(WHT::class, 'tax_id', 'wht_code');
    }

    public function arReceipt()
    {
        return $this->hasOne(ARReceiptNumberAllV::class, 'cash_receipt_id', 'ar_receipt_id');
    }
}
