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
    Route::resource('product','ProductController',
                    ['only'=>['index','store','update','destroy','show']]);
    Route::resource('attribute','AttributeController',
                    ['only'=>['index','store','update','destroy','show']]);
    Route::resource('customer','CustomerController',
                    ['only'=>['index','store','update','destroy','show']]);
    Route::resource('kardex','KardexController',
                    ['only'=>['index','store','update','destroy','show']]);
    Route::group(['prefix'=>'ubigeo'],function(){
        Route::get('/departamento','UbigeoController@departamento');
        Route::get('/provincia/{provincia}','UbigeoController@provincia');
        Route::get('/distrito/{distrito}','UbigeoController@distrito');
    });
    Route::group(['prefix'=>'operador'],function(){
        Route::get('/','OperadorController@index');
    });
    Route::group(['prefix'=>'social'],function(){
        Route::get('/','SocialController@index');
    });
    Route::group(['prefix'=>'customer/add'],function(){
        Route::post('/address/{address}','CustomerController@addressAdd');
        Route::post('/phone/{phone}','CustomerController@phoneAdd');
        Route::post('/social/{social}','CustomerController@socialAdd');
    });
    Route::group(['prefix'=>'customer/upd'],function(){
        Route::put('/address','CustomerController@addressUpdate');
        Route::put('/phone','CustomerController@phoneUpdate');
        Route::put('/social','CustomerController@socialUpdate');
    });

    Route::get('validate-key','HomeController@validar');
});