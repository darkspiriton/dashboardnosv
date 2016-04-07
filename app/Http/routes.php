<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('login');
});

Route::get('/dashboard', function () {
    return view('administrator');
});


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::post('auth/login' , 'Auth\AuthTokenController@login');
Route::post('auth/signup' , 'Auth\AuthTokenController@signup');

Route::get('/test', function(){
    return response()->json([ 'role' => 'VEN' , 'routes' => Config::get('angularJSRoutes.VEN')]);
    dd(Config::get('angularJSRoutes.GOD'));
});