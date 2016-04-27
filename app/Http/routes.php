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

//API REST
Route::group(['prefix'=>'api','middleware'=>['auth']],function(){
    //Rutas para API REST Usuarios del Sistema
    Route::resource('user','UserController',
                    ['only'=>['index','store','update','destroy','show']]);

    //Rutas para API REST de Productos
    Route::group(['prefix'=>'product'],function(){
        Route::resource('/','ProductController',
            ['only'=>['index','store','update','destroy','show']]);
        Route::get('/types','ProductController@types');
        Route::get('/type/{id}','ProductController@type_products');
    });

    //Rutas para API REST de Attribute
    Route::get('product/group_attributes/{id}','ProductController@group_attributes');

    Route::resource('attribute','AttributeController',
                    ['only'=>['index','store','update','destroy','show']]);

    //Rutas para API REST de Clientes
    Route::resource('customer','CustomerController',
                    ['only'=>['index','store','update','destroy','show']]);

    //Rutas para API REST Kardex
    Route::resource('kardex','KardexController',
                    ['only'=>['index','store','update','destroy','show']]);

    //Rutas para API REST Registro de Interes
    Route::resource('interest','InterestController',
        ['only'=>['index','store','update','destroy','show']]);

    //Rutas para API REST Orden de Pedido
    Route::resource('order','OrderController',
        ['only'=>['index','store','update','destroy','show']]);

    //Rutas para API REST Registro de alcance
    Route::group(['prefix'=>'scope'],function(){
        Route::resource('/','ScopeController',
            ['only'=>['index','store','update','show']]);
        Route::get('/types', 'ScopeController@types');
    });

    //Rutas para API REST UBIGEO
    Route::group(['prefix'=>'ubigeo'],function(){
        Route::get('/departamento','UbigeoController@departamento');
        Route::get('/provincia/{provincia}','UbigeoController@provincia');
        Route::get('/distrito/{distrito}','UbigeoController@distrito');
    });

    //Rutas para consultar tipos de operador de celular
    Route::group(['prefix'=>'operador'],function(){
        Route::get('/','OperadorController@index');
    });

    //Rutas para consultar redes sociales
    Route::group(['prefix'=>'social'],function(){
        Route::get('/','SocialController@index');
    });

    //Rutas para agregar clientes
    Route::group(['prefix'=>'customer/add'],function(){
        Route::post('/address/{address}','CustomerController@addressAdd');
        Route::post('/phone/{phone}','CustomerController@phoneAdd');
        Route::post('/social/{social}','CustomerController@socialAdd');
    });

    //Rutas para actualizar clientes
    Route::group(['prefix'=>'customer/upd'],function(){
        Route::put('/address','CustomerController@addressUpdate');
        Route::put('/phone','CustomerController@phoneUpdate');
        Route::put('/social','CustomerController@socialUpdate');
    });

    //Rutas para Socials
    Route::get('/channel', 'SocialController@index');

    //Ruta para validar valide-key
    Route::get('validate-key','HomeController@validar');
});

Route::get('/test', function(\Illuminate\Http\Request $request){
    return '=)';
});
