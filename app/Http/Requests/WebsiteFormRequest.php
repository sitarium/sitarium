<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Response;
use Validator;

class WebsiteFormRequest extends FormRequest
{
    public function validator($factory)
    {
        return $factory->make(
            $this->all(), [
                'name' => 'required',
                'host' => 'required|unique:websites,host,'.$this->input('id'),
                'email' => 'required|email',
                'active' => 'required',
            ], $this->messages(), $this->attributes()
        );
    }

    public function authorize()
    {
        // Only allow admin users
        return Auth::check() && Auth::user()->admin;
    }

    public function response(array $errors)
    {
        return Response::json([
                'code' => 1,
                'message' => 'Nous avons rencontré un souci :-(',
                'errors' => $errors,
        ]);
    }
}