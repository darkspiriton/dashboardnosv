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
                {"sTitle": "Hora de ingreso", "bSortable" : true},
                {"sTitle": "Hora de salida" ,"bSearchable": false , "bSortable" : false },
                {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "190px"}
            ],
            actions	:  	[
                ['actions', [
                    ['Editar', 'edit' ,'btn-primary']
                ]
                ]
            ],
            data  	: 	['name','sexo','area','actions'],
            configStatus : 'status'
        };

        var alertConfig = {
            title: "¿Esta seguro?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "SI",
            cancelButtonColor: "#212121",
            cancelButtonText: "CANCELAR",
            closeOnConfirm: true
        };

        $scope.entryClear = {
            id: null,
            employee: null,
            reconsile: 0,
            justified: 0,
            date: null,
            ini: null,
            fin: null
        };

        $scope.list = function() {
            petition.get('api/user')
                .then(function(data){
                    $scope.tableData = data.users;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.view = function( ind ){
            var id = $scope.tableData[ind].id;
            petition.get('api/user/' + id)
                .then(function(data){
                    $scope.userDetail = data.user;
                    util.modal();
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
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
            if($scope.entry.fin < $scope.entry.ini) return toastr.error('Uyuyuy dice: La hora de salida debe ser mayor.');
            var method = ( $scope.entry.id )?'PUT':'POST';
            var url = ( method == 'PUT')? util.baseUrl('api/lllllll/' + $scope.entry.id): util.baseUrl('api/lll');
            var config = {
                method: method,
                url: url,
                params: $scope.entry
            };
            $scope.formSubmit=true;
            petition.custom(config).then(function(data){
                toastr.success(data.message);
                $scope.formSubmit=false;
                $scope.list();
                util.ocultaformulario();
            }, function(error){
                toastr.error('Ups ocurrio un problema: ' + error.data.message);
                $scope.formSubmit=false;
            });
        };

        $scope.cancel = function () {
            $scope.entry = angular.copy($scope.entryClear);
            util.ocultaformulario();
        };

        $scope.cancel2 = function () {
            console.log($scope.entry);
        };

        $scope.new = function(){
            $scope.entry = angular.copy($scope.entryClear);
            util.muestraformulario();
        };

        angular.element(document).ready(function(){
            $scope.entry = angular.copy($scope.entryClear);

            $scope.employees = [{id: 1, name:'Mario Vargas Llosa'}, {id: 2, name: 'Pedro Marmol Picapiedra'}];

            $scope.list();
        });
    });