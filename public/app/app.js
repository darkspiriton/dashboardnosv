angular.module('App', ['ngResource','ngMessages','ngSanitize','ngAnimate','toastr','ui.router','satellizer'])
    .config(function($stateProvider, $urlRouterProvider, $authProvider) {
        $authProvider.tokenName = "token";
        $authProvider.tokenPrefix = "DB_NV";

        $stateProvider
            .state('Home', {
                url: '/home',
                templateUrl: 'app/partials/efecto.html',
                controller: 'homeCtrl'
            });

        $urlRouterProvider.otherwise('/home');

    })
    .directive('fileModel', function() {
        return {
            controller: function($parse, $element, $attrs, $scope){
                var exp = $parse($attrs.fileModel);
                $element.on('change', function(){
                    exp.assign($scope, this.files);
                    $scope.$apply();
                });
            }
        }
    })
    .factory('color', function(){
        var colors = ['#F44336','#03A9F4','#8BC34A','#009688','#E91E63','#FF9800','#00BCD4','#FFEB3B','#9C27B0','#673AB7','#3F51B5','#4CAF50'];
        get = function(i){
            i || (i = 0);
            i = (i > 11)?i-12:i;
            return colors[i];
        };

        return {
            get : get
        };
    })
    .factory('chart', ['color', function(color){
        dataChart = function( data , config, callback){
            var dataChart = [];
            for(i in data){
                var temp = {};
                for(y in config){
                    temp[y] = data[i][config[y]];
                }
                temp.color = color.get(i);
                dataChart.push(temp);
            }
            callback(dataChart);
        };
        chart = function( data, config ){
            dataChart( data , config, function(pieData){
                $.plot('#pie-chart', pieData, {
                    series: {
                        pie: {
                            innerRadius: 0.5,
                            show: true,
                            stroke: {
                                width: 4
                            }
                        }
                    },
                    legend: {
                        container: '.flc-pie',
                        backgroundOpacity: 0.7,
                        noColumns: 0,
                        backgroundColor: "white",
                        lineWidth: 0
                    },
                    grid: {
                        hoverable: true,
                        clickable: true
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                        shifts: {
                            x: 20,
                            y: -10
                        },
                        defaultTheme: false,
                        cssClass: 'flot-tooltip'
                    }
                });
            });
        };

        return {
            draw : chart
        };
    }])
    .factory('toformData',  function(){
        dataFile = function( data ){
            if ( undefined === data ) return data;
            var formData = new FormData();
            angular.forEach(data, function(value, key){
                if (value instanceof FileList){
                    if (value.length === 1)
                        formData.append(key , value[0]);
                    else {
                        angular.forEach(value , function(file, index){
                            formData.append(key + '_' + index , file);
                        });
                    }
                } else if( value instanceof Array){
                    formData.append(key, angular.toJson(value));
                } else {
                    formData.append(key, value);
                }
            });
            return formData;
        }

        return {
            dataFile : dataFile
        }
    })
    .factory('petition', function($http, $location){
        var baseUrl =  function ( URL ) {
            var prot = $location.protocol();
            var host = $location.host();
            return prot + '://' + host + '/' + URL;
        };

        var promise;

        return {

            get: function( URL, data ) {
                data || (data = {});
                promise = $http.get( baseUrl(URL), data ).then(function(response){
                    return response.data;
                });

                return promise;
            },
            post: function ( URL , data, config) {
                data || (data = {});
                config || (config = {});
                promise = $http.post( baseUrl(URL), data, config ).then(function(response){
                    return response.data;
                });

                return promise;
            },
            put: function( URL, data ) {
                data || (data = {});
                promise = $http.put( baseUrl(URL), data ).then(function(response){
                    return response.data;
                });

                return promise;
            },
            delete: function ( URL , data) {
                data || (data = {});
                promise = $http.delete( baseUrl(URL), data ).then(function(response){
                    return response.data;
                });

                return promise;
            },
            custom :  function ( config ) {
                promise = $http(config).then(function(response){
                    return response.data;
                });
                return promise;
            }
        };
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
            }
        };
    }])
    .factory('util', function($location){
        return {
            liPage : function( name ){
                $('li.active').removeClass('active');
                $('li#' + name).addClass('active');
            },
            muestraformulario : function (){
                $('#formulariohide').fadeIn('fast');
                $('html, body').stop().animate({
                    scrollTop: $('#formulariohide').offset().top-100
                }, 1000);
            },
            ocultaformulario : function (){
                $('#formulariohide').fadeOut('fast');
                $("body").animate({
                    scrollTop: 0
                }, 1000);
            },
            modal : function ( id ) {
                id || (id = 'Modal');
                $('#'+ id).modal('show');
            },
            modalClose : function ( id ) {
                id || (id = 'Modal');
                $('#'+ id).modal('hide');
            },
            baseUrl : function ( URL ) {
                var prot = $location.protocol();
                var host = $location.host();
                return prot + '://' + host + '/' + URL;
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
