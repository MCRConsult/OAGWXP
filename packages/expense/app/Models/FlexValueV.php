<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class FlexValueV extends Model
{
    protected $table = 'OAGFND_FLEX_VALUE_V';
    protected $connection = 'oracle';
    // protected $connection = 'oracle_oagwxp';
}
