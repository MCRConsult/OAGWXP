<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
    ];

    public function boot(Gate $gate)
    {
        $this->registerPolicies($gate);
        // กำหนด Gate สำหรับการตรวจสอบ permission
        foreach ($this->getPermissions() as $permission) {
            Gate::define($permission->permission_code, function ($user) use ($permission) {
                return $user->permissions()->where('permission_id', $permission->id)->exists();
            });
        }
    }

    private function getPermissions()
    {
        // $minutes = 3600; // 1 hr
        // $minutes = now()->addHours(24);
        // return \Cache::remember("permissions", $minutes, function () {
        //     return \Packages\expense\app\Models\Permission::all();
        // });
        return \Packages\expense\app\Models\Permission::all();
    }
}
