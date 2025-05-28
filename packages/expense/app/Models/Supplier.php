<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'oagap_supplier_v';
    protected $connection = 'oracle';
}
