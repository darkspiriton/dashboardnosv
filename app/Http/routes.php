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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Dashboard\User;

Route::get('/', function () {
    return view('login');
});


Route::post('/dashboard', function (Request $request) {
    $token = $request->input('token');
    $user = User::where('token','=', $token)->first();
    if ($user->role->abrev == 'GOD' ){
        return view('god');
    } else if ($user->role->abrev == 'ADM') {
        return view('administrator');
    } else if ($user->role->abrev == 'VEN') {
        return view('vendedor');
    } else if ($user->role->abrev == 'JVE') {
        return view('vendedor');
    }
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
    response()->json([ 'role' => 'VEN' , 'routes' => Config::get('angularJSRoutes.GOD')]);
    dd(Config::get('angularJSRoutes.GOD'));
});