angular.module('loginApp', ['ngResource', 'ngMessages', 'ngAnimate', 'toastr', 'ui.router', 'satellizer'])
    .config(function($stateProvider, $urlRouterProvider, $authProvider) {
        var url = 'luisdev.me/';
        $stateProvider
            .state('homeLogin', {
                url: '/login',
                templateUrl: url+'app/partials/login.html',
                controller: 'LoginCtrl',
                //resolve: {
                //    skipIfLoggedIn: skipIfLoggedIn
                //}
            });

        $urlRouterProvider.otherwise('/login');

        $authProvider.tokenName = "token";
        $authProvider.tokenPrefix = "DB_NV";

        function skipIfLoggedIn($q, $auth) {
            var deferred = $q.defer();
            if ($auth.isAuthenticated()) {
                $location.path('/dashborad');
            } else {
                deferred.resolve();
            }
            return deferred.promise;
        }

    });
