<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = 'ZX_RATES_VL';
    protected $connection = 'oracle';
}
