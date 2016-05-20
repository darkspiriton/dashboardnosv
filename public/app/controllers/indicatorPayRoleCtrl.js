angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('ReportePlanilla', {
                url: '/Reporte-de-planilla-entre-fechas',
                templateUrl: 'app/partials/indicatorPayRole.html',
                controller : 'reportPayRolCtrl'
            });
    })
    .controller('reportPayRolCtrl', function($scope, $compile, $log, util, petition, toastr, $filter, chart){

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
            ],
            data  	: 	['name', 'salary','pay_amount','delay_min','delay_amount','lunch_min','lunch_amount']
        };

        $scope.data = {
            year : null,
            month: null,
            employee_id: null,
            area_id: null
        };

        $scope.areas = [
            {id: 1, name: 'Administracion'},
            {id: 2, name: 'Sistemas'},
            {id: 3, name: 'Publicidad'},
            {id: 4, name: 'Ventas'}
        ];

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

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/payroll', { params: $scope.data})
                .then(function(data){
                    $scope.tableData = data.payroll;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Uyuyuy dice: ' + error.data.message);
                    $scope.updateList = false;
                    resetTable();
                });
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
        function resetTable(){
            $scope.tableData = [];
            $('#table').AJQtable('view', $scope, $compile);
        }

        angular.element(document).ready(function(){
            resetTable();
            $scope.listEmployee();
        });
    });