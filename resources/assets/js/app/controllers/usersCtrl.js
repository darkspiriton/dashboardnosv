angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('Usuarios', {
                url: '/Adminitracion-de-usuarios',
                templateUrl: 'app/partials/users.html',
                controller : 'usersCtrl'
            });
    }])
    .controller('usersCtrl',["$scope", "$compile", "$log", "util", "petition", "toastr",
        function($scope, $compile, $log, util, petition, toastr){

        util.liPage('users');

        $scope.tableConfig 	= 	{
                                    columns :	[
                                                    {"sTitle": "Nombre", "bSortable" : true},
                                                    {"sTitle": "Rol", "bSortable" : true},
                                                    {"sTitle": "Estado" ,"bSearchable": false , "bSortable" : false , "sWidth": "80px"},
                                                    {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "190px"}
                                                ],
                                    actions	:  	[
                                                    ['status',   {
                                                                    0 : { txt : 'Inactivo' , cls : 'btn-danger' },
                                                                    1 : { txt : 'Activo' ,  cls : 'btn-success' } ,
                                                                }
                                                    ],
                                                    ['actions', [
                                                                    ['ver', 'view' ,'btn-info'],
                                                                    ['Editar', 'edit' ,'btn-primary']
                                                                ]
                                                    ]
                                                ],
                                    data  	: 	['full_name','rol','status','actions'],
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

        $scope.empleadoClear = {
            first_name: '',
            last_name: '',
            email: '',
            phone: '',
            address: '',
            birth_date: '',
            sex: '',
            photo:'',
            role_id: '',
            password: '',
            status: true
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

        $scope.status = function( ind, dom ) {
            alertConfig.title = "¿Desea cambiar el estado del usuario?";
            var id = $scope.tableData[ind].id;
            swal(alertConfig ,
                function() {
                    petition.delete('api/user/' + id ).then(function(data){
                        toastr.success(data.message);
                        changeButton(ind , dom.target);
                    },function(error){
                        $log.log(error);
                        toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    });
                });
        };

        $scope.view = function( ind ){
            var id = $scope.tableData[ind].id;
            $scope.userDetail = {};
            petition.get('api/user/' + id)
                .then(function(data){
                    $scope.userDetail = data.user;
                    util.modal();
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.ListRoles = function(){
            petition.get('api/user/get/roles')
                .then(function(data){
                    $scope.roles = data.roles;
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.edit = function( ind ){
            var id = $scope.tableData[ind].id;
            petition.get('api/user/' + id)
                .then(function(data){
                    data.user.role_id = parseInt(data.user.role_id);
                    data.user.status = parseInt(data.user.status);
                    $scope.empleado = data.user;
                    $scope.empleado.password = '**********';
                    util.muestraformulario();
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };
        
        $scope.submit = function () {
            var method = ( $scope.empleado.id )?'PUT':'POST';
            var url = ( method == 'PUT')? util.baseUrl('api/user/' + $scope.empleado.id): util.baseUrl('api/user');
            if ( $scope.empleado.password == '**********' )
                $scope.empleado.password = null;
            var config = {
                            method: method,
                            url: url,
                            params: $scope.empleado
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
            $scope.empleado = angular.copy($scope.empleadoClear);
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.empleado = angular.copy($scope.empleadoClear);
            util.muestraformulario();
        };

        $scope.getStatus = function( status ){
            if (status == 1)return 'Activo';
            else return 'Inactivo';
        };

        var changeButton = function (ind, dom){
            $scope.tableData[ind].status = ($scope.tableData[ind].status == 0)? 1 : 0;
            if ( $scope.tableData[ind].status == 1){
                $(dom).removeClass('btn-danger');
                $(dom).addClass('btn-success');
                $(dom).html('Activo');
            }else{
                $(dom).removeClass('btn-success');
                $(dom).addClass('btn-danger');
                $(dom).html('Inactivo');
            }
        };

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.empleado = angular.copy($scope.empleadoClear);
            $scope.list();
            $scope.ListRoles();
        });
    }]);