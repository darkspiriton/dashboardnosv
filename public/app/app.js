angular.module('App', ['ngResource', 'ngMessages', 'ngAnimate', 'toastr', 'ui.router', 'satellizer'])
    .config(function($stateProvider, $urlRouterProvider, $authProvider) {
        $stateProvider
            .state('home', {
                url: '/home',
                templateUrl: 'app/partials/home.html',
            });

        $urlRouterProvider.otherwise('/');

        $authProvider.tokenName = "token";
        $authProvider.tokenPrefix = "DB_NV";

    })
    .factory('SatellizerStorage', ['$window', '$log', 'SatellizerConfig', function($window, $log, config) {

        var store = {};

        // Check if localStorage or sessionStorage is available or enabled
        var isStorageAvailable = (function() {
            try {
                var supported = config.storageType in $window && $window[config.storageType] !== null;

                if (supported) {
                    var key = Math.random().toString(36).substring(7);
                    $window[config.storageType].setItem(key, '');
                    $window[config.storageType].removeItem(key);
                }

                return supported;
            } catch (e) {
                return false;
            }
        })();

        if (!isStorageAvailable) {
            $log.warn(config.storageType + ' is not available.');
        }

        return {
            get: function(key) {
                return isStorageAvailable ? $window[config.storageType].getItem(key) : store[key];
            },
            set: function(key, value) {
                return isStorageAvailable ? $window[config.storageType].setItem(key, value) : store[key] = value;
            },
            remove: function(key) {
                return isStorageAvailable ? $window[config.storageType].removeItem(key): delete store[key];
            }
        };

    }])
    .factory("utilFactory", function ($cookieStore) {
        return {
            getValue : function (key) {
                return $cookieStore.get(key) || false;
            },
            removeCookie : function() {
                $cookieStore.remove('username');
                $cookieStore.remove('rol');
                $cookieStore.remove('url');
                $cookieStore.remove('idempresa');
            }
        }
    })
    .controller('appController', function AppCtrl($state, $log, $scope, utilFactory) {
        if (!$auth.isAuthenticated()) {
            $scope.rol = utilFactory.getValue('rol');

            $scope.urls = {
                'GOD' : ['home','reporte_ventas','reporte_de_ventas_por_prenda'],
                'ADM' : ['home','ventas', 'kardex','registro_de_devoluciones'],
                'VEN' : ['home','verntas','registro_de_alcanse','comiciones'],
                'JVE' : ['home','ventas','clientes','registro_de_rutas','registro_de_seguimiento'],
                'MOT' : ['home','registro_de_devoluciones'],
            };


            $scope.logout = function(){
                utilFactory.removeCookie();
                window.location.href = "index.html";
            };

            $scope.$on('$stateChangeSuccess', function (event, toState) {

                //si no los tiene que los redirija a /home
                if($scope.urls[$scope.rol].indexOf(toState.name) === -1){
                    event.preventDefault();
                    $state.go('/home');
                }

                if (angular.isDefined(toState.data.pageTitle)) {
                    $scope.pageTitle = toState.data.pageTitle + ' | NosVenden';
                }
            });
        }else{
            $window.location.href = '/';
        }
    });
