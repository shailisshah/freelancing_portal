<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */


Route::namespace('Admin')->group(function () {
    // Controllers Within The "App\Http\Controllers\Admin" Namespace
    //Auth::routes();
    // Authentication Routes...
    Route::any('/', 'AuthController@login')->name('admin.login');
    Route::any('admin', 'AuthController@login')->name('admin.login');

    // Registration Routes...
    Route::any('admin/register', 'AuthController@register')->name('admin.register');

    Route::any('/admin/logout', 'AuthController@logout')->name('admin.logout');

    // Password Reset Routes...
    Route::any('admin/forgot-password', 'PasswordController@sendPasswordResetToken')->name('admin.password.request');
    Route::any('admin/reset-password/{token}', 'PasswordController@resetPassword')->name('admin.password.reset');

    // Dashboard Routes...
    Route::get('/admin/dashboard', 'DashboardController@index')->name('admin.dashboard');

    // Post Projects Routes For Client...
    Route::resource('/admin/post-projects', 'PostProjectsController', array("as" => "admin"));
    Route::get('/admin/post-projects/{id}/delete', ['as' => 'admin.post-projects.delete', 'uses' => 'PostProjectsController@destroy']);

    // Projects Routes For Designer...
    Route::resource('/admin/projects', 'ProjectsController', array("as" => "admin"));

    // Projects Submission Routes For Designer and Client...
    Route::resource('/admin/projects-submission', 'ProjectsSubmissionController', array("as" => "admin"));

    // Login using google Routes...
    Route::any('admin/login/google', 'AuthController@redirectToGoogle')->name('admin.login.google');
    Route::any('admin/login/google/callback', 'AuthController@handleGoogleCallback')->name('admin.login.google.callback');

    // Login using facebook Routes...
    Route::any('admin/login/facebook', 'AuthController@redirectToFacebook')->name('admin.login.facebook');
    Route::any('admin/login/facebook/{provider}/callback', 'AuthController@handleFacebookCallback')->name('admin.login.facebook.callback');
});




