<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Lang;
use Request;
use Response;
use Session;

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

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin';
    
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendFailedLoginResponse(\Illuminate\Http\Request $request)
    {
        if (Request::ajax()) {
            return Response::json([
                'code'    => 403,
                'message' => Lang::get('auth.failed'),
            ]);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => Lang::get('auth.failed'),
            ]);
    }

    public function authenticated(\Illuminate\Http\Request $request)
    {
        if ($request->route()->domain() == env('SITARIUM_ADMIN_WEBSITE') && Auth::user()->admin !== true) {
            Auth::logout();
            if ($request->ajax() || $request->wantsJson()) {
                return Response::json([
                    'code'    => 401,
                    'message' => Lang::get('sitarium.unauthorized_exception'),
                ], 401);
            } else {
                return redirect()->guest('login', 401);
            }
        }

        $intended_url = Session::pull('url.intended', $this->redirectTo);

        if (Request::ajax()) {
            return Response::json([
                'code'    => 0,
                'message' => Lang::get('sitarium.authentication_success'),
                'callback_vars' => ['redirect_url' => $intended_url],
            ]);
        }

        return redirect()->intended($intended_url);
    }
}
