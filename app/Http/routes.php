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
use Dashboard\User;

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
Route::get('/',['middleware'=>'web', function () {
    return view('login');
}]);

Route::group(['prefix'=>'auth','middleware' => ['web']],function(){
    Route::post('/login' , 'Auth\AuthTokenController@login');
    Route::post('/signup' , 'Auth\AuthTokenController@signup');

});

Route::group(['prefix'=>'dashboard','middleware'=>['web','auth']],function(){
    Route::get('/', function () {
        return view('vendedor');
    });

});