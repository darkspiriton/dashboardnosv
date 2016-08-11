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

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: accept, content-type, x-xsrf-token, x-csrf-token, authorization');
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
header("Content-Type: application/json");

/**
 *  Nuevas rutas -- middleware se ubican en sus controladores
 */

Route::get('/',['middleware'=>'web', function () {
    return view('login');
}]);

Route::get('/asociados',['middleware'=>'web', function () {
    return view('login_usc');
}]);

Route::group(['prefix'=>'auth','middleware' => ['web']],function(){
    Route::post('/login' ,'Auth\AuthTokenController@login');
    Route::post('/signup' ,'Auth\AuthTokenController@signup');
    Route::post('/signup/usc' ,'Auth\AuthTokenController@signup_usc');
});

Route::group(['prefix'=>'dashboard'],function(){
    Route::post('/','Auth\AuthTokenController@dashboard');
    Route::get('/','Auth\AuthTokenController@getDashboard');
});

Route::group(['prefix'=>'asociados/dashboard'],function(){
    Route::get('/','Auth\AuthTokenController@dashboard_associated');
});

Route::group(['prefix' => 'api'], function(){

    /**
     *  PAYROLL - EMPLOYEES
     */
    Route::resource('employee','EmployeeController',
        ['only'=>['index','store','update','show']]);

    Route::group(['prefix'=>'employee'],function(){
        Route::get('/get/area','EmployeeController@area');
        Route::get('/get/day','EmployeeController@day');
        Route::get('/get/indicator','EmployeeController@minuto');
        Route::get('/get/roles', 'EmployeeController@getRole');
    });

    Route::resource('assist','AssistController',
        ['only'=>['index','store','update','show']]);

    Route::group(['prefix' => 'payroll'], function(){
        Route::get('employee/assists', 'ReportPayRollController@get_assists_for_month');
        Route::get('employee/assists/day', 'ReportPayRollController@get_assists_by_day');
        Route::get('/', 'ReportPayRollController@get_payroll');
        Route::get('employee/assists/by/god', 'ReportPayRollController@get_assists_for_month_by_god');
    });

    /**
     * USERS
     */
    Route::resource('user','UserController',
        ['only'=>['index','store','update','destroy','show']]);

    Route::get('user/get/roles','UserController@roles');
    Route::get('user/get/sellers','UserController@sellers');

    /**
     * VALIDATE KEY AUTH
     */
    Route::get('validate-key','HomeController@validar');

    /**
     * AUX PRODUCTS - TYPE PRODUCT
     */
    Route::resource('auxproduct','AuxProductController',
        ['only'=>['index','store','update','show','destroy']]);

    Route::resource('auxproduct/get/type','TypeAuxProductController',
        ['only'=>['index','store','update','show']]);

    Route::group(['prefix'=>'auxproduct/'],function(){
        Route::post('/set/movement','AuxProductController@addressUpdate');
        Route::post('/set/color','AuxProductController@setColor');
        Route::post('/set/provider','AuxProductController@setProvider');
        Route::get('/get/code','AuxProductController@getCod');
        
        Route::get('/get/provider/id','AuxProductController@getIdProvider');

        Route::get('/get/cantPro','AuxProductController@cantPro');
        Route::get('/get/stockProd','AuxProductController@stockProd');
        Route::get('/get/stockProd/type/{id}','AuxProductController@stockProdType');
        Route::get('/get/stockIni','AuxProductController@stockIni');
        Route::get('/get/prodSize','AuxProductController@prodSize');
        Route::get('/get/prodColor','AuxProductController@prodColor');

        Route::get('/get/prodOutProvider','AuxProductController@prodOutProvider');

        Route::get('/get/alarm','AuxProductController@alarm');

        Route::get('/get/report','AuxProductController@listProduct');

        Route::get('/get/uniques','AuxProductController@UniqueProduct');
        Route::get('/get/uniques/{name}/codes','AuxProductController@CodesLists');
        
        Route::get('get/movements/{id}','AuxProductController@movements_for_product');
        Route::put('reserve/{id}','AuxProductController@product_reserve');
        Route::get('observe/{id}','AuxProductController@product_observe_status');
        Route::put('observe/update/{id}','AuxProductController@product_observe_update');
        Route::get('observe/detail/{id}','AuxProductController@product_observe_detail');
    });

    /**
     * AUX PRODUCTS - FILTERS
     */

    Route::group(['prefix'=>'auxproduct/filter'],function(){
        Route::get('get/types','auxProductFiltersController@TypeProductList');
        Route::get('get/providers','auxProductFiltersController@ProviderList');
        Route::get('get/products','auxProductFiltersController@ProductList');
        Route::get('get/search','auxProductFiltersController@FilterForAll');
        Route::post('get/search/download','auxProductFiltersController@FilterForAllDownload');
        Route::get('get/search/stock','auxProductFiltersController@FilterStockForAllByVEN');
    });

    /**
     * MOVEMENTS
     */
    Route::resource('auxmovement','AuxMovementController',
        ['only'=>['index','store','update','show','destroy']]);
    Route::post('auxmovement/out', 'AuxMovementController@product_out');
    Route::post('auxmovement/set/sale','AuxMovementController@sale');


    Route::group(['prefix'=>'auxmovement/'],function(){
        Route::put('/get/movement/discount/{id}','AuxMovementController@discountUpdate');
        Route::get('/get/movement','AuxMovementController@movementPending');
        Route::get('/get/movementDay','AuxMovementController@movementDay');
        Route::get('/get/movementDays','AuxMovementController@movementDays');
        Route::get('/get/movementDay/get/','AuxMovementController@movementOtherDay');
        Route::post('/get/movementDays/download','AuxMovementController@movementDaysDownload');
        Route::get('/get/movement/day','AuxMovementController@move_day');
//        Route::get('/get/movement/day/today','AuxMovementController@move_day_today');
        Route::get('/get/movement/outfit/day/','AuxMovementController@move_day_outfit');
        Route::get('/get/codes','AuxMovementController@get_cod_prod');
        Route::get('/get/movementDay/consolidado','AuxMovementController@consolidado');
        Route::get('/get/movementDay/consolidadoOut','AuxMovementController@consolidadoOut');

        Route::get('/get/movementDay/provider','AuxMovementController@providertest');
        Route::get('/get/dispatch','AuxMovementController@getDispatchForDate');
        Route::post('/get/dispatch/download','AuxMovementController@dispatchForDateDownload');
    });

    /**
     * PROVIDERS - SIZE -COLORS
     */
    Route::get('auxproviders', 'AuxProductController@getProviders');
    Route::get('sizes', 'AuxProductController@getSizes');
    Route::get('colors', 'AuxProductController@getColors');

    /**
     * QUESTIONNAIRES
     */

    Route::resource('question', 'QuestionController',
        ['only' => ['index', 'store', 'show']]);
    Route::get('question/{id}/options','QuestionController@OptionsForQuestion');
    
    Route::resource('q_category', 'q_CategoryController',
        ['only' => ['index', 'store', 'show', 'update']]);

    Route::resource('questionnaire', 'QuestionnairesController',
        ['only' => ['index', 'store', 'show', 'edit', 'update']]);
    Route::get('questionnaire/category/{id}','QuestionnairesController@QuestionnaireForCategory');

    Route::resource('answer/product', 'q_ProductController',
        ['only' => ['index', 'store', 'show']]);

    Route::resource('answer/customer', 'q_AnswerController',
        ['only' => ['index', 'store']]);
    Route::get('answer/customer/{id}/{qq}', ['as' => 'show','uses' => 'q_AnswerController@show']);
    Route::get('answer/customer/search/tag/{string}','q_AnswerController@search');

    Route::post('indicator/questionnaire/for/products', 'q_IndicatorController@showProducts');
    Route::post('indicator/questionnaire/for/customers', 'q_IndicatorController@showCustomers');

    /**
     * OUTFIT
     */

    Route::resource('outfit','OutFitController',['only' => ['index','store','show','destroy']]);
    Route::get('outfit/get/actives','OutFitController@actives');
    Route::get('outfit/get/products/{name}','OutFitController@codes_by_product');

    /**
     * OUTFIT MOVEMENTS
     */

    Route::resource('auxmovements-outfit','AuxMovementOutFitController',['only' => ['index','show','store','update']]);

    /**
     * LIQUIDATION
     */

    Route::resource('liquidation','liquidationController',['only' => ['index','store','destroy']]);

    /**
     * PUBLICITIES - JVE
     */

    Route::resource('/sales/publicity','PublicityJVEController',['only' => ['index','store','destroy']]);

    /**
     * SOCIALS
     */

    Route::resource('/socials','AuxSocialController',['only' => ['index','store','update']]);

    //Rutas de creacion de producto y cliente
    Route::resource('auxqcustomer','AuxQCustomer',['only'=>['index','store','update','show','destroy']]);
    Route::resource('auxqproduct','AuxQProduct',['only'=>['index','store','update','show','destroy']]);

    Route::group(['prefix'=>'auxqproduct'],function(){
       Route::get('mostrar/{id}','AuxQProduct@mostrar');
    });

    /**
     * PUBLICITY
     */    
    Route::resource('publicity','PublicityController',
        ['only'=>['index','store','update','show']]);
    Route::post('publicity/{id}/upload','PublicityController@upload_image');
    Route::get('publicity/get/facebook','PublicityController@ByFacebook');

    /**
     * PAYMENTS PROVIDERS
     */    
    Route::resource('payment','PayProviderController',
        ['only' => ['index','store','update','destroy']]);

    /**
     * REQUESTS PRODUCTS
     */
    Route::resource('requestproduct','RequestProductController',
        ['only' => ['index','store','show','update','destroy']]);

    Route::group(['prefix' => 'requestproduct' ], function(){
        Route::get('/status/get','RequestProductController@status');
        Route::get('/user/get/{id}','RequestProductController@getUser');
    });

    Route::group(['prefix'=>'payment'],function(){
        Route::get('get','PayProviderController@getPayment');
        Route::get('bank/get','PayProviderController@getBank');
        Route::get('typeD/get','PayProviderController@getTypeD');
        Route::get('typeP/get','PayProviderController@getTypeP');
        Route::post('bank/set','PayProviderController@setBank');
        Route::post('typeD/set','PayProviderController@setTypeD');
        Route::post('typeP/set','PayProviderController@setTypeP');
    });
    
    Route::group(['prefix'=>'publicity'],function(){
        Route::get('relation/{id}','PublicityController@relation');
        Route::get('relation/indicator/get','PublicityController@indicator');
        Route::get('relation/esquema/get','PublicityController@esquema');
        Route::get('relation/esquemadate/get','PublicityController@esquemaDate');
    });

    /**
     * PARTNER
     */

    Route::get('partner/get/top/sales','PartnerController@TopSales');
    Route::get('partner/get/top/less-sold','PartnerController@TopLessSold');
    Route::get('partner/get/products/sales','PartnerController@ProductStatusForSales');

    Route::get('partner/get/products/movements','PartnerController@movementsGet');

    Route::get('partner/get/products/provider/sale','PartnerController@saleMonth');

    Route::get('partner/get/payments','PartnerController@infoPayment');

    /**
     * Associate
     */

    Route::resource("associate","RequestApplicationController", ["only" => ["index","store","show","update"]]);
    Route::resource("associate/get/status","RequestApplicationController@status");

    /**
     * PrestaShop
     */
    Route::resource('prestashop','PrestaShopController',
        ["only" => ["index","store","show","update","destroy"]]);
    Route::get('prestashop/products/{id}','PrestaShopContro@detailProduct');
    Route::get('prestashop/get/status','PrestaShopController@status');


});

/**
 *  TEST
 *  @return Collection test
 */

Route::get('/test', function(\Illuminate\Http\Request $request){
    return request("name","=D");
});

