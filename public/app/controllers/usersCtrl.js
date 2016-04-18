angular.module('App')
    .controller('usersCtrl', function($scope, $compile, util, petition, toastr){

        util.liPage('users');

        $scope.tableConfig 	= 	{
                                    columns :	[
                                                    {"sTitle": "Fecha", "sWidth": "160px", "aaSorting": 'desc'},
                                                    {"sTitle": "Nombre", "bSortable" : false},
                                                    {"sTitle": "Correo", "bSortable" : false},
                                                    {"sTitle": "Estrellas", "sWidth": "1px"},
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
                                                                    ['Eliminar', 'remove' ,'btn-danger'],
                                                                    ['ver', 'view' ,'btn-primary']
                                                                ]
                                                    ]
                                                ],
                                    data  	: 	['created_at','name','email','stars','status','actions'],
                                    configStatus : 'status'
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
            status: true,
        };

        $scope.list = function() {
            petition.get('users')
                .then(function(data){
                    $scope.tableData = data.comments;
                    $('#tab_users').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema al cargar los registros.');
                    $scope.updateList = false;
                });
        };

        $scope.status = function( ind, dom ) {
            alertConfig.title = "¿Desea cambiar el estado de este comentario?";
            var id = $scope.tableData[ind].id;
            swal(alertConfig ,
                function() {
                    petition.custom('', { id : id}).then(function(data){
                        toastr.success(data.message);
                        changeButton(ind , dom.target);
                    },function(error){
                        console.log(error);
                        toastr.error('Ups algo paso con el servidor');
                    });
                });
        };
        
        $scope.submit = function () {
            $scope.createUser=true;
            petition.post('api/user', $scope.empleado).then(function(data){
                toastr.success(data.message);
                $scope.createUser=false;
            }, function(error){
                toastr.error(error.data.message);
                $scope.createUser=false;
            });
        }

        $scope.cancelar = function () {
            $scope.empleado = angular.copy($scope.empleadoClear);
        }

        angular.element(document).ready(function(){
            $scope.empleado = angular.copy($scope.empleadoClear);
            //$scope.list();
        });
    });