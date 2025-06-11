<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class FlexValueV extends Model
{
    protected $table = 'OAGFND_FLEX_VALUE_V';
    protected $connection = 'oracle';

    protected static function booted(): void
    {
        static::addGlobalScope('checkEnabled', function ($q) {
            $q->where('enabled_flag', 'Y');
        });
    }
}
