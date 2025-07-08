<?php

namespace Packages\expense\app\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;

use App\Models\User;
use Packages\expense\app\Models\Permission;
use Packages\expense\app\Models\PermissionUser;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check()) {
                auth()->user()->loadPermissions();
            }
            return $next($request);
        });
    }

    public function index()
    {
        $search = request();
        $users = User::search($search)
                        ->with(['hrEmployee', 'organizationV', 'location'])
                        ->when($search, function ($query) use ($search) {
                            return $query->whereHas('hrEmployee', function ($query) use ($search) {
                                $query->whereRaw('full_name like ?', ['%'.strtoupper($search->username).'%'])
                                    ->orWhereRaw('employee_number like ?', ['%'.strtoupper($search->username).'%']);
                            });
                        })
                        ->orderBy('name')
                        ->paginate(25);
        $statuses = ['ACTIVE' => 'ใช้งานอยู่'
                    , 'INACTIVE' => 'ไม่ใช้งาน'];

        return view('expense::settings.user.index', compact('users', 'statuses'));
    }

    public function show($userId)
    {
        $user = User::with(['hrEmployee', 'organizationV', 'location'])
                    ->where('id', $userId)
                    ->first();
        // PERMISSION
        $permissionGroups = Permission::selectRaw("distinct permission_group")->get()->groupBy('permission_group');
        $permissions = Permission::orderBy('id')->get()->groupBy('permission_group');
        $permissionUsers = PermissionUser::with(['permission'])->where('user_id', $userId)->get();

        return view('expense::settings.user.show', compact('user', 'permissionGroups', 'permissions', 'permissionUsers'));
    }

    public function update(Request $request, $userId)
    {
        $user = auth()->user();
        try {
            // ====== UPDATE STATUS USER
            $user = User::findOrFail($userId);
            $user->is_active = $request->status;
            $user->save();

            // ====== UPDATE PERMISSION
            // 1 DELETE PERMISSION NOT IN ARRAYS
            $listPerms = $request->listPerms;
            $permissions = Permission::whereIn('permission_code', $request->listPerms)->get()->pluck('id')->toArray();
            PermissionUser::where('user_id', $userId)
                            ->whereNotIn('permission_id', $permissions)
                            ->delete();
            // 2 INSERT PERMISSION WITH NOT IN PERM
            foreach ($listPerms as $perm) {
                $permission = Permission::where('permission_code', $perm)->first();
                $chkPermUser = PermissionUser::where('user_id', $userId)
                                            ->where('permission_id', $permission->id)
                                            ->first();
                if (!$chkPermUser) {
                    $permUser                   = new PermissionUser;
                    $permUser->user_id          = $userId;
                    $permUser->permission_id    = $permission->id;
                    $permUser->save();
                }
            }
            $data = [
                'status' => 'SUCCESS',
                'message' => ''
            ];
        } catch (\Exception $e) {
            \Log::error($e);
            $data = [
                'status' => 'ERROR',
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($data);
    }
}
