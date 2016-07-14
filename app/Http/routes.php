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


/**
 *  Nuevas rutas -- middleware se ubican en sus controladores
 */

Route::get('/',['middleware'=>'web', function () {
    return view('login');
}]);

Route::group(['prefix'=>'auth','middleware' => ['web']],function(){
    Route::post('/login' ,'Auth\AuthTokenController@login');
    Route::post('/signup' ,'Auth\AuthTokenController@signup');
});

Route::group(['prefix'=>'dashboard'],function(){
    Route::post('/','Auth\AuthTokenController@dashboard');
    Route::get('/','Auth\AuthTokenController@getDashboard');
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
        
        // Route::get('/get/products/provider/{id}','AuxProductController@productProvider');
        // Route::get('/get/products/provider/month/{id}','AuxProductController@productProviderMonth');
        // Route::get('/get/products/provider/date/{id}','AuxProductController@productProviderDate');

        // Route::get('/get/products/provider/sale/{id}','AuxProductController@productProviderTotalMonth');
        // Route::get('/get/products/provider/sale/now/{id}','AuxProductController@productProviderTotalMonthNow');
    });

    /**
     * MOVEMENTS
     */
    Route::resource('auxmovement','AuxMovementController',
        ['only'=>['index','store','update','show','destroy']]);
    Route::post('auxmovement/out', 'AuxMovementController@product_out');
    Route::post('auxmovement/set/sale','AuxMovementController@sale');


    Route::group(['prefix'=>'auxmovement/'],function(){
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
});

/**
 *  TEST
 *  @return Collection test
 */

Route::get('/test', function(\Illuminate\Http\Request $request){

    return "=)";

});

