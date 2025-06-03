<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $table = 'oagwxp_users';
    protected $connection = 'oracle_oagwxp';
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function fndUser()
    {
        return $this->belongsTo(FNDUser::class, 'fnd_user_id', 'user_id');
    }

    public function employee()
    {
        return $this->belongsTo(PerPeopleV7::class, 'person_id', 'person_id');
    }

    public function hrEmployee()
    {
        return $this->belongsTo(EmployeesV::class, 'person_id', 'person_id');
    }

    public function organizationV()
    {
        return $this->hasOne(OrganizationV::class, 'organization_id', 'org_id');
    }

    public function location()
    {
        return $this->hasOne(Location::class, 'location_id', 'location_id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', 1);
    }

    public function scopeSearch($q, $search)
    {
        $status = $search->status == 'ACTIVE'? true: false;  
        if ($search->status) {
            $q->where('is_active', $status);
        }
               
        return $q;
    }
}
