<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionReceiptTemp extends Model
{
    protected $table = 'oagwxp_requisition_receipt_temps';
    protected $connection = 'oracle_oagwxp';
}
