angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('PayrollEntry', {
                url: '/Planilla-registro-de-entradas',
                templateUrl: 'app/partials/payrollEntry.html',
                controller : 'payrollEntryCtrl'
            });
    })
    .controller('payrollEntryCtrl', function($scope, $compile, $log, util, petition, toastr){

        util.liPage('payrollEntry');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Nombre de empleado", "bSortable" : true},
                {"sTitle": "Ingreso", "bSortable" : false},
                {"sTitle": "Break", "bSortable" : false},
                {"sTitle": "F. Break", "bSortable" : false},
                {"sTitle": "Salida" ,"bSearchable": false , "bSortable" : false },
                {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "90px"}
            ],
            actions	:  	[
                ['actions', [
                    ['Editar', 'edit' ,'btn-primary']
                ]
                ]
            ],
            data  	: 	['name','start','break','end_break','end','actions'],
            configStatus : 'status'
        };

        //var alertConfig = {
        //    title: "¿Esta seguro?",
        //    text: "",
        //    type: "warning",
        //    showCancelButton: true,
        //    confirmButtonColor: "#DD6B55",
        //    confirmButtonText: "SI",
        //    cancelButtonColor: "#212121",
        //    cancelButtonText: "CANCELAR",
        //    closeOnConfirm: true
        //};

        $scope.entryClear = {
            id: null,
            employee_id: null,
            conciliate: 0,
            justification: 0,
            date: null,
            start_time: null,
            end_time: null,
            start_time_launch: null,
            end_time_launch: null
        };

        $scope.list = function(date) {
            date || (date = new Date());
            petition.get('api/payroll/employee/assists/day', {params: {date: date}})
                .then(function(data){
                    $scope.tableData = data.registers;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Uyuyuy dice: ' + error.data.message);
                    $scope.updateList = false;
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

        //$scope.edit = function( ind ){
        //    var id = $scope.tableData[ind].id;
        //    petition.get('api/user/' + id)
        //        .then(function(data){
        //            $scope.empleado = data.user;
        //            $scope.empleado.password = '**********';
        //            util.muestraformulario();
        //        }, function(error){
        //            toastr.error('Ups ocurrio un problema: ' + error.data.message);
        //        });
        //};

        $scope.submit = function () {
            if($scope.entry.start_time > $scope.entry.end_time) return toastr.error('Uyuyuy dice: La hora de salida debe ser mayor.');
            if($scope.entry.start_time_launch > $scope.entry.end_time_launch) return toastr.error('Uyuyuy dice: La hora de fin de almuerzo debe ser mayor.');
            var method = ( $scope.entry.id )?'PUT':'POST';
            var url = ( method == 'PUT')? util.baseUrl('api/assist/' + $scope.entry.id): util.baseUrl('api/assist');
            var config = {
                method: method,
                url: url,
                params: $scope.entry
            };
            $scope.formSubmit=true;
            petition.custom(config).then(function(data){
                toastr.success(data.message);
                $scope.formSubmit=false;
                $scope.list($scope.entry.date);
                ClearFroAdd();
            }, function(error){
                toastr.error('Uyuyuy dice: '+error.data.message);
                $scope.formSubmit=false;
            });
        };

        function ClearFroAdd(){
            $scope.entry.id = null;
            $scope.entry.conciliate = 0;
            $scope.entry.justification = 0;
            $scope.entry.start_time = null;
            $scope.entry.end_time = null;
            $scope.entry.start_time_launch = null;
            $scope.entry.end_time_launch = null;
        }

        $scope.cancel = function () {
            $scope.entry = angular.copy($scope.entryClear);
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.entry = angular.copy($scope.entryClear);
            util.muestraformulario();
        };

        angular.element(document).ready(function(){
            $scope.entry = angular.copy($scope.entryClear);

            $scope.list();
            $scope.listEmployee();
        });
    });