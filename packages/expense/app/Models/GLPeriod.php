<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class GLPeriod extends Model
{
    protected $table = 'GL_PERIODS';
    protected $connection = 'oracle';

    protected static function booted(): void
    {
        static::addGlobalScope('defaultSetName', function ($q) {
            $q->where('period_set_name','OAG Calendar');
        });
    }
}
