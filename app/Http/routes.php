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

    Route::post('/login' ,'Auth\AuthTokenController@login');
    Route::post('/signup' ,'Auth\AuthTokenController@signup');

});

Route::group(['prefix'=>'dashboard','middleware'=>['auth']],function(){
    Route::post('/','Auth\AuthTokenController@dashboard');
});

//API REST CRUD EMPREADOS
Route::group(['prefix'=>'api','middleware'=>['auth']],function(){
    
    Route::resource('user','UserController',
                    ['only'=>['index','store','update','destroy','show']]);
    Route::resource('products','ProductController',
                    ['only'=>['index','store','update','destroy','show']]);

});