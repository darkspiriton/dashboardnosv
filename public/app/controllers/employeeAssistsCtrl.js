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
                {"sTitle": "ingreso", "bSortable" : false},
                {"sTitle": "break", "bSortable" : false},
                {"sTitle": "F. break", "bSortable" : false},
                {"sTitle": "salida", "bSortable" : false},
                {"sTitle": "T. min", "bSortable" : false, "sWidth": "80px"},
                {"sTitle": "T. monto", "bSortable" : false, "sWidth": "80px"},
                {"sTitle": "B. min", "bSortable" : false, "sWidth": "80px"},
                {"sTitle": "B. monto", "bSortable" : false, "sWidth": "80px"}
            ],
            data  	: 	['date','start','break','end_break','end','delay_min','delay_amount','lunch_min','lunch_amount']
        };


        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/payroll/employee/assists',{params: {year:2016, month:5}})
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
            $scope.list();
        });
    });