<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierSite extends Model
{
    protected $table = 'oagap_supplier_site_v';
    protected $connection = 'oracle';
}
