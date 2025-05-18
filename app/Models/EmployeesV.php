<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeesV extends Model
{
    // protected $table = 'oaghr_employees_v';
    protected $table = 'oag_hr_employee_v';
    protected $connection = 'oracle';
}
