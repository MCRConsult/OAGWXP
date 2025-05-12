<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FNDUser extends Model
{
    protected $table        = 'per_people_v7';
    protected $connection   = 'oracle';

    protected static function booted(): void
    {
        static::addGlobalScope('selectedColumn', function ($q) {
            $q->select('person_id', 'full_name', 'global_name', 'attribute1');
        });
    }
}
