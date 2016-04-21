angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Kardex', {
                url: '/Kardex/:id',
                templateUrl: 'app/partials/kardex.html',
                controller : 'kardexCtrl'
            });
    })
    .controller('kardexCtrl', function($scope, $compile, $stateParams, $log, util, petition, toastr){

        //util.liPage('users');

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

        $scope.list = function() {
            petition.get('api/kardex/' + $scope.id)
                .then(function(data){
                    return console.log(data.kardex);
                    $scope.tableData = data.kardex;
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


        $scope.getStatus = function( status ){
            if (status == 1)return 'Activo';
            else return 'Inactivo';
        };

        angular.element(document).ready(function(){
            $scope.id = $stateParams.id;
            $scope.list();
        });
    });