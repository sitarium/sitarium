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

    // Websites
    Route::get('/admin/websites/{user?}', 'AdminController@paginateWebsites');
    Route::get('/admin/website/{id?}', 'AdminController@showWebsiteForm');
    Route::post('/admin/website', 'AdminController@saveWebsite');
    Route::delete('/admin/website', 'AdminController@deleteWebsite');

    // Users
    Route::get('/admin/users/{website?}', 'AdminController@paginateUsers');
    Route::get('/admin/user/{id?}', 'AdminController@showUserForm');
    Route::post('/admin/user', 'AdminController@saveUser');
    Route::delete('/admin/user', 'AdminController@deleteUser');

    // Authorizations
    Route::post('/admin/authorize', 'AdminController@authorizeUserWebsite');
});

// Route for hosted websites
Route::get('/{page?}', 'DisplayController@show');

// Routes for authentication
Route::post('/sitarium/login', 'Auth\AuthController@login');
Route::get('/sitarium/logout', 'Auth\AuthController@logout');

// Routes for websites updates
Route::post('/fly-editor/submit', 'FlyEditorController@submit');
Route::post('/fly-editor/image_upload', 'FlyEditorController@image_upload');







// Route::get('/', function () {
//     return view('welcome');
// });

// Route::auth();

// Route::get('/home', 'HomeController@index');
