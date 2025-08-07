<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class APInvoiceStatusV extends Model
{
    protected $table = 'oagap_invoice_web_status_v';
    protected $connection = 'oracle_oagwxp';
}
