angular.module('App')
    .controller('usersCtrl', function($scope, $compile, $log, util, petition, toastr){

        util.liPage('users');

        $scope.tableConfig 	= 	{
                                    columns :	[
                                                    {"sTitle": "Nombre", "bSortable" : true},
                                                    {"sTitle": "Rol", "bSortable" : true},
                                                    {"sTitle": "Estado" ,"bSearchable": false , "bSortable" : false , "sWidth": "80px"},
                                                    {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "180px"}
                                                ],
                                    actions	:  	[
                                                    ['status',   {
                                                                    0 : { txt : 'Inactivo' , cls : 'btn-danger' },
                                                                    1 : { txt : 'Acitvo' ,  cls : 'btn-success', dis : false} ,
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
            status: false
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
                    petition.custom('user', { id : id}).then(function(data){
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
            petition.get('api/user/' + id)
                .then(function(data){
                    $scope.userDetail = data.user;
                    util.modal();
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.edit = function( ind ){
            var id = $scope.tableData[ind].id;
            petition.get('api/user/' + id)
                .then(function(data){
                    $scope.empleado = data.user;
                    var role_id = $scope.empleado.role_id;
                    $scope.empleado.role_id = role_id.toString();
                    $scope.empleado.password = '**********';
                    util.muestraformulario();
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };
        
        $scope.submit = function () {
            var method = ( $scope.empleado.id )?'PUT':'POST';
            var url = ( method == 'PUT')? util.baseUrl('api/user/' + $scope.empleado.id): util.baseUrl('api/user');

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

        angular.element(document).ready(function(){
            $scope.empleado = angular.copy($scope.empleadoClear);
            $scope.list();
        });
    });