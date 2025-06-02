<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceInterfaceLine extends Model
{
    protected $table = 'oagap_invoice_line_inf_temp';
    protected $connection = 'oracle';
    protected $primary_key = null;
    public $incrementing = false;
    public $timestamps = false;
}
