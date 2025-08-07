<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class WHT extends Model
{
    protected $table = 'OAGAP_TAX_CODE_V';
    protected $connection = 'oracle';
}
