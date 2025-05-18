<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class GLPeriod extends Model
{
    protected $table = 'GL_PERIOD_SETS';
    protected $connection = 'oracle';
}
