<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'iby_payment_methods_vl';
    protected $connection = 'oracle';
}
