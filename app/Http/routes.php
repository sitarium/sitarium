<?php


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Routes for main domain
Route::group(['domain' => env('SITARIUM_ADMIN_WEBSITE')], function () {
    // Authentication Routes
    $this->get('login', 'Auth\AuthController@showLoginForm');
    $this->post('login', 'Auth\AuthController@login');
    $this->get('logout', 'Auth\AuthController@logout');

    // Registration Routes --> Disabled
//     $this->get('register', 'Auth\AuthController@showRegistrationForm');
//     $this->post('register', 'Auth\AuthController@register');

    // Password Reset Routes
    $this->get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
    $this->post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
    $this->post('password/reset', 'Auth\PasswordController@reset');

    // Main page
    Route::get('/', 'HomeController@index');

    // Admin page
    Route::get('/admin', 'AdminController@index');
});

// Route for hosted websites
Route::get('/{page?}', 'DisplayController@show');

// Routes for authentication
<<<<<<< HEAD
// Route::post('/sitarium/login', 'LoginController@login');
// Route::get('/sitarium/logout', 'LoginController@logout');
Route::post('/sitarium/login', 'Auth\AuthController@login');
Route::get('/sitarium/logout', 'Auth\AuthController@logout');
=======
Route::post('/sitarium/login', 'LoginController@login');
Route::get('/sitarium/logout', 'LoginController@logout');
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy







// Route::get('/', function () {
//     return view('welcome');
// });

// Route::auth();

// Route::get('/home', 'HomeController@index');
