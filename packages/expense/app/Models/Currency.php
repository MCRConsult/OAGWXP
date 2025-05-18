<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'fnd_currencies_vl';
    protected $connection = 'oracle';

    public function scopeEnabled($q)
    {
        return $q->where('currency_flag', 'Y')
                ->where('enabled_flag', 'Y');
    }
}
