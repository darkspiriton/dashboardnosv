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
                    3 : ['reservado', 'bgm-black', false],                    
                    4 : ['observado', 'bgm-black'],
                    5 : ['transición', 'bgm-black']
                };

        var statusForSale = {
                    0 : ['normal', 'btn-success', false],
                    1 : ['liquidacion', 'btn-info', false]
                };


        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha", "bSortable" : true, 'sWidth': '80px'},
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
                {"sTitle": "Accion", "bSortable" : false, "bSearchable": false, "sWidth" : "400px"}
            ],
            buttons :
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'obseveDetail', column: 'status', render: status},
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
            data  	: 	['date','cod','name','provider','size','color','types','statusForSale','price_real','precio','obseveDetail','actions'],
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

        $scope.list = function(s) {
            $scope.updateList = true;
            petition.get('api/auxproduct')
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

        /*
         * Helper para vista de detalle de movimientos
         *
         *  @params Int
         *  @return Object:String   Confirmation
         */

        $scope.reserve = function(i){
            var id = $scope.tableData[i].id;
            petition.put('api/auxproduct/reserve/'+id)
                .then(function(data){
                    $scope.searchList($scope.data);
                    toastr.success(data.message);
                }, function(error){
                    $scope.searchList($scope.data);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        /**
         *    Helper para observar un producto en el kardex
         *
         *    @param  Int  id
         *    @return  Object:String    Confirmation
         */
         $scope.observe = function (i){
            $scope.productId = $scope.tableData[i].id;
            $scope.productAux.cod = $scope.tableData[i].cod;
            $scope.productAux.name = $scope.tableData[i].name;
            $scope.productAux.size = $scope.tableData[i].size;
            $scope.productAux.color = $scope.tableData[i].color;
            $scope.productAux.situation = null;
            petition.get('api/auxproduct/observe/' +  $scope.productId)
                .then(function(data){
                    //Validar si el producto esta observado o no
                    $scope.status=data.status;
                    if($scope.status === false){
                        util.modal('Modal');                        
                        //Mostrar modal y poder elegir el motivo de la observacion
                        //Luego mostrar detalle de modificacion
                        //Luego mostrar detalle de confirmacion
                        //changeObserve(productAux,id);
                    }else if ($scope.status === true){
                        //Mostrar detalle de modificacion
                        //Luego mostrar detalle de confirmacion
                        $scope.changeObserve();
                    } else if ($scope.status === null){
                        toastr.error('Huy Huy dice: ' + data.message);
                    }
                },function(error){
                    $scope.searchList($scope.data);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });

         };

         /**{
                   *    Método de modificacion de estatus}
          *
          *    @param  Int  Id
          *    @return  Object:String    Confirmation
          */
         $scope.changeObserve = function (id) {
             alertConfig.title = "¿Desea cambiar el estado?";
             alertConfig.text = `<table class="table table-bordered w-100 table-attr text-center">
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
                                             <td>${$scope.productAux.cod}</td>
                                             <td>${$scope.productAux.name}</td>
                                             <td>${$scope.productAux.size}</td>
                                             <td>${$scope.productAux.color}</td>
                                             <td>${(function(){
                                                if ($scope.productAux.situation === null){
                                                    return 'Desactivar estado observado';
                                                }else{
                                                    return  $scope.productAux.situation;
                                                }
                                             })()}
                                            </td>
                                         </tr>
                                         </tbody>
                                     </table>
                                     </div>`;

             sweetAlert(alertConfig, function () {
                 petition.put('api/auxproduct/observe/update/'+ $scope.productId , {situation:$scope.productAux.situation} )
                     .then(function (data) {
                        util.modalClose();
                        toastr.success(data.message);
                        $scope.searchList($scope.data);
                     }, function (error) {
                         toastr.error('Uyuyuy dice: ' + error.data.message);
                     });
             });
         };
         /**
          *    Se obtiene el detalle de la observacion
          *
          *    @param  int  Id
          *    @return  String
          */
         $scope.obseveDetail=function(i){
            var id = $scope.tableData[i].id;
            petition.get('api/auxproduct/observe/detail/'+id)
                .then(function(data){
                    $scope.observe_detail = data.observe_detail;
                    util.modal('ModalDetail');
                },function(error){
                    $scope.searchList($scope.data);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
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

        /**
         *    Helper para transicion un producto en el kardex
         *
         *    @param  Int  id
         *    @return  Object:String    Confirmation
         */
         $scope.transition = function (i){
            $scope.productId = $scope.tableData[i].id;
            $scope.productAux.cod = $scope.tableData[i].cod;
            $scope.productAux.name = $scope.tableData[i].name;
            $scope.productAux.size = $scope.tableData[i].size;
            $scope.productAux.color = $scope.tableData[i].color;
            $scope.productAux.transition = null;
            petition.get('api/auxproduct/transition/' +  $scope.productId)
                .then(function(data){
                    //Validar si el producto esta en transición o no
                    $scope.status=data.status;
                    if($scope.status === false){
                        util.modal('Transition2');                        
                        //Mostrar modal y poder elegir el motivo de la observacion
                        //Luego mostrar detalle de modificacion
                        //Luego mostrar detalle de confirmacion
                        //changeObserve(productAux,id);
                    }else if ($scope.status === true){
                        //Mostrar detalle de modificacion
                        //Luego mostrar detalle de confirmacion
                        $scope.changeTransition();
                    } else if ($scope.status === null){
                        toastr.error('Huy Huy dice: ' + data.message);
                    }
                },function(error){
                    $scope.searchList($scope.data);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });

         };


         /**
          *    Método de modificación de estatus de transición
          *
          *    @param  Int  Id
          *    @return  Object:String    Confirmation
          */
         $scope.changeTransition = function (id) {
             alertConfig.title = "¡Cuidado este proceso no se puede revertir!";
             alertConfig.text = `<table class="table table-bordered w-100 table-attr text-center">
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
                                             <td>${$scope.productAux.cod}</td>
                                             <td>${$scope.productAux.name}</td>
                                             <td>${$scope.productAux.size}</td>
                                             <td>${$scope.productAux.color}</td>
                                             <td>${(function(){
                                                if ($scope.productAux.transition === null){
                                                    return 'Desactivar estado transición';
                                                }else{
                                                    return  $scope.productAux.transition;
                                                }
                                             })()}
                                            </td>
                                         </tr>
                                         </tbody>
                                     </table>
                                     </div>`;

             sweetAlert(alertConfig, function () {
                 petition.put('api/auxproduct/transition/update/'+ $scope.productId , {situation:$scope.productAux.transition} )
                     .then(function (data) {
                        util.modalClose('Transition2');
                        toastr.success(data.message);
                        $scope.searchList($scope.data);
                     }, function (error) {
                         toastr.error('Uyuyuy dice: ' + error.data.message);
                     });
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

        angular.element(document).ready(function(){
            // $scope.list();
            $scope.typesList();
            $scope.providerList();
            $scope.productList();
        });
    }]);
