<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebsiteFormRequest;
use App\Http\Requests\UserFormRequest;
use App\Models\User;
use App\Models\Website;
use Auth;
use Request;
use Response;
use View;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'check.admin.password']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin/dashboard');
    }

    /**
     * Returns a paginated list of websites.
     *
     * @return \Illuminate\Http\Response
     */
    public function paginateWebsites()
    {
        return Response::json(View::make('admin.websites.page')->with(['websites' => Website::paginate(2)])->render());
    }

    /**
     * Show the website form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showWebsiteForm($id = null)
    {
        $website = Website::findOrNew($id);

        return view('admin.websites.form')->with(compact('website'));
    }

    /**
     * Save the updated or created website.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveWebsite(WebsiteFormRequest $request)
    {
        $website = Website::updateOrCreate(['id' => $request->input('id')], $request->all());

        if (! $website) {
            return Response::json([
                'code' => 1,
                'message' => 'Failed to save website.',
            ]);
        }

        return Response::json([
            'code' => 0,
            'message' => 'Website saved.',
            'callback_vars' => [
                'id' => $website->id,
            ],
        ]);
    }

    /**
     * Delete a website.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteWebsite()
    {
        $id = Request::input('id');

        $website = Website::findOrFail($id);

        if (! $website->delete()) {
            return Response::json([
                'code' => 1,
                'message' => 'Failed to delete website.',
            ]);
        }

        return Response::json([
            'code' => 0,
            'message' => 'Website deleted.',
            'callback_vars' => [
                'redirect_url' => url('admin'),
            ],
        ]);
    }

    /**
     * Returns a paginated list of users.
     *
     * @return \Illuminate\Http\Response
     */
    public function paginateUsers()
    {
        return Response::json(View::make('admin.users.page')->with(['users' => User::paginate(2)])->render());
    }

    /**
     * Show the user form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showUserForm($id = null)
    {
        $user = User::findOrNew($id);

        return view('admin.users.form')->with(compact('user'));
    }

    /**
     * Save the updated or created user.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveUser(UserFormRequest $request)
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
    public function deleteUser()
    {
        $id = Request::input('id');

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
