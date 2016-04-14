angular.module('App')
    .controller('commentsCtrl', function($scope, $compile, util, $http, $httpParamSerializerJQLike, $templateCache){

        util.liPage('comments');

        $scope.tableConfig 	= 	{
                                    columns :	[
                                                    {"sTitle": "id"},
                                                    {"sTitle": "Nombre"},
                                                    {"sTitle": "Correo"},
                                                    {"sTitle": "Comentario"},
                                                    {"sTitle": "Estrellas"},
                                                    {"sTitle": "Fecha"},
                                                    {"sTitle": "Estado" ,"bSearchable": false , "bSortable" : false},
                                                    {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false}
                                                ],
                                    actions	:  	[
                                                    ['state',   {
                                                                    0 : { txt : 'Inactivo' , cls : 'btn-danger' },
                                                                    1 : { txt : 'Acitvo' ,  cls : 'btn-success'} ,
                                                                }
                                                    ],
                                                    ['actions', [
                                                                    ['Eliminar', 'remove' ,'btn-danger'],
                                                                    ['Detalle' , 'detail', 'btn-info']
                                                                ]
                                                    ]
                                                ],
                                    data  	: 	['id','name','email',['comment', 50],'stars','created_at','state','actions'],
                                    configStatus : 'status'
                                }

        //var config = {
        //    method: 'GET',
        //    url: 'http://api.nosvenden.com/api/admin/comment',
        //    headers: {
        //        'Content-Type': {'Content-Type': 'application/x-www-form-urlencoded'}
        //    },
        //    dataType: 'json',
        //    params : { page: 1, 'api-key':'$2y$10$X1UOd6hknkG7j2RG1o8LBet17DxB5A8dPXs5clDE3KtpnQ5gtn6Oe' }
        //};
        //
        //$http( config ).then(function (response) {
        //    console.log(response.data);
        //});

        $http({
            url: "http://api.nosvenden.com/api/admin/comment",
            method: "POST",
            data: {"foo":"bar"}
        }).success(function(data, status, headers, config) {
            console.log(data);
        }).error(function(data, status, headers, config) {
            console.log(status);
        });

        //$scope.method = 'GET';
        //$scope.url = 'https://angularjs.org/greet.php?callback=JSON_CALLBACK&name=Super%20Hero';
        //
        //$http({method: $scope.method, url: $scope.url, cache: $templateCache}).
        //    then(function(response) {
        //        console.log(response.status);
        //        console.log(response.data);
        //    }, function(response) {
        //        console.log(response.data || "Request failed");
        //        console.log(response.status);
        //});

        //$.get('http://api.nosvenden.com/api/comment',{ page : 1, 'api-key':'$2y$10$X1UOd6hknkG7j2RG1o8LBet17DxB5A8dPXs5clDE3KtpnQ5gtn6Oe'}, function (data) {
        //    console.log(data.comments.data);
        //    $scope.tableData = data.comments.data ;
        //}, 'json');



        //$('#tab_users').AJQtable('view', $scope, $compile);
    });