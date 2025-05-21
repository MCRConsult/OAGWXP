<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'AP_SUPPLIERS';
    protected $connection = 'oracle';
}
