angular.module('App')
    .controller('commentsCtrl', function($scope, $compile,$log , util, toastr, petition){

        util.liPage('comments');

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
                                                                    1 : { txt : 'Activo' ,  cls : 'btn-success', dis : false} ,
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

        var data =  {
            'api-key':'$2y$10$X1UOd6hknkG7j2RG1o8LBet17DxB5A8dPXs5clDE3KtpnQ5gtn6Oe'
        };

        $scope.list = function(){
            $scope.updateList = true;
            var config = {
                            method: 'GET',
                            url: 'http://api.nosvenden.com/api/admin/comment',
                            params: data,
                        };
            petition.custom(config).then(function(data){
                $scope.tableData = data.comments;
                $('#table').AJQtable('view', $scope, $compile);
                $scope.updateList = false;
            },function(error){
                $log.log(error);
                toastr.error('Ups ocurrio un problema al cargar los registros.');
                $scope.updateList = false;
            });
        };

        $scope.status = function( ind, dom ) {
            alertConfig.title = "¿Desea cambiar el estado de este comentario?";
            var id = $scope.tableData[ind].id;
            swal(alertConfig ,
                function() {
                    var config = {
                        method: 'PUT',
                        url: 'http://api.nosvenden.com/api/comment/'+ id,
                        params: data,
                    };
                    petition.custom(config).then(function(data){
                        toastr.success(data.message);
                        changeButton(ind , dom.target);
                    },function(error){
                        $log.log(error);
                        toastr.error('Ups algo paso con el servidor');
                    });
                });
        };

        $scope.remove = function( ind, dom ) {
            alertConfig.title = "¿Desea eliminar este comentario?";
            swal(alertConfig ,
                function() {
                    var config = {
                        method: 'DELETE',
                        url: 'http://api.nosvenden.com/api/comment/'+ $scope.tableData[ind].id,
                        params: data,
                    };
                    petition.custom(config).then(function(data){
                        $('#tab_users').AJQtable('removeRow', dom.target , function(){
                            toastr.success(data.message);
                        });

                    },function(error){
                        $log.log(error);
                        toastr.error('Ups algo paso con el servidor');
                    });
                });
        };

        $scope.view = function( ind ){
            $scope.comment.name = $scope.tableData[ind].name;
            $scope.comment.message = $scope.tableData[ind].comment;
            util.modal();
        };

        changeButton = function (ind, dom){
            $scope.tableData[ind].status = ($scope.tableData[ind].status == 0)? 1 : 0;
            if ( $scope.tableData[ind].status == 1){
                $(dom).removeClass('btn-danger');
                $(dom).addClass('btn-success');
                $(dom).html('Activo');
                $(dom).attr({ 'disabled': 'disabled'});
            }else{
                $(dom).removeClass('btn-success');
                $(dom).addClass('btn-danger');
                $(dom).html('Inactivo');
            }
        };

        angular.element(document).ready(function(){
            $scope.comment = {};
            $scope.list();
        });
    });