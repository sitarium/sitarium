<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserFormRequest;
use App\Models\User;
use App\Models\Website;
use Auth;
use Request;
use Response;
use View;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Returns a paginated list of users.
     *
     * @return \Illuminate\Http\Response
     */
    public function paginate($website = null)
    {
        $users = User::paginate(2);

        if ($website != null) {
            $users->map(function ($user, $key) use ($website) {
                $user->authorized = $user->websites->contains(function ($value, $key) use ($website) {
                    return $value->id == intval($website);
                });

                return $user;
            });
        }

        return Response::json(View::make('admin.users.page')->with(compact('users', 'website'))->render());
    }

    /**
     * Show the user form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showForm($id = null)
    {
        $user = User::findOrNew($id);

        return view('admin.users.form')->with(compact('user'));
    }

    /**
     * Save the updated or created user.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(UserFormRequest $request)
    {
        $user = User::updateOrCreate(['id' => $request->input('id')], $request->all());

        if (! $user) {
            return Response::json([
                'code' => 1,
                'message' => 'Failed to save user.',
            ]);
        }

        return Response::json([
            'code' => 0,
            'message' => 'User saved.',
            'callback_vars' => [
                'id' => $user->id,
            ],
        ]);
    }

    /**
     * Delete a user.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete()
    {
        $id = Request::input('id');

        if (intval($id) === Auth::user()->id) {
            return Response::json([
                'code' => 1,
                'message' => 'You cannot delete yourself!',
            ]);
        }

        $user = User::findOrFail($id);

        if (! $user->delete()) {
            return Response::json([
                'code' => 1,
                'message' => 'Failed to delete user.',
            ]);
        }

        return Response::json([
            'code' => 0,
            'message' => 'User deleted.',
            'callback_vars' => [
                'redirect_url' => url('admin'),
            ],
        ]);
    }
}
