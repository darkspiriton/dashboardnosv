angular.module('App')
    .controller('commentsCtrl', function($scope, $compile, util, toastr){

        util.liPage('comments');

        $scope.tableConfig 	= 	{
                                    columns :	[
                                                    {"sTitle": "Nombre", "bSortable" : false},
                                                    {"sTitle": "Correo", "bSortable" : false},
                                                    {"sTitle": "Comentario", "bSortable" : false},
                                                    {"sTitle": "Estrellas", "sWidth": "50px"},
                                                    {"sTitle": "Fecha"},
                                                    {"sTitle": "Estado" ,"bSearchable": false , "bSortable" : false , "sWidth": "80px"},
                                                    {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "80px"}
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
                                    data  	: 	['name','email',['comment', 25],'stars','created_at','status','actions'],
                                    configStatus : 'status'
                                };

        var data =  {
                        'api-key':'$2y$10$X1UOd6hknkG7j2RG1o8LBet17DxB5A8dPXs5clDE3KtpnQ5gtn6Oe'
                    };

        $.getJSON( "http://api.nosvenden.com/api/admin/comment", data )
            .done(function( data ) {
                $scope.tableData = data.comments;
                $('#tab_users').AJQtable('view', $scope, $compile);
            })
            .fail(function() {
                toastr.error('Ups ocurrio un problema al cargar los registros.');
            });

        $scope.status = function( ind ) {
            swal({
                    title: "¿Desea cambiar el estado de este comentario?",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    cancelButtonText: "CANCELAR",
                    closeOnConfirm: true
                },
                function() {
                    $.ajax({
                        url: 'http://api.nosvenden.com/api/comment/' + $scope.tableData[ind].id,
                        type: 'PUT',
                        data: data,
                        dataType: 'json'
                    }).done(function (data) {
                        toastr.success('Se activo el comentario');
                    }).error(function (err) {
                        toastr.error('Ups algo paso con el servidor');
                    });
                });
        };

        $scope.remove = function( ind ) {
            swal({
                    title: "¿Desea eliminar este comentario?",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    cancelButtonText: "CANCELAR",
                    closeOnConfirm: true
                },
                function() {
                    $.ajax({
                        url: 'http://api.nosvenden.com/api/comment/' + $scope.tableData[ind].id,
                        type: 'DELETE',
                        data: data,
                        dataType: 'json'
                    }).done(function (data) {
                        toastr.success('Se elimino correctamente');
                    }).error(function (err) {
                        toastr.error('Ups algo paso con el servidor');
                    });
                });
        };

        $scope.cambiaBoton = function (ind, dom){
            $scope.tableData[ind].est = ($scope.tableData[ind].est == 0)? 1 : 0;
            if ( $scope.tableData[ind].est == 1){
                $(dom).removeClass('btn-danger');
                $(dom).addClass('btn-success');
                $(dom).html('Activo');
            }else{
                $(dom).removeClass('btn-success');
                $(dom).addClass('btn-danger');
                $(dom).html('Inactivo');
            }
        }


    });