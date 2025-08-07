<?php
namespace App\Repositories;

use App\Models\FNDUser;
use App\Models\User;
use App\Models\OrganizationV;
use App\Models\InvOrganizationV;

class UserRepo {

    public function sync($fndUserId = -999)
    {
        ini_set("memory_limit","1024M");
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        $fndUsers = FNDUser::when($fndUserId != -999, function ($query) use ($fndUserId) {
                        return $query->where('user_id', $fndUserId);
                    })
                    // ->whereIn('created_by', [1130, -1])
                    // ->whereNotIn('user_id', [1170,1190])
                    // ->whereRaw("nvl(end_date, sysdate) >= sysdate")
                    ->select(['user_id', 'user_name', 'start_date', 'end_date', 'employee_id', 'email_address'])
                    ->with(['users'])
                    ->get();

        foreach ($fndUsers as $key => $fndUser) {
            $users = $fndUser->users;
            logger($fndUser->user_name);
            $active = $fndUser->isActive();
            // GET ORG ID FROM LOCATION
            $orgId = '';
            $organization = OrganizationV::where('location_id', $fndUser->hrEmployee->location_id)->first();
            if (!$organization) {
                $organization = InvOrganizationV::where('location_id', $fndUser->hrEmployee->location_id)->first();
                $orgId = $organization? $organization->hr_organization_id: '';
            }else{
                $orgId = $organization? $organization->organization_id: '';
            }

            if ($users->isNotEmpty()) {
                foreach ($users as $key => $user) {
                    $updateFlag = false;
                    if ($user->person_id != $fndUser->employee_id) {
                        $updateFlag = true;
                        $user->person_id = $fndUser->employee_id;
                    }

                    if ($user->org_id != $orgId) {
                        $updateFlag = true;
                        $user->org_id = $orgId;
                    }

                    if ($user->location_id != $fndUser->hrEmployee->location_id) {
                        $updateFlag = true;
                        $user->location_id = $fndUser->hrEmployee->location_id;
                    }

                    if (is_null($user->email)) {
                        $updateFlag = true;
                        $user->email = $fndUser->email_address;
                    }

                    if ($user->is_active != $active && is_null($user->password)) {
                        $updateFlag = true;
                        $user->is_active = $active;
                    }

                    if (is_null($user->password)) {
                        if ($user->name != $fndUser->user_name) {
                            $updateFlag = true;
                            $user->name = $fndUser->user_name;
                        }
                    }

                    if ($updateFlag) {
                        try {
                            $user->save();
                            \Log::info("---------------- User: $user->id", [$user->getChanges()]);
                        } catch (\Exception $e) {
                            \Log::error($e);
                            \Log::error("--------------- UserRepo@sync", [$user]);
                        }
                    }
                }
            } else {
                if ($active) {
                    $user               = new User;
                    $user->name         = strtoupper($fndUser->user_name);
                    $user->org_id       = $orgId;
                    $user->location_id  = $fndUser->hrEmployee->location_id;
                    $user->fnd_user_id  = $fndUser->user_id;
                    $user->person_id    = $fndUser->employee_id;
                    $user->email        = $fndUser->email_address;
                    $user->is_active    = true;
                    $user->save();
                }
            }
        }
    }
}