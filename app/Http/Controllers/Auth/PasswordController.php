<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Request;
use Response;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    private $linkRequestView = 'admin.passwords.email';

    private $resetView = 'admin.passwords.reset';

    private $redirectPath = '/admin';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEmail()
    {
        $email = '';
        if (Auth::check()) {
            $email = Auth::user()->email;
        }

        return $this->showLinkRequestForm()->with(compact('email'));
    }

    /**
     * Get the response for after the reset link has been successfully sent.
     *
     * @param string $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getSendResetLinkEmailSuccessResponse($response)
    {
        if (Request::ajax() || Request::wantsJson()) {
            return Response::json([
                'code'    => 0,
                'message' => trans($response),
            ]);
        } else {
            return redirect()->back()->with('status', trans($response));
        }
    }

    /**
     * Get the response for after the reset link could not be sent.
     *
     * @param string $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getSendResetLinkEmailFailureResponse($response)
    {
        if (Request::ajax() || Request::wantsJson()) {
            return Response::json([
                'code'    => 1,
                'message' => trans($response),
            ], 500);
        } else {
            return redirect()->back()->withErrors(['email' => trans($response)]);
        }
    }

    /**
     * Get the response for after a successful password reset.
     *
     * @param string $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResetSuccessResponse($response)
    {
        if (Request::ajax() || Request::wantsJson()) {
            return Response::json([
                'code'          => 0,
                'message'       => trans($response),
                'callback_vars' => ['redirect_url' => $this->redirectPath()],
            ]);
        } else {
            return redirect($this->redirectPath())->with('status', trans($response));
        }
    }

    /**
     * Get the response for after a failing password reset.
     *
     * @param Request $request
     * @param string  $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResetFailureResponse(\Illuminate\Http\Request $request, $response)
    {
        if (Request::ajax() || Request::wantsJson()) {
            return Response::json([
                'code'    => 1,
                'message' => trans($response),
            ], 500);
        } else {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => trans($response)]);
        }
    }
}
