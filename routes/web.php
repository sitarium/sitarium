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
    Route::get('/admin', 'Admin\AdminController@index');

    // Websites
    Route::get('/admin/websites/{user?}', 'Admin\WebsiteController@paginate');
    Route::get('/admin/website/{id?}', 'Admin\WebsiteController@showForm');
    Route::get('/admin/website/browse/{id?}', 'Admin\WebsiteController@browse');
    Route::get('/admin/website/fs/{id?}', 'Admin\WebsiteController@fs');
    Route::post('/admin/website', 'Admin\WebsiteController@save');
    Route::delete('/admin/website', 'Admin\WebsiteController@delete');
    Route::post('/admin/website/authorize', 'Admin\AdminController@authorizeUser');

    // Users
    Route::get('/admin/users/{website?}', 'Admin\UserController@paginate');
    Route::get('/admin/user/{id?}', 'Admin\UserController@showForm');
    Route::post('/admin/user', 'Admin\UserController@save');
    Route::delete('/admin/user', 'Admin\UserController@delete');
});

    // Route for hosted websites
    Route::get('/{page?}', 'DisplayController@show');

    // Routes for authentication
    Route::post('/sitarium/login', 'Auth\LoginController@login');
    Route::post('/sitarium/logout', 'Auth\LoginController@logout');

    // Routes for websites updates
    Route::post('/fly-editor/submit', 'FlyEditorController@submit');
    Route::post('/fly-editor/image_upload', 'FlyEditorController@image_upload');

