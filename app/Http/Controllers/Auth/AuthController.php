<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Lang;
use Request;
use Response;
use Session;
use Validator;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    private $loginView = 'admin.login';

    private $registerView = 'admin.register';

    private $redirectPath = '/admin';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
//     protected function validator(array $data)
//     {
//         return Validator::make($data, [
//             'name' => 'required|max:255',
//             'email' => 'required|email|max:255|unique:users',
//             'password' => 'required|min:6|confirmed',
//         ]);
//     }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
//     protected function create(array $data)
//     {
//         return User::create([
//             'name' => $data['name'],
//             'email' => $data['email'],
//             'password' => bcrypt($data['password']),
//         ]);
//     }

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
                'message' => $this->getFailedLoginMessage(),
            ]);
        }

        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }

    public function authenticated(\Illuminate\Http\Request $request)
    {
        if ($request->route()->domain() == env('SITARIUM_ADMIN_WEBSITE') && Auth::user()->admin !== true) {
            Auth::logout();
            if ($request->ajax() || $request->wantsJson()) {
                return Response::json([
                    'code'    => 401,
                    'message' => Lang::has('sitarium.unauthorized_exception')
                                    ? Lang::get('sitarium.unauthorized_exception')
                                    : 'Unauthorized Exception!',
                ], 401);
            } else {
                return redirect()->guest('login', 401);
            }
        }

        $intended_url = Session::pull('url.intended', $this->redirectPath);

        if (Request::ajax()) {
            return Response::json([
                'code'    => 0,
                'message' => Lang::has('sitarium.authentication_success')
                                ? Lang::get('sitarium.authentication_success')
                                : 'Authentication successful!',
                'callback_vars' => ['redirect_url' => $intended_url],
            ]);
        }

        return redirect()->intended($intended_url);
    }
}
