angular.module('loginApp', ['ngResource', 'ngMessages', 'ngAnimate', 'toastr', 'ui.router', 'satellizer'])
    .config(function($stateProvider, $urlRouterProvider, $authProvider) {
        $stateProvider
            .state('homeLogin', {
                url: '/login',
                templateUrl: 'app/partials/login.html',
                controller: 'LoginCtrl',
                resolve: {
                    skipIfLoggedIn: skipIfLoggedIn
                }
            });

        $urlRouterProvider.otherwise('/login');

        $authProvider.loginUrl = "http://dashboard.app:8000/auth/login";
        $authProvider.signupUrl = "http://dashboard.app:8000/auth/signup";
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
