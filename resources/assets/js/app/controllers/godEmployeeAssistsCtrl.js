angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('assistsByGod', {
                url: '/Asistencias-por-mes-por-empleado/:id/:year/:month',
                templateUrl: 'app/partials/godEmployeeAssists.html',
                controller : 'godEmployeeAssistsCtrl'
            });
    }])
    .controller('godEmployeeAssistsCtrl', ["$scope", "$compile", "$stateParams", "$log", "util", "petition", "toastr",
        function($scope, $compile, $stateParams, $log, util, petition, toastr){

        util.liPage('assists');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha", "bSortable" : true},
                {"sTitle": "ingreso", "bSortable" : false, "sWidth": "80px"},
                {"sTitle": "break", "bSortable" : false, "sWidth": "80px"},
                {"sTitle": "F. break", "bSortable" : false, "sWidth": "80px"},
                {"sTitle": "Salida", "bSortable" : false, "sWidth": "80px"},
                {"sTitle": "M. Día", "bSortable" : false, "sWidth": "60px"},
                {"sTitle": "T. min", "bSortable" : false, "sWidth": "60px"},
                {"sTitle": "T. monto", "bSortable" : false, "sWidth": "60px"},
                {"sTitle": "B. min", "bSortable" : false, "sWidth": "60px"},
                {"sTitle": "B. monto", "bSortable" : false, "sWidth": "60px"}
            ],
            data  	: 	['date','start','break','end_break','end','amount','delay_min','delay_amount','lunch_min','lunch_amount']
        };


        $scope.list = function(data) {
            petition.get('api/payroll/employee/assists/by/god',{ params : data})
                .then(function(data){
                    $scope.discounts = data.discounts;
                    $scope.tableData = data.registers;
                    $('#table').AJQtable('view', $scope, $compile);
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            var data = {};
            data.id = $stateParams.id;
            data.year = $stateParams.year;
            data.month = $stateParams.month;
            $scope.list(data);
        });
    }]);