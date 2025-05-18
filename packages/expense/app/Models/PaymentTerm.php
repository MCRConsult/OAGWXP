<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTerm extends Model
{
    protected $table = 'AP_TERMS';
    protected $connection = 'oracle';
}
