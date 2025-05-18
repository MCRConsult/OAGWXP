<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceLine extends Model
{
    protected $table = 'oagwxp_invoice_lines';
    protected $connection = 'oracle_oagwxp';

    public function expense()
    {
        return $this->hasOne(MTLCategoriesV::class, 'category_concat_segs', 'expense_type');
    }
}
