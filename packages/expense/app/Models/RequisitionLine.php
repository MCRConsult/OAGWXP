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
}
