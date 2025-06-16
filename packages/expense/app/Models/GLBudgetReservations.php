<?php

namespace Packages\expense\app\Models;

use Illuminate\Database\Eloquent\Model;

class GLBudgetReservations extends Model
{
    protected $table = 'oaggl_budget_reservations';
    protected $connection = 'oracle';
    protected $primary_key = null;
    public $incrementing = false;
    public $timestamps = false;
}
