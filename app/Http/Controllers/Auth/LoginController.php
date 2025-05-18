<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\FNDUser;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;
    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(Request $request)
    {
        $userName = request()->input('username');
        $password = request()->input('password');
        $syncProcess = false;

        $user = User::where('name', $userName)->first();
        $searchFndUser = FNDUser::where('user_name', $userName)->first();

        if (is_null($user) && !is_null($searchFndUser)) {
            $syncProcess = true;
            (new \App\Repositories\UserRepo)->sync($searchFndUser->user_id);
            $user = User::where('name', $userName)->first();
        }

        if (is_null($user)) {
            return redirect()->route('login')->withErrors('login failed, these credentials do not match')->withInput();
        }

        if (!$syncProcess) {
            (new \App\Repositories\UserRepo)->sync($user->fnd_user_id);
        }

        $user->refresh();

        if (!$user->is_active || is_null($user->is_active)) {
            return redirect()->route('login')->withErrors("{$user->name} : status inactive")->withInput();
        }

        if (is_null($fndUser = $user->fndUser)) {
            return redirect()->route('login')->withErrors('User Oracle : these credentials do not match')->withInput();
        }

        $login  = \DB::connection('oracle')->table('DUAL')
                    ->selectRaw("fnd_web_sec.validate_login ('{$fndUser->user_name}', '{$password}') AUTH")
                    ->first();

        if ($login->auth == 'Y') {
            \Auth::login($user, $request->remember);
            $this->setSession();
            return redirect('/');
        } else {
            return redirect()->route('login')->withErrors('login failed Oracle : these credentials do not match')->withInput();
            if (\Auth::attempt(['name' => $userName, 'password' => $password])) {
                return redirect('/');
            }
        }

        return redirect()->route('login')->withErrors('login failed Oracle : these credentials do not match')->withInput();
    }

    public function username()
    {
        return 'username';
    }

    public function loginById($userId)
    {
        \Auth::loginUsingId($userId);

        $user = User::find($userId);
        // session([
        //     'user_roles' => $user->getOracleRoles(),
        //     'program_roles' => getProgramRoles()
        // ]);

        return redirect()->route('main');
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        $route = '/login';
        \Auth::guard()->logout();
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect($route);
    }

    public function setSession()
    {
        $db = \DB::connection('oracle')->table('v$database')->first();
        session([
            'db_name' => $db->name
        ]);
    }
}
