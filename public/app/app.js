angular.module('App', ['ngResource', 'ngMessages', 'ngSanitize', 'ngAnimate', 'toastr', 'ui.router', 'satellizer'])
    .config(function($stateProvider, $urlRouterProvider, $authProvider, $interpolateProvider) {

        $stateProvider
            .state('home', {
                url: '/home',
                templateUrl: 'app/partials/efecto.html',
                controller: 'homeCtrl'
            }).state('other', {
                url: '/registro_de_alcanse',
                templateUrl: 'app/partials/dashHome.html',
            });

        $urlRouterProvider.otherwise('/home');

        $authProvider.tokenName = "token";
        $authProvider.tokenPrefix = "DB_NV";

    })
    .factory('storage', ['$window', '$log', '$auth', function($window, $log , $auth) {
        var storageType = 'localStorage';
        var roleName = 'levelRole';
        var store = {};

        // Check if localStorage or sessionStorage is available or enabled
        var isStorageAvailable = (function() {
            try {
                var supported = storageType in $window && $window[storageType] !== null;

                if (supported) {
                    var key = Math.random().toString(36).substring(7);
                    $window[storageType].setItem(key, '');
                    $window[storageType].removeItem(key);
                }

                return supported;
            } catch (e) {
                return false;
            }
        })();

        if (!isStorageAvailable) {
            $log.warn(storageType + ' is not available.');
        }

        return {
            get: function(key) {
                return isStorageAvailable ? $window[storageType].getItem(key) : store[key];
            },
            set: function(key, value) {
                return isStorageAvailable ? $window[storageType].setItem(key, value) : store[key] = value;
            },
            remove: function(key) {
                return isStorageAvailable ? $window[storageType].removeItem(key): delete store[key];
            },
            removeStorage : function() {
                if(isStorageAvailable){
                    $auth.removeToken();
                    $window[storageType].removeItem(roleName);
                    return;
                }
                return delete store[''];
            }
        };

    }])
    .factory('logout', function(storage, $window){
        logout = function(){
            storage.removeStorage();
            $window.location.href = "/";
            console.log('l');
        };

        return {
            logout: logout
        }
    })
    .controller('appCtrl', function AppCtrl($state, $log, $scope, $window, $auth, storage) {
        $scope.pageTitle = 'Home';

        if ($auth.isAuthenticated()) {
            $scope.logout = function(){
                storage.removeStorage();
                $window.location.href = "/";
            };

            var list = storage.get('routesList');
            if (!list)$scope.logout();
            $scope.urls = list.split(",");

            $scope.$on('$stateChangeSuccess', function (event, toState) {

                //Pruebas rutas
                $log.log([$scope.urls,toState.url,$scope.urls.indexOf(toState.url)]);

                //si no los tiene que los redirija a /home
                if($scope.urls.indexOf(toState.url) === -1){
                    event.preventDefault();
                    $state.go('home');
                }

                if (angular.isDefined(toState.name)) {
                    $scope.pageTitle = toState.name + ' | NosVenden';
                }
            });
        }else{
            $window.location.href = '/';
        }
    });
