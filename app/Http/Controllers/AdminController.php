<?php
<<<<<<< HEAD

namespace App\Http\Controllers;
=======
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Website;
use Auth;

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
