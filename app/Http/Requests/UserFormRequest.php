<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Response;
use Validator;

class UserFormRequest extends FormRequest
{
    public function validator($factory)
    {
        return $factory->make(
            $this->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.$this->input('id'),
                'admin' => 'required',
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
