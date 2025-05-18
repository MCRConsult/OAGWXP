<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class MTLCategoriesV extends Model
{
    protected $table = 'MTL_CATEGORIES_V';
    protected $connection = 'oracle';

    protected static function booted(): void
    {
        static::addGlobalScope('enabledFlag', function ($q) {
            $q->selectRaw('distinct structure_name, category_concat_segs, description')
                ->where('enabled_flag', 'Y');
        });
    }
}
