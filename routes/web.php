<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

// Routes for main domain
Route::group(['domain' => env('SITARIUM_ADMIN_WEBSITE')], function () {
    // Authentication Routes
    $this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
    $this->post('login', 'Auth\LoginController@login');
    $this->post('logout', 'Auth\LoginController@logout');

    // Registration Routes --> Disabled
//         $this->get('register', 'Auth\RegisterController@showRegistrationForm');
//         $this->post('register', 'Auth\RegisterController@register');

    // Password Reset Routes
    $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
    $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
    $this->post('password/reset', 'Auth\ResetPasswordController@reset');

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
    Route::post('/sitarium/login', 'Auth\LoginController@login');
    Route::post('/sitarium/logout', 'Auth\LoginController@logout');

    // Routes for websites updates
    Route::post('/fly-editor/submit', 'FlyEditorController@submit');
    Route::post('/fly-editor/image_upload', 'FlyEditorController@image_upload');

