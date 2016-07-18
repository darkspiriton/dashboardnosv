angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('Productos JVE', {
                url: '/Vista-general-de-productos-KARDEX',
                templateUrl: 'app/partials/auxProductJVE.html',
                controller : 'productsJVECtrl'
            });
    }])
    .controller('productsJVECtrl', ["$scope", "$compile", "$state", "$log", "util", "petition", "toastr",
        function($scope, $compile, $state, $log, util, petition, toastr){

        util.liPage('productsJVE');


        var status = {
                    0 : ['salida','btn-danger', false],
                    1 : ['disponible', 'btn-success', false],
                    2 : ['vendido', 'bgm-teal', false],
                    3 : ['reservado', 'bgm-black', false]
                };

        var statusForSale = {
                    0 : ['normal', 'btn-success', false],
                    1 : ['liquidacion', 'btn-info', false]
                };


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
            buttons :
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'status', column: 'status', render: status},
                            { name: 'statusForSale', column: 'liquidation', render: statusForSale},
                        ]
                    },
                    {
                        type: 'actions',
                        list: [
                            { name: 'actions', render: [['movimientos','movements','bgm-indigo'],['reservar','reserve','bgm-purple']]}
                        ]
                    }
                ],
            data  	: 	['date','cod','name','provider','size','color','types','statusForSale','price_real','precio','status','actions'],
        };

        $scope.list = function(s) {
            $scope.updateList = true;
            var obj = { params: {}};
            if(typeof s !== 'undefined')
                obj.params.search = s;
            petition.get('api/auxproduct', obj)
                .then(function(data){
                    $scope.tableData = data.products;
                    $('#table').AJQtable2('view2', $scope, $compile);
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
            $scope.list();
        });
    }]);