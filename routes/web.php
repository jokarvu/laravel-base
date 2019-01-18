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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api', 'middleware' => 'api.auth'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login')->name('login');
        Route::get('admin', 'AuthController@isAdmin');
        Route::get('current', 'AuthController@current');
        Route::get('logout', 'AuthController@logout');
    });
    Route::resource('permission', 'PermissionController');
    Route::resource('role', 'RoleController');
    Route::resource('user', 'UserController');
    Route::resource('category', 'CategoryController');
});

Route::get('/login', function () {
    return view('admin');
});

Route::get('/admin/{vue?}', function () {
    return view('admin');
})->where('vue', '[\/\.\w\-]+');