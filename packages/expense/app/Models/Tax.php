<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = 'ZX_TAXES_VL';
    protected $connection = 'oracle';
}
