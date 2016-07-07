<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Website;

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
}
