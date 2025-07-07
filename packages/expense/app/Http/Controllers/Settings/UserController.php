<?php

namespace Packages\expense\app\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;

use App\Models\User;
use Packages\expense\app\Models\Permission;

class UserController extends Controller
{
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
        // $permission = Permission::

        return view('expense::settings.user.show', compact('user'));
    }

    public function update(Request $request, $userId)
    {
        $user = auth()->user();
        \DB::beginTransaction();
        try {
            $user = User::findOrFail($userId);
            $user->is_active = $request->status;
            $user->save();

            \DB::commit();
            $data = [
                'status' => 'SUCCESS',
                'message' => ''
            ];
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error($e);
            $data = [
                'status' => 'ERROR',
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($data);
    }
}
