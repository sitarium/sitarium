<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebsiteFormRequest;
use App\Models\User;
use App\Models\Website;
use Auth;
use Request;
use Response;

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
        return view('admin/dashboard')->with(['websites' => Website::all(), 'users' => User::all()]);
    }

    /**
     * Show the website form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showWebsiteForm($id = null)
    {
        $website = Website::findOrNew($id);
        
        return view('admin/website')->with(compact('website'));
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
            return Response::json(array(
                'code' => 1,
                'message' => 'Failed to save website.'
            ));
        }
        
        return Response::json(array(
            'code' => 0,
            'message' => 'Website saved.',
            'callback_vars' => [
                'id' => $website->id
            ]
        ));
    }
    
    /**
     * Delete a website
     * 
     * @return \Illuminate\Http\Response
     */
    public function deleteWebsite()
    {
        $id = Request::input('id');
        
        $website = Website::findOrFail($id);
        
        if (! $website->delete()) {
            return Response::json(array(
                'code' => 1,
                'message' => 'Failed to delete website.'
            ));
        }
        
        return Response::json(array(
            'code' => 0,
            'message' => 'Website deleted.',
            'callback_vars' => [
                'redirect_url' => url('admin')
            ]
        ));
        
    }
}
