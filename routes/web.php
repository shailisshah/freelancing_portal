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


    Route::get('/admin/dashboard', 'DashboardController@index')->name('admin.dashboard');

    Route::resource('/admin/punches', 'PunchesController', array("as" => "admin"));
    Route::get('/admin/punches/{punch}/delete', ['as' => 'admin.punches.delete', 'uses' => 'PunchesController@destroy']);

    Route::resource('/admin/my-attendance-report', 'MyAttendanceReportController', array("as" => "admin"));
    Route::resource('/admin/emp-attendance-report', 'EmpAttendanceReportController', array("as" => "admin"));
});




