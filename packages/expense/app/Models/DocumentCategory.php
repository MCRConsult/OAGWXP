<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    protected $table = 'FND_DOC_SEQUENCE_ASSIGNMENTS';
    protected $connection = 'oracle';

    protected static function booted(): void
    {
        static::addGlobalScope('defaultApplication', function ($q) {
            $q->where('application_id', 200)
                ->where('attribute1', auth()->user()->org_id);
        });
    }
}
