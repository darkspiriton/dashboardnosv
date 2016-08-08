var elixir = require("laravel-elixir");
require("laravel-elixir-stylus");

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */


/*
 |
 |  Procees for CSS
 |
 */

elixir(function(mix) {
    mix.sass("app1.css" , "public/css/app.min.1.css");
    mix.sass("app2.css" , "public/css/app.min.2.css");
    mix.stylus("loginStyles.styl" , "public/css/loginStyles.min.css");
    mix.stylus("styles.styl" , "public/css/styles.min.css");
    mix.stylus("toastr.styl" , "public/css/toastr.min.css");
    mix.stylus("loginV2.styl" , "public/css/loginV2.min.css");
});

/*
 |
 |  Process from JS
 |
 */

elixir(function(mix) {
     

    mix.browserify([
        "app/controllers/auxProductStoreCtrl.js",
        "app/controllers/auxStockVENCtrl.js",
        ], "public/app/controllers/compile/almacenControllers.js");
    
    /*
     |
     |  Compile for JVE
     |
     */

    mix.browserify([
        "app/controllers/auxMovementCtrl.js",
        "app/controllers/auxMovement2_JVECtrl.js",
        "app/controllers/auxStockCtrl.js",
        "app/controllers/auxMovementOutFitCtrl.js",
        "app/controllers/auxMovementOutFit2Ctrl.js",
        "app/controllers/publicityJVECtrl.js",
        "app/controllers/auxProductJVECtrl.js",
        "app/controllers/auxIndicator7Ctrl.js",
        "app/controllers/RequestApplicationCtrl.js"
        ], "public/app/controllers/compile/coordinadorControllers.js");

    /*
     |
     |  Compile for VEN
     |
     */

    mix.browserify([
        "app/controllers/auxStockVENCtrl.js",
        ], "public/app/controllers/compile/vendedorControllers.js");

    /*
     |
     |  Compile for GOD
     |
     */

    mix.browserify([
        "app/controllers/auxStockCtrl.js",
        "app/controllers/auxAlarmCtrl.js",
        "app/controllers/auxProductCtrl.js",
        "app/controllers/auxIndicator1Ctrl.js",
        "app/controllers/auxIndicator2Ctrl.js",
        "app/controllers/auxIndicator3Ctrl.js",
        "app/controllers/auxIndicator4Ctrl.js",
        "app/controllers/auxIndicator5Ctrl.js",
        "app/controllers/auxIndicator6Ctrl.js",
        "app/controllers/auxIndicator7Ctrl.js",
        "app/controllers/usersCtrl.js",
        "app/controllers/commentsCtrl.js",
        "app/controllers/employeesCtrl.js",
        "app/controllers/payrollEntryCtrl.js",
        "app/controllers/godEmployeeAssistsCtrl.js",
        "app/controllers/indicatorPayRoleCtrl.js",
        "app/controllers/products_out.js",
        "app/controllers/q_questionnairesCtrl.js",
        "app/controllers/q_questionsCtrl.js",
        "app/controllers/q_categoriesCtrl.js",
        "app/controllers/q_AnswerIndicator.js",
        "app/controllers/q_productsCtrl.js",
        "app/controllers/q_customersCtrl.js",
        "app/controllers/outFitCtrl.js",
        "app/controllers/liquidationCtrl.js",
        "app/controllers/auxMovementCtrl.js",
        "app/controllers/auxMovement2Ctrl.js",
        "app/controllers/auxMovementOutFitCtrl.js",
        "app/controllers/auxMovementOutFit2Ctrl.js",
        "app/controllers/auxEsquemaCtrl.js",
        "app/controllers/publicityJVECtrl.js",
        "app/controllers/publicityCtrl.js",
        "app/controllers/facebookPublicityCtrl.js",
        "app/controllers/PartnerCtrl.js",
        "app/controllers/auxMovementDayCtrl.js",
        "app/controllers/auxProviderProductCtrl.js",
        "app/controllers/PartnerPanelCtrl.js",
        "app/controllers/PaymentCtrl.js",
        "app/controllers/PaymentListCtrl.js",
        "app/controllers/RequestProductCtrl.js",
        "app/controllers/RequestApplicationCtrl.js"
        ], "public/app/controllers/compile/godControllers.js");    

    /*
     |
     |  Compile for ADM
     |
     */

    mix.browserify([
        "app/controllers/auxProductCtrl.js",
        "app/controllers/auxIndicator1Ctrl.js",
        "app/controllers/auxIndicator2Ctrl.js",
        "app/controllers/auxIndicator3Ctrl.js",
        "app/controllers/auxIndicator4Ctrl.js",
        "app/controllers/auxIndicator5Ctrl.js",
        "app/controllers/auxIndicator6Ctrl.js",
        "app/controllers/usersCtrl.js",
        "app/controllers/commentsCtrl.js",
        "app/controllers/auxStockCtrl.js",
        "app/controllers/employeesCtrl.js",
        "app/controllers/payrollEntryCtrl.js",
        "app/controllers/godEmployeeAssistsCtrl.js",
        "app/controllers/indicatorPayRoleCtrl.js",
        "app/controllers/products_out.js",
        "app/controllers/auxMovementCtrl.js",
        "app/controllers/auxMovement2Ctrl.js",
        "app/controllers/q_questionnairesCtrl.js",
        "app/controllers/q_questionsCtrl.js",
        "app/controllers/q_categoriesCtrl.js",
        "app/controllers/q_AnswerIndicator.js",
        "app/controllers/q_productsCtrl.js",
        "app/controllers/q_customersCtrl.js",
        "app/controllers/RequestProductCtrl.js",
        "app/controllers/RequestApplicationCtrl.js"
        ], "public/app/controllers/compile/admControllers.js");

    /*
     |
     |  Compile for ASSOCIATED
     |
     */

    mix.browserify([
        "app/controllers/RequestProductCtrl.js"
        ], "public/app/controllers/compile/associatedControllers.js");


    /*
     |
     |  Other Compiles
     |
     */

    mix.browserify("app/app.js","public/app/app.js");
    mix.browserify("app/appLogin.js","public/app/appLogin.js");
    mix.browserify("app/controllers/loginCtrl.js","public/app/controllers/loginCtrl.js");

    /*
     |
     |  Compile for Login 
     |
     */

    mix.browserify([
        "app-usc/login.js",
        "app-usc/controllers/loginCtrl.js",
        "app-usc/services/loginService.js",
        ], "public/app-usc/compiled/loginApp.js");


    /*
     |
     |  Versions
     |
     */

    mix.version([
        //  JS
        "public/app/controllers/compile/almacenControllers.js",
        "public/app/controllers/compile/coordinadorControllers.js",
        "public/app/controllers/compile/vendedorControllers.js",
        "public/app/controllers/compile/godControllers.js",
        "public/app/controllers/compile/admControllers.js",
        "public/app/app.js",
        "public/app/appLogin.js",
        "public/app/controllers/loginCtrl.js",
        "public/app-usc/compiled/loginApp.js",
        "public/app/controllers/compile/associatedControllers.js",
        //  CSS
        "css/app.min.1.css",
        "css/app.min.2.css",
        "css/styles.min.css",
        "css/loginStyles.min.css",
        "css/toastr.min.css",
        "public/css/loginV2.min.css",
        ]);
});
