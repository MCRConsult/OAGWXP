<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class LookupV extends Model
{
    protected $table = 'FND_LOOKUP_VALUES_VL';
    protected $connection = 'oracle';

    protected static function booted(): void
    {
        static::addGlobalScope('defaultApplication', function ($q) {
            $q->where('enabled_flag', 'Y');
        });
    }
}
