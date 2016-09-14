<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Response;
use Validator;

class WebsiteFormRequest extends FormRequest
{
    /**
     * Defines the rules through a factory (required to check host unicity).
     *
     * @param mixed $factory
     * @return bool
     */
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

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Only allow admin users
        return Auth::check() && Auth::user()->admin;
    }

    /**
     * Returns a specific json response.
     *
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        /*
         * TODO: test if request was ajax
         */
        return Response::json([
                'code' => 1,
                'message' => 'Nous avons rencontré un souci :-(',
                'errors' => $errors,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
