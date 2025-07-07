<?php

namespace Packages\expense\app\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;

use Packages\expense\app\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('seq_number')->get();

        return view('expense::settings.permission.index', compact('permissions'));
    }

    public function create()
    {
        return view('expense::settings.permission.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $perm = $request->permission;
        try {
            $perCode = $perm['group'].'_'.$perm['code'];
            $checkPermission = Permission::where('permission_code', $perCode)->first();
            if ($checkPermission) {
                $data = [
                    'status' => 'ERROR',
                    'message' => 'มีข้อมูลสิทธิ์การใช้งานนี้แล้ว'
                ];
                return response()->json($data);
            }

            $permission                     = new Permission;
            $permission->permission_group   = $perm['group'];
            $permission->permission_code    = $perCode;
            $permission->description        = $perm['description'];
            $permission->is_active          = $perm['status'];
            $permission->created_by         = $user->id;
            $permission->updated_by         = $user->id;
            $permission->save();

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

    public function show($permission_id)
    {
        $permission = Permission::findOrFail($permission_id);
        
        return view('expense::settings.permission.show', compact('permission'));
    }

    public function update(Request $request, $permission_id)
    {
        $user = auth()->user();
        $perm = $request->permission;
        try {
            $perCode = $perm['group'].'_'.$perm['code'];

            $permission = Permission::findOrFail($permission_id);
            $permission->permission_group   = $perm['group'];
            $permission->permission_code    = $perCode;
            $permission->description        = $perm['description'];
            $permission->is_active          = $perm['status'];
            $permission->created_by         = $user->id;
            $permission->updated_by         = $user->id;
            $permission->save();

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
