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
        // return $this->hasOne(FNDUser::class, 'user_id', 'fnd_user_id');
    }

    public function employee()
    {
        return $this->belongsTo(PerPeopleV7::class, 'person_id', 'person_id');
    }

    public function hrEmployee()
    {
        return $this->belongsTo(EmployeesV::class, 'person_id', 'person_id');
    }

    public function location()
    {
        return $this->hasOne(Location::class, 'location_id', 'location_id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', 1);
    }


    // public function setSession()
    // {
    //     $orgId = 102;
    //     $org = \Package\inventory\app\Models\MtlParameter::selectRaw("organization_id, organization_code")
    //             ->where('organization_id', $orgId)
    //             ->first();

    //     $subinv = \Package\inventory\app\Models\MtlSecondaryInventories::selectRaw('secondary_inventory_name, description')
    //                 ->where('organization_id', $orgId)
    //                 ->orderBy('secondary_inventory_name')
    //                 ->get();

    //     $orgDef = OrgOrganizationDefinition::where('organization_id', $orgId)->first();
    //     $ou = null;
    //     if ($orgDef) {
    //         $ou = HrOperatingUnit::where('organization_id', $orgDef->operating_unit)->first();
    //     }

    //     $emp = $this->employee;
    //     session([
    //         'ou' => $ou,
    //         'user' => auth()->user(),
    //         'organization_id' => optional($org)->organization_id ?? '',
    //         'organization_code' => optional($org)->organization_code ?? '',
    //         'subinventory_code' => '',
    //         'subinventory_desc' => '',
    //         'branch_code' => optional($emp)->segment5 ?? '',
    //         'mtl_secondary_inventories' => $subinv,
    //     ]);

    //     return;
    // }
}
