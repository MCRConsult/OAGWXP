<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    // protected $table = 'FND_DOC_SEQUENCE_ASSIGNMENTS';
    protected $table = 'oagap_document_category_v';
    protected $connection = 'oracle';

    protected static function booted(): void
    {
        static::addGlobalScope('defaultApplication', function ($q) {
            $q->where('attribute1', auth()->user()->org_id)
                ->where('table_name', 'AP_INVOICES_ALL');
        });
    }
}
