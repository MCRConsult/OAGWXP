<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class ARReceiptAccountV extends Model
{
    protected $table = 'oagap_web_mapping_auto_inv_lines_v';
    protected $connection = 'oracle';
}
