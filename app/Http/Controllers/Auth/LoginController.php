<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;

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
        $username = request()->input('username');
        $password = request()->input('password');
        $syncProcess = false;

        $user = User::where('name', $username)->first();
        $searchFndUser = FNDUser::where('user_name', $username)->first();

        if (is_null($user) && !is_null($searchFndUser)) {
            $syncProcess = true;
            (new \App\Repositories\UserRepo)->sync($searchFndUser->user_id);
            $user = User::where('name', $username)->first();
        }

        if (is_null($user)) {
            return redirect()->route('login')->withErrors('ไม่สามารถเข้าสู่ระบบได้ เนื่องจากข้อมูลผู้ใช้งานไม่ถูกต้อง กรุณาติดต่อผู้ดูแลระบบ')->withInput();
        }

        if (!$syncProcess) {
            (new \App\Repositories\UserRepo)->sync($user->fnd_user_id);
        }

        $user->refresh();

        if (!$user->is_active || is_null($user->is_active)) {
            return redirect()->route('login')->withErrors("{$user->name} : สถานะผู้ใช้งาน ไม่พร้อมใช้งาน กรุณาติดต่อผู้ดูแลระบบ")->withInput();
        }

        if (is_null($fndUser = $user->fndUser)) {
            return redirect()->route('login')->withErrors('ไม่สามารถเข้าสู่ระบบได้ เนื่องจากข้อมูลผู้ใช้งานไม่ถูกต้อง กรุณาติดต่อผู้ดูแลระบบ')->withInput();
        }

        $login  = \DB::connection('oracle')->table('DUAL')
                    ->selectRaw("fnd_web_sec.validate_login ('{$fndUser->user_name}', '{$password}') AUTH")
                    ->first();

        if ($login->auth == 'Y') {
            \Auth::login($user, $request->remember);
            // If "Remember Me" is checked, save credentials in cookies
            if ($request->remember) {
                // 30 Days
                Cookie::queue('remember_username', $username, 43200);
                Cookie::queue('remember_password', Crypt::encrypt($password), 43200); // Encrypt the password
            }
            // else {
            //     // Clear cookies if "Remember Me" is unchecked
            //     Cookie::queue(Cookie::forget('remember_username'));
            //     Cookie::queue(Cookie::forget('remember_password'));
            // }
            $this->setSession();
            return redirect('/OAGWXP');
        } else {
            return redirect()->route('login')->withErrors('ไม่สามารถเข้าสู่ระบบได้ เนื่องจากข้อมูลผู้ใช้งานไม่ถูกต้อง กรุณาติดต่อผู้ดูแลระบบ')->withInput();
            if (\Auth::attempt(['name' => $username, 'password' => $password])) {
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
        $route = '/OAGWXP/login';
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
