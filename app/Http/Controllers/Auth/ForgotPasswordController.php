<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Request;
use Password;
use Response;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
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
    public function showLinkRequestForm()
    {
        $email = '';
        if (Auth::check()) {
            $email = Auth::user()->email;
        }

        return view('admin.passwords.email')->with(compact('email'));
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(\Illuminate\Http\Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email'), $this->resetNotifier()
        );

        if ($response === Password::RESET_LINK_SENT) {
            if (Request::ajax() || Request::wantsJson()) {
                return Response::json([
                    'code'    => 0,
                    'message' => trans($response),
                ]);
            } else {
                return back()->with('status', trans($response));
            }
        }

        // If an error was returned by the password broker, we will get this message
        // translated so we can notify a user of the problem. We'll redirect back
        // to where the users came from so they can attempt this process again.
        if (Request::ajax() || Request::wantsJson()) {
            return Response::json([
                'code' => 1,
                'message' => trans($response),
            ], 500);
        } else {
            return back()->withErrors(['email' => trans($response)]);
        }
    }
}
