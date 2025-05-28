<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Packages\expense\app\Models\OrganizationV;

class EmployeesV extends Model
{
    // protected $table = 'oaghr_employees_v';
    protected $table = 'oag_hr_employee_v';
    protected $connection = 'oracle';

    public function organization()
    {
        return $this->hasOne(OrganizationV::class, 'location_id', 'location_id');
    }
}
