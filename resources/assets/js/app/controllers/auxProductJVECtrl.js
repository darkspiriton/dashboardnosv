angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('Productos JVE', {
                url: '/Vista-general-de-productos-KARDEX-JVE',
                templateUrl: 'app/partials/auxProductJVE.html',
                controller : 'productsJVECtrl'
            });
    }])
    .controller('productsJVECtrl', ["$scope", "$compile", "$state", "$log", "util", "petition", "toastr",
        function($scope, $compile, $state, $log, util, petition, toastr){

        /*
         |
         |  Init
         |
         */
        $scope.provider = {};
        $scope.product = {};
        $scope.data = {};
        $scope.productAux = [];

        var messages = {
            reserve: {title: "¿Esta seguro de cambiar estado a reservado?", response: "Se cambio el estado a reservado."},
            transition: {title: "¡Cuidado este proceso no se puede revertir!", response: "El producto esta en transición."},
            observe: {title: "¿Esta seguro de cambiar el estado a observado?", response: "Se cambio el estado a observado"},
            inProvider: {title: "¿Esta seguro de cambiar el estado?", response: "Se cambio el estado a en proveedor"},
        };

        var reasonsMsg = {
            3: "Reservado",
            4: "Observado",
            5: "Transición",
            6: "Devolucion Proveedor"
        };

        var msg;

        $scope.situations = [
            {id: 1, name:'Producto dañado' },
            {id: 2, name:'Producto no esta en el almacen' }
        ];

        $scope.transitions = [
            {id: 1, name:'Producto esta en transición' },
            {id: 2, name:'Otros' }
        ];

        /*
         |  END
         */
        util.liPage('productsJVE');

        var actions = [                            
                            ['movimientos','movements','bgm-blue'],
                            ['reservar','reserve','bgm-purple'],
                            ['observar','observe','bgm-lime'],
                            ['transición','transition','bgm-orange']
                        ];

        var status = {
                    0 : ['salida','btn-danger', false],
                    1 : ['disponible', 'btn-success', false],
                    2 : ['vendido', 'bgm-teal', false],
                    3 : ['reservado', 'bgm-purple'],
                    4 : ['observado', 'bgm-lime'],
                    5 : ['transición', 'bgm-orange'],
                    6 : ['en proveedor', 'bgm-red']
                };

        var statusForSale = {
                    0 : ['normal', 'btn-success', false],
                    1 : ['liquidacion', 'btn-info', false]
                };


        $scope.tableConfig 	= 	{
            columns :	[
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
                {"sTitle": "Accion", "bSortable" : false, "bSearchable": false, "sWidth" : "90px"}
            ],
            buttons :
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'productStatusDetail', column: 'status', render: status},
                            { name: 'statusForSale', column: 'liquidation', render: statusForSale},
                        ]
                    },
                    {
                        type: 'actions',
                        list: [
                            { name: 'actions', render: actions}
                        ]
                    }
                ],
            data  	: 	['cod','name','provider','size','color','types','statusForSale','price_real','precio','productStatusDetail','actions'],
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
            closeOnConfirm: true,
            html: true
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
            petition.get('api/auxproduct/get/movements/'+id)
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

        $scope.movementStatus = function(movement){
            var info = {
                'Retornado': ['Retornado','bgm-red'],
                'Vendido': ['Vendido','bgm-teal'],
                'salida': ['Salida','bgm-deeppurple'],
                'reprogramado': ['reprogramado','bgm-purple']
            };
            if(movement.status == 'salida'){
                if(movement.situation == 'reprogramado')
                    return `<a class="btn btn-xs disabled ${info.reprogramado[1]}">${info.reprogramado[0]}</a>`;
            }
            return `<a class="btn btn-xs disabled ${info[movement.status][1]}">${info[movement.status][0]}</a>`;
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

        $scope.reserve = function(i){
            msg = messages.reserve;
            listReasons(3, i);
            util.modal();
        };

        $scope.observe = function (i){
           msg = messages.observe;
           listReasons(4, i);
           util.modal();
        };

        $scope.inProvider = function (i) {
           msg = messages.inProvider;
           listReasons(6, i);
           util.modal();
        };

        $scope.transition = function (i){
            msg = messages.transition;
            listReasons(5, i);
            util.modal();
        };

        $scope.cancel = function(){
          util.modalClose();
        };


        /*
         *
         * Helper para filtro
         *
         */

        $scope.typesList = function(){
            petition.get('api/auxproduct/filter/get/types')
                .then(function(data){
                    $scope.types = data.types;
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.providerList = function(){
            petition.get('api/auxproduct/filter/get/providers')
                .then(function(data){
                    $scope.providers = data.providers;
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.productList = function(){
            petition.get('api/auxproduct/filter/get/products')
                .then(function(data){
                    $scope.products = data.products;
                    $scope.colors = data.colors;
                    $scope.sizes = data.sizes;
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.searchList = function(dataSearch){
            $scope.updateList = true;
            if(dataSearch.status_sale === "")dataSearch.status_sale = null;
            petition.get('api/auxproduct/filter/get/search', {params: dataSearch})
                .then(function(data){
                    $scope.tableData = data.products;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        function resetProduct(){
            $scope.products = [];
            $scope.colors = [];
            $scope.sizes = [];

            $scope.product = {};
            $scope.data.product = null;
            $scope.data.color = null;
            $scope.data.size = null;
        }

        function resetColorSize(){
            $scope.colors = [];
            $scope.sizes = [];

            $scope.data.color = null;
            $scope.data.size = null;
        }

        /*
         *  END
         */ 

        $scope.resetFilter = function(){
            return void($scope.data = {});
        };

        $scope.$watch("data", function(nValue){
           if(Object.keys(nValue).lenght){
               return void($scope.btnDisable = true);
           }

           for (var i in nValue) {
               if(nValue[i]){
                   return void($scope.btnDisable = false);
               }
           }
           return void($scope.btnDisable = true);
        }, true);

        function listReasons(id, i){
            var product = $scope.tableData[i];
            if(product.status != 1 && product.status != 2){
                $scope.stsCase = true;
            } else {
                $scope.stsCase = false;
            }

            $scope.product_i = i;
            $scope.reasons = [];
            petition.get('api/auxproduct/get/reasons/' + parseInt(id))
                .then(function(response){
                    $scope.reasons = response;
                },function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message || "Error inesperado =(");
                });
        }

        $scope.confirmChangeStatus = function(reason_i, product_i){
            var data = {};
            var product = $scope.tableData[product_i];
            var reason = $scope.reasons[reason_i];

            data.reason_id = reason.id;
            data.response = msg.response;

            alertConfig.title = msg.title;
            alertConfig.text = confirmTemplate(reason.description, product);

            sweetAlert(alertConfig, function () {
                petition.post('api/auxproduct/' + product.id + '/status/change', data)
                    .then(function (response) {
                       util.modalClose('Modal');
                       toastr.success(response.message);
                       $scope.searchList($scope.data);
                    }, function (error) {
                        toastr.error('Huy Huy dice: ' + error.data.message || "Error inesperado =(");
                    });
            });
        };

        $scope.confirmRestoreStatus = function (reason_i, product_i){
            var product = $scope.tableData[product_i];
            var reason = reasonsMsg[product.status];

            alertConfig.title = "¿Esta seguro de restaurar el estado del producto, estado actual sera resuelto?";
            alertConfig.text = confirmTemplate(reason, product);

            sweetAlert(alertConfig, function () {
                petition.post('api/auxproduct/' + product.id + '/status/change')
                    .then(function (response) {
                       util.modalClose('Modal');
                       toastr.success(response.message);
                       $scope.searchList($scope.data);
                    }, function (error) {
                        toastr.error('Huy Huy dice: ' + error.data.message || "Error inesperado =(");
                    });
            });
        };

        function confirmTemplate(reason, product) {
            var template = `<table class="table table-bordered w-100 table-attr text-center">
                                <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Talla</th>
                                    <th>Color</th>
                                    <th>Motivo</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>${product.cod}</td>
                                    <td>${product.name}</td>
                                    <td>${product.size}</td>
                                    <td>${product.color}</td>
                                    <td>${reason}</td>
                                </tr>
                                </tbody>
                            </table>
                            </div>`;

            return template;
        }

        $scope.productStatusDetail = function(i){
            var product = $scope.tableData[i];
            petition.post('api/auxproduct/' + product.id + '/status/detail')
                .then(function (response) {
                    $scope.statusDetail = response;
                    util.modal('ModalDetail');
                }, function (error) {
                    toastr.error('Huy Huy dice: ' + error.data.message || "Error inesperado =(");
                });
        };

        angular.element(document).ready(function(){

            $scope.typesList();
            $scope.providerList();
            $scope.productList();
        });
    }]);
