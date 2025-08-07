<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class GLLedger extends Model
{
    protected $table = 'gl_ledgers';
    protected $connection = 'oracle';
}
