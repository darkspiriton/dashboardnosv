angular.module('App', ['ngResource', 'ngMessages', 'ngSanitize', 'ngAnimate', 'toastr', 'ui.router', 'satellizer','angular-fb'])
    .config(["$stateProvider", "$urlRouterProvider", "$authProvider", "$fbProvider", "$locationProvider",
        function ($stateProvider, $urlRouterProvider, $authProvider, $fbProvider, $locationProvider) {
        $authProvider.tokenName = "token";
        $authProvider.tokenPrefix = "DB_NV";

        $fbProvider.appId = 1148473048525783;
        $fbProvider.extendPermissions = 'publish_actions,,user_photos';

        $stateProvider
            .state('Home', {
                url: '/home',
                templateUrl: 'http://' + location.hostname + '/app/partials/efecto.html',
                controller: 'homeCtrl'
            });

        $urlRouterProvider.otherwise('/home');
        // $locationProvider.html5Mode(true);

    }]).directive("selectpicker", ["$timeout", function($timeout){
        return {
            link: function(scope, element, attrs, ctrl){
                var $el = $(element);

                $timeout(function () {
                    $(element).selectpicker();
                }, 3000);
            }
        };
    }])
    .directive('fileModel', function () {
        return {
            controller: function ($parse, $element, $attrs, $scope) {
                var exp = $parse($attrs.fileModel);
                $element.on('change', function () {
                    exp.assign($scope, this.files);
                    $scope.$apply();
                });
            }
        };
    })
    .factory('color', function () {
        var colors = ['#F44336', '#03A9F4', '#8BC34A', '#009688', '#E91E63', '#FF9800', '#00BCD4', '#FFEB3B', '#9C27B0', '#673AB7', '#3F51B5', '#4CAF50'];
        var get = function (i) {
            i = i || 0;
            i = (i > 11) ? i - 12 : i;
            return colors[i];
        };

        return {
            get: get
        };
    })
    .factory('chart', ['color', function (color) {
        var dataChart = function (data, config, callback) {
            var dataChart = [];
            for (var i in data) {
                var temp = {};
                for (var y in config) {
                    temp[y] = data[i][config[y]];
                }
                dataChart.push(temp);
            }
            callback(dataChart);
        };
        var dataColumn = function (data, days, callback) {
            var barData = [];
            for (var y = 0; y < data.length; y++) {
                var temp = [];
                for (var i = 1; i <= days; i++) {
                    temp.push([i, 0]);
                }
                for (var d in data[y].data) {
                    for (var x = 0; x < temp.length; x++) {
                        if (temp[x][0] == data[y].data[d].fecha) {
                            temp[x][1] = data[y].data[d].quantity;
                        }
                    }
                }
                barData.push({
                    data: temp,
                    label: data[y].name,
                    bars: {
                        show: true,
                        barWidth: 0.1,
                        order: y+1,
                        lineWidth: 0
                    }
                });
            }
            callback(barData);
        };
        var chart = function (data, config) {
            dataChart(data, config, function (pieData) {
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

        var column = function (data, days) {
            dataColumn(data, days, function (Data) {
                if ($('#bar-chart')[0]) {
                    $.plot($("#bar-chart"), Data, {
                        grid: {
                            borderWidth: 1,
                            borderColor: '#eee',
                            show: true,
                            hoverable: true,
                            clickable: true
                        },

                        yaxis: {
                            tickColor: '#eee',
                            tickDecimals: 0,
                            font: {
                                lineHeight: 13,
                                style: "normal",
                                color: "#9f9f9f",
                            },
                            shadowSize: 0
                        },

                        xaxis: {
                            tickColor: '#fff',
                            tickDecimals: 0,
                            font: {
                                lineHeight: 13,
                                style: "normal",
                                color: "#9f9f9f"
                            },
                            shadowSize: 0,
                        },

                        legend: {
                            container: '.flc-bar',
                            backgroundOpacity: 0.5,
                            noColumns: 0,
                            backgroundColor: "white",
                            lineWidth: 0
                        },
                        'bars': {
                            show: true,
                            fill: 0.7,
                            lineWidth: 1
                        },
                        colors: ['#F44336', '#03A9F4', '#8BC34A', '#009688', '#E91E63', '#FF9800', '#00BCD4', '#FFEB3B', '#9C27B0', '#673AB7', '#3F51B5', '#4CAF50']
                    });
                }
                /* Tooltips for Flot Charts */

                if ($(".flot-chart")[0]) {
                    $(".flot-chart").bind("plothover", function (event, pos, item) {
                        if (item) {
                            var x = item.datapoint[0].toFixed(0),
                                y = item.datapoint[1].toFixed(0);
                            //$(".flot-tooltip").html(item.series.label + " para el dia " + x + " tienes " + y + " ventas").css({top: item.pageY+5, left: item.pageX+5}).show();
                            $(".flot-tooltip").html(item.series.label+ " - " + y + " movimiento(s)").css({top: item.pageY+5, left: item.pageX+5}).show();
                        }
                        else {
                            $(".flot-tooltip").hide();
                        }
                    });

                    $("<div class='flot-tooltip' class='chart-tooltip'></div>").appendTo("body");
                }
            });
        };

        return {
            draw: chart,
            drawColummn: column
        };
    }])
    .factory('charts', function(){
        var chart, element;

        function make(element, type, data, options){
            element = document.getElementById(element);
            chart = new Chart(element, {
                type: type || "line",
                data: data || {},
                options: options || {}
            });

            return chart;
        }            

        return {
            make: make
        };           
    })
    .factory('toformData', function () {
        var dataFile = function (data) {
            if (undefined === data) return data;
            var formData = new FormData();
            angular.forEach(data, function (value, key) {
                if (value instanceof FileList) {
                    if (value.length === 1)
                        formData.append(key, value[0]);
                    else {
                        angular.forEach(value, function (file, index) {
                            formData.append(key + '_' + index, file);
                        });
                    }
                } else if (value instanceof Array) {
                    formData.append(key, angular.toJson(value));
                } else {
                    formData.append(key, value);
                }
            });
            return formData;
        };

        return {
            dataFile: dataFile
        };
    })
    .factory('petition', ["$http", "$location", "$q", function ($http, $location, $q) {
        var baseUrl = function (URL) {
            var prot = $location.protocol();
            var host = $location.host();
            return prot + '://' + host + '/' + URL;
        };


        return {

            get: function (URL, data) {
                data = data || {};
                var deferred = $q.defer();
                $http.get(baseUrl(URL), data).then(function (response) {
                    deferred.resolve(response.data);
                }, function(error){
                    deferred.reject(error);
                });

                return deferred.promise;
            },
            post: function (URL, data, config) {
                data = data || {};
                config = config || {};
                var deferred = $q.defer();
                $http.post(baseUrl(URL), data, config).then(function (response) {
                    deferred.resolve(response.data);
                }, function(error){
                    deferred.reject(error);
                });

                return deferred.promise;
            },
            put: function (URL, data) {
                data = data || {};
                var deferred = $q.defer();
                $http.put(baseUrl(URL), data).then(function (response) {
                    deferred.resolve(response.data);
                }, function(error){
                    deferred.reject(error);
                });

                return deferred.promise;
            },
            delete: function (URL, data) {
                data = data || {};
                var deferred = $q.defer();
                $http.delete(baseUrl(URL), data).then(function (response) {
                    deferred.resolve(response.data);
                }, function(error){
                    deferred.reject(error);
                });

                return deferred.promise;
            },
            custom: function (config) {
                var deferred = $q.defer();
                $http(config).then(function (response) {
                    deferred.resolve(response.data);
                }, function(error){
                    deferred.reject(error);
                });
                return deferred.promise;
            }
        };
    }])
    .factory('storage', ['$window', '$log', '$auth', function ($window, $log, $auth) {
        var storageType = 'localStorage';
        var roleName = 'roleName';
        var userName = 'fullName';
        var routeName = 'routesList';

        return {
            get: function (key) {
                return $window[storageType].getItem(key);
            },
            set: function (key, value) {
                return $window[storageType].setItem(key, value);
            },
            remove: function (key) {
                return $window[storageType].removeItem(key);
            },
            removeStorage: function () {
                $auth.removeToken();
                $window[storageType].removeItem(roleName);
                $window[storageType].removeItem(userName);
                $window[storageType].removeItem(routeName);
            }
        };
    }])
    .factory('util', ["$location", function ($location) {
        return {
            liPage: function (name) {
                $('li.active').removeClass('active');
                $('li#' + name).addClass('active');
            },
            muestraformulario: function () {
                $('#formulariohide').fadeIn('fast');
                $('html, body').stop().animate({
                    scrollTop: $('#formulariohide').offset().top - 100
                }, 1000);
            },
            ocultaformulario: function () {
                $('#formulariohide').fadeOut('fast');
                $("body").animate({
                    scrollTop: 0
                }, 1000);
            },
            modal: function (id) {
                id = id || 'Modal';
                $('#' + id).modal('show');
            },
            modalClose: function (id) {
                id = id || 'Modal';
                $('#' + id).modal('hide');
            },
            baseUrl: function (URL) {
                var prot = $location.protocol();
                var host = $location.host();
                return prot + '://' + host + '/' + URL;
            },
            resetTable: function (scope, compile, table){
                table = table || '#table';
                scope.tableData = [];
                $(table).AJQtable('view', scope, compile);
            },
            setDate: function (date){
               var datestring = "";
               date = new Date(date);
               var day = date.getDate();
                   day = (day.toString().length == 1)?'0'+day.toString():day;
               var month = date.getMonth() + 1;
                   month = (month.toString().length == 1)?'0'+month.toString():month;
               var year = date.getFullYear();
               return datestring.concat(' ',day,'-',month,'-',year);
            }
        };
    }])



    .controller('appCtrl', ["$state", "$log", "$scope", "$window", "$auth", "storage", "$location",
        function AppCtrl($state, $log, $scope, $window, $auth, storage, $location) {
        $scope.pageTitle = 'Home';

        $scope.logout = function () {
            if (storage.get("roleName") == "Asociado"){
                storage.removeStorage();
                $window.location.href = "/asociados";
            } else if (storage.get("roleName") === null){
                storage.removeStorage();
                $window.location.href = "/asociados";
            } else {
                storage.removeStorage();
                $window.location.href = "/";
            }
        };

        
        if ($auth.isAuthenticated()) {

            var role = storage.get('roleName');
            var name = storage.get('fullName');

            if (!role || !name)$scope.logout();

            $scope.userInfo = {
                role: role,
                name: name
            };

            $scope.$on('$stateChangeSuccess', function (event, toState) {
                if (angular.isDefined(toState.name)) {
                    $scope.pageTitle = toState.name + ' | NosVenden';
                }
            });
        } else {
            if(location.pathname != "/asociados/dashboard")
                $window.location.href = '/';
        }
    }]);
