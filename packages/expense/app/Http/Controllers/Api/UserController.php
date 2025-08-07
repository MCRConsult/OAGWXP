<?php

namespace Packages\expense\app\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\User;

class UserController extends Controller
{
    public function fetchUserRenderPage()
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
                    ->get();

        $perPage = 25;
        $currPage = (int)request()->page;
        $users = collect($users)->all();
        $respUsers = new LengthAwarePaginator(
            array_slice($users, ($currPage - 1) * $perPage, $perPage),
            count($users), 
            $perPage,
            $currPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $data = [
            'users' => $respUsers
        ];
        return response()->json($data);
    }
}
