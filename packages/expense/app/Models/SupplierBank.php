<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierBank extends Model
{
    protected $table = 'oagap_supplier_bank_v';
    protected $connection = 'oracle';
    // protected $connection = 'oracle_oagwxp';
}
