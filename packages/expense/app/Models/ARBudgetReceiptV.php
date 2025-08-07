<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class ARBudgetReceiptV extends Model
{
    protected $table = 'oagwxp_ar_budget_receipts_view';
    protected $connection = 'oracle_oagwxp';
}
