angular.module('App', [
                        'ngResource',
                        'ngMessages',
                        'ngSanitize',
                        'ngAnimate',
                        'toastr',
                        'ui.router',
                        'satellizer',
                       ])
    .config(function($stateProvider, $urlRouterProvider, $authProvider, $httpProvider) {
        $authProvider.tokenName = "token";
        $authProvider.tokenPrefix = "DB_NV";

        //$httpProvider.defaults.useXDomain = true;
        //delete $httpProvider.defaults.headers.common['X-Requested-With'];

        $stateProvider
            .state('Home', {
                url: '/home',
                templateUrl: 'app/partials/efecto.html',
                controller: 'homeCtrl'
            })
            .state('Usuarios', {
                url: '/Adminitracion-de-usuarios',
                templateUrl: 'app/partials/users.html',
                controller : 'usersCtrl'
            })
            .state('Comentarios', {
                url: '/Adminitracion-de-comentarios',
                templateUrl: 'app/partials/comments.html',
                controller : 'commentsCtrl'
            });

        $urlRouterProvider.otherwise('/home');

    })
    .factory('method', function($http, $location, $httpParamSerializerJQLike){
        var baseUrl =  function ( URL ) {
            var prot = $location.protocol();
            var host = $location.host();
            return prot + '://' + host + ':/' + URL;
        }
        var metService = {
            get: function( URL ) {
                var promise = $http.get( baseUrl( URL ) ).then(function (response) {
                    return response.data;
                });
                return promise;
            },
            post: function ( URL , postData) {
                postData || (postData = {});
                var promise = 	$http({
                    method: 'POST',
                    url: baseUrl( URL ),
                    data: $httpParamSerializerJQLike( postData ),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (response) {
                    return response.data;
                });
                return promise;
            },
            postFD :  function ( URL , postData) {
                postData || (postData = {});
                var promise = 	$http({
                    method: 'POST',
                    url: baseUrl( URL ),
                    data: postData,
                    headers: {'Content-Type': undefined}
                }).then(function (response) {
                    return response.data;
                });
                return promise;
            }
        };
        return metService;
    })
    .factory('storage', ['$window', '$log', '$auth', function($window, $log , $auth) {
        var storageType = 'localStorage';
        var roleName = 'roleName';
        var userName = 'fullName';
        var routeName = 'routesList';

        return {
            get: function(key) {
                return $window[storageType].getItem(key);
            },
            set: function(key, value) {
                return $window[storageType].setItem(key, value);
            },
            remove: function(key) {
                return $window[storageType].removeItem(key);
            },
            removeStorage : function() {
                $auth.removeToken();
                $window[storageType].removeItem(roleName);
                $window[storageType].removeItem(userName);
                $window[storageType].removeItem(routeName);
                return;
            }
        };

    }])
    .factory('util', function(){
        return {
            liPage : function( name ){
                $('li.active').removeClass('active');
                $('li#' + name).addClass('active');
            },
            muestraformulario : function (){
                $('#formulariohide').fadeIn(500);
                $('html, body').stop().animate({
                    scrollTop: $('#formulariohide').offset().top-100
                }, 1000);
            },
            ocultaformulario : function (){
                $('#formulariohide').fadeOut(500);
                $("body").animate({
                    scrollTop: 0
                }, 1000);
            }
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
            var urls = list.split(",");
            var role = storage.get('roleName');
            var name = storage.get('fullName');

            if (!urls || !role || !name)$scope.logout();

            $scope.userInfo = {
                                urls : urls,
                                role : role,
                                name : name
                              };
            $log.log($scope.userInfo);

            $scope.$on('$stateChangeSuccess', function (event, toState) {

                // test routes
                //$log.log([$scope.userInfo.urls,toState.url,$scope.userInfo.urls.indexOf(toState.url)]);

                //if($scope.userInfo.urls.indexOf(toState.url) === -1){
                //    event.preventDefault();
                //    $state.go('Home');
                //}

                if (angular.isDefined(toState.name)) {
                    $scope.pageTitle = toState.name + ' | NosVenden';
                }
            });
        }else{
            $window.location.href = '/';
        }
    });
