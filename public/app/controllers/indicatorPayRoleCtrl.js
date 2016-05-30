angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('ReportePlanilla', {
                url: '/Reporte-de-planilla-entre-fechas',
                templateUrl: 'app/partials/indicatorPayRole.html',
                controller : 'reportPayRolCtrl'
            });
    })
    .controller('reportPayRolCtrl', function($scope, $compile, $log, util, petition, toastr, $state){

        util.liPage('reportePlanilla');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Empleado", "bSortable" : true,"bSearchable": false},
                {"sTitle": "Sueldo", "bSortable" : false,"bSearchable": false},
                {"sTitle": "Pago del mes", "bSortable" : false,"bSearchable": false},
                {"sTitle": "Minutos tarde", "bSortable" : false,"bSearchable": false},
                {"sTitle": "Descuento por tardanza", "bSortable" : false,"bSearchable": false},
                {"sTitle": "Minutos demora break", "bSortable" : false,"bSearchable": false},
                {"sTitle": "descuento por break", "bSortable" : false,"bSearchable": false},
                {"sTitle": "Accion", "bSortable" : false,"bSearchable": false}
            ],
            actions	:  	[
                ['actions',
                    [
                        ['ver dias', 'view' ,'btn-primary']
                    ]
                ]
            ],
            data  	: 	['name', 'salary','pay_amount','delay_min','delay_amount','lunch_min','lunch_amount','actions']
        };

        $scope.data = {
            year : null,
            month: null,
            employee_id: null,
            area_id: null
        };

        $scope.years = [2015,2016,2017,2018,2019,2020,2021,2022,2023,2024,2025,2026,2027,2028,2029,2030];
        $scope.months = [
            {id: 1, name: 'Enero'},
            {id: 2, name: 'Febrero'},
            {id: 3, name: 'Marzo'},
            {id: 4, name: 'Abril'},
            {id: 5, name: 'Mayo'},
            {id: 6, name: 'Junio'},
            {id: 7, name: 'Julio'},
            {id: 8, name: 'Agosto'},
            {id: 9, name: 'Septiembre'},
            {id: 10, name: 'Octubre'},
            {id: 11, name: 'Nomviembre'},
            {id: 12, name: 'Diciembre'}
        ];

        $scope.list = function(data) {
            data || (data = {});
            $scope.updateList = true;
            petition.get('api/payroll', { params: data})
                .then(function(data){
                    $scope.tableData = data.payroll;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Uyuyuy dice: ' + error.data.message);
                    $scope.updateList = false;
                    util.resetTable($scope,$compile);
                });
        };

        $scope.view = function(i) {
            $state.go("assistsByGod", { id: $scope.tableData[i].id, year: $scope.data.year, month: $scope.data.month });
        };

        $scope.listEmployee = function() {
            petition.get('api/employee')
                .then(function(data){
                    $scope.employees = data.employees;
                }, function(error){
                    console.log(error);
                    toastr.error('Uyuyuy dice: ' + error.data.message);
                });
        };

        $scope.listAreas = function() {
            petition.get('api/employee/get/roles')
                .then(function(data){
                    $scope.areas = data.areas;
                }, function(error){
                    console.log(error);
                    toastr.error('Uyuyuy dice: ' + error.data.message);
                });
        };

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            util.resetTable($scope,$compile);
            $scope.list();
            $scope.listEmployee();
            $scope.listAreas();
        });
    });