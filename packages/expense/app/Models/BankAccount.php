<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table = 'oagce_bank_account_v';
    protected $connection = 'oracle';
}
