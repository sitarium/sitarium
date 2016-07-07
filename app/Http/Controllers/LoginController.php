<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Website;
use Auth;
use Hash;
use Input;
use Redirect;
use Request;
use Response;
use Session;

class LoginController extends Controller
{
    public function login()
    {
        $email = trim(Input::get('email', ''));
        $password = trim(Input::get('password', ''));
        $remember_me = Input::has('remember-me') ? true : false;

        if (Auth::attempt(['email' => $email, 'password' => $password], $remember_me) === false) {
            return Response::json([
                    'code' => 1,
// 					'message' => 'Oups... raté ! Besoin d\'un coup de main pour <a href="http://vivarium.jungle-gecko.fr/login/reminder">réinitialiser votre mot de passe</a> ?'
                    'message' => 'Oups... raté ! Vous êtes sûr de votre mot de passe ?',
            ]);
        } else {
            /*
             * TODO /login/reminder feature
             */

            // User authenticated, but access to current website to be checked
            $website = Website::where([
                    'host' => Request::server('HTTP_HOST'),
            ])->firstOrFail();

            if (!Auth::user()->isAuthorizedOnWebsite($website)) {
                Auth::logout();

                return Response::json([
                    'code' => 2,
// 					'message' => 'Oups... raté ! Besoin d\'un coup de main pour <a href="http://vivarium.jungle-gecko.fr/login/reminder">réinitialiser votre mot de passe</a> ?'
                    'message' => 'Hum... Il vous manque quelques autorisations sur ce site...',
                ]);
            }

            $intended_url = Session::pull('url.intended', '');

            return Response::json([
                    'code'          => 0,
                    'message'       => 'Bienvenue ! Passons à la partie fun maintenant !',
                    'callback_vars' => ['intended_url' => $intended_url],
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();

        return Redirect::to('/');
    }

    public function insert()
    {
        $user = new User([
            'name'     => 'test',
            'email'    => 'jerome.gras@gmail.com',
            'password' => Hash::make('aaa'),
        ]);

        $user->save();
    }
}
