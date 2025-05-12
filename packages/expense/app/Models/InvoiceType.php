<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceType extends Model
{
    protected $table = 'ap_lc_invoice_types_v';
    protected $connection = 'oracle';
    // protected $connection = 'oracle_oagwxp';
}
