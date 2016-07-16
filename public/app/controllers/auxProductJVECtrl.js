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
                {"sTitle": "Color", "bSortable" : true, "bSearchable": true},
                {"sTitle": "Tipos", "bSortable" : true, "bSearchable": true},
                {"sTitle": "Estado V." , "bSearchable": false},
                {"sTitle": "P. Real" , "bSearchable": false},
                {"sTitle": "Precio" , "bSearchable": false},
                {"sTitle": "Status", "bSortable" : false, "bSearchable": true},
                {"sTitle": "Accion", "bSortable" : false, "bSearchable": false},
            ],
            actions	:   	[
                ['status',   {
                    0 : { txt : 'salida' , cls : 'btn-danger', dis : false},
                    1 : { txt : 'disponible' ,  cls : 'btn-success', dis : false},
                    2 : { txt : 'vendido' ,  cls : 'bgm-teal', dis : false},
                    3 : { txt : 'reservado' ,  cls : 'bgm-black', dis : false}
                }
                ],
                ['actions', [
                    ['movimientos','movements','bgm-indigo'],
                    ['reservar','reserve','bgm-purple']
                ]
                ]
            ],
            data  	: 	['date','cod','name','provider','size','color','types','liquidation','price_real','precio','status','actions'],
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


        /**
         *  Nueva vista de movimientos por producto
         *
         *  @params int         id
         *  @return Collection  movements
         */

        $scope.movements = function(i){
            var id = $scope.tableData[i].id;
            petition.get(`api/auxproduct/get/movements/${id}`)
                .then(function(data){
                    $scope.productMovements = data.movements;
                    util.modal('productMovements');
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        /*
         * Helper para vista de detalle de movimientos
         *
         *  @params String
         *  @return tag:a-button
         */

        $scope.movementStatus = function(status){
            var info = {
                'Retornado': ['Retornado','bgm-red'],
                'Vendido': ['Vendido','bgm-teal'],
                'salida': ['Salida','bgm-deeppurple']
            };

            return `<a class="btn btn-xs disabled ${info[status][1]}">${info[status][0]}</a>`;
        };

        /*
         * Helper para vista de detalle de movimientos
         *
         *  @params Int
         *  @return tag:a-button
         */

        $scope.payStatus = function(status){
            var info = {
                0: ['Normal','bgm-green',false],
                1: ['Liquidacion','btn-info',false],
            };

            return `<a class="btn btn-xs disabled ${info[status][1]}">${info[status][0]}</a>`;
        };

        /*
         * Helper para vista de detalle de movimientos
         *
         *  @params Int
         *  @return Object:String   Confirmation
         */

        $scope.reserve = function(i){
            var id = $scope.tableData[i].id;
            petition.put(`api/auxproduct/reserve/${id}`)
                .then(function(data){
                    $scope.list();
                    toastr.success(data.message);
                }, function(error){
                    $scope.list();
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        }

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.list();
        });
    });