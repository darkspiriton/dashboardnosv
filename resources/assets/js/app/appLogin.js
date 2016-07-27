angular.module('loginApp', ['ngResource', 'ngMessages', 'ngAnimate', 'toastr', 'ui.router', 'satellizer'])
    .config([$stateProvider, $urlRouterProvider, $authProvider,
        function($stateProvider, $urlRouterProvider, $authProvider) {

        $stateProvider
            .state('homeLogin', {
                url: '/login',
                templateUrl: 'app/partials/login.html',
                controller: 'LoginCtrl'
            });

        $urlRouterProvider.otherwise('/login');

        $authProvider.tokenName = "token";
        $authProvider.tokenPrefix = "DB_NV";

    }]);
