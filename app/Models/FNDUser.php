<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FNDUser extends Model
{
    protected $table = 'fnd_user';
    protected $connection = 'oracle';

    public function employee()
    {
        return $this->belongsTo(PerPeopleV7::class, 'employee_id', 'person_id');
    }

    public function hrEmployee()
    {
        return $this->hasOne(EmployeesV::class, 'person_id', 'employee_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'fnd_user_id', 'user_id');
    }

    public function scopeActive($q)
    {
        return $q->whereRaw('
                trunc(sysdate) between trunc(start_date) and nvl(trunc(end_date), trunc(sysdate))
            ');
    }

    public function isActive()
    {
        $sysdate    = \Carbon\Carbon::now()->timezone('Asia/Bangkok');
        $startDate  = optional($this->start_date)->timezone('Asia/Bangkok') ?? $sysdate;
        $endDate    = optional($this->end_date)->timezone('Asia/Bangkok') ?? $sysdate;


        return $sysdate >= $startDate && $sysdate <= $endDate;
    }
}
