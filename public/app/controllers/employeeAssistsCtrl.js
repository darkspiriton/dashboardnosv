angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('assists', {
                url: '/Asistencias-por-mes',
                templateUrl: 'app/partials/employeeAssists.html',
                controller : 'employeeAssistsCtrl'
            });
    })
    .controller('employeeAssistsCtrl', function($scope, $compile, $log, util, petition, toastr){

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


        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/payroll/employee/assists')
                .then(function(data){
                    $scope.discounts = data.discounts;
                    $scope.tableData = data.registers;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.list();
        });
    });