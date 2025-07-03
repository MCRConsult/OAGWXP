<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class ARBudgetReceiptV extends Model
{
    // protected $table = 'OAG_AR_BUDGET_RECEIPT_NUMBER_ID';
    protected $table = 'oagwxp_ar_budget_receipts_view';
    protected $connection = 'oracle_oagwxp';
}
