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

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Codigo", "bSortable" : true, "sWidth": "80px"},
                {"sTitle": "Nombre", "bSortable" : true},
                {"sTitle": "Price", "bSortable" : true},
                {"sTitle": "Estado" ,"bSearchable": false , "bSortable" : false , "sWidth": "80px"},
                {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "190px"}
            ],
            actions	:  	[
                ['status',   {
                    0 : { txt : 'Salida' , cls : 'btn-danger', dis : false},
                    1 : { txt : 'Disponible' ,  cls : 'btn-success', dis : false } ,
                }
                ],
                ['actions', [
                    ['Detalle', 'view' ,'btn-info'],
                    ['movimiento', 'movement' ,'btn-primary']
                ]
                ]
            ],
            data  	: 	['id','name','price','status','actions'],
            configStatus : 'stock'
        };

        $scope.list = function() {
            petition.get('api/kardex', { params : {id : $scope.id }})
                .then(function(data){
                    $scope.productName = data.products.name;
                    $scope.refactor( data.products ,function( products){
                        $scope.tableData = products;
                        $('#table').AJQtable('view', $scope, $compile);
                        $scope.updateList = false;
                    });
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.refactor = function( products, calback ){
            for (var i in products.kardexs){
                products.kardexs[i].name  =  products.name;
                products.kardexs[i].price =  products.price;
                products.kardexs[i].image =  products.image;
            }
            calback(products.kardexs);
        };

        $scope.view = function( ind ){
            $scope.kardexAttributes = [];
            var id = $scope.tableData[ind].group_attribute_id;
            petition.get('api/attribute/' + id)
                .then(function(data){
                    $scope.kardexAttributes = data.attributes;
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