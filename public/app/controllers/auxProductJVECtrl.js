angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Productos JVE', {
                url: '/Vista-general-de-productos-KARDEX',
                templateUrl: 'app/partials/auxProductJVE.html',
                controller : 'productsJVECtrl'
            });
    })
    .controller('productsJVECtrl', function($scope, $compile, $state, $log, util, petition, toastr){

        util.liPage('productsJVE');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha", "bSortable" : true, 'sWidth': '100px'},
                {"sTitle": "Codigo", "bSortable" : true, 'sWidth': '1px'},
                {"sTitle": "Nombre", "bSortable" : true, 'sWidth': '250px'},
                {"sTitle": "Proveedor", "bSortable" : true},
                {"sTitle": "Talla", "bSortable" : true},
                {"sTitle": "Color" , "bSearchable": true},
                {"sTitle": "Tipos" , "bSearchable": true},
                {"sTitle": "Precio (S/.)" , "bSearchable": true, 'sWidth': '100px'},
                {"sTitle": "Status" , "bSearchable": true},
            ],
            actions	:   	[
                ['status',   {
                    0 : { txt : 'salida' , cls : 'btn-danger', dis : false},
                    1 : { txt : 'disponible' ,  cls : 'btn-success', dis : false},
                    2 : { txt : 'vendido' ,  cls : 'bgm-teal', dis : false}
                }
                ]
            ],
            data  	: 	['date','cod','name','provider','size','color','types','precio','status'],
            configStatus : 'status'
        };

        $scope.list = function(s) {
            $scope.updateList = true;
            var obj = { params: {}};
            if(typeof s !== 'undefined')
                obj.params.search = s;
            petition.get('api/auxproduct', obj)
                .then(function(data){
                    $scope.tableData = data.products;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        // End events

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.list();
        });
    });