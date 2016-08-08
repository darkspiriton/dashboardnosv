angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('RequestProduct', {
                url: '/Pedidos-venta-productos',
                templateUrl: 'app/partials/requestProduct.html',
                controller : 'RequestProductCtrl'
            });
    }])
    .controller('RequestProductCtrl', ["$scope", "$compile", "$state", "$log", "util", "petition", "toformData", "toastr",
        function($scope, $compile, $state, $log, util, petition, toformData, toastr){

        util.liPage('RequestProduct');

        var s1 = {
            1: ['No Atendido','bgm-teal',false],
            2: ['Atendido','bgm-green',false],
            3: ['Rechazado','bgm-red',false]
        };

        var a1 = [
                    ['Cliente', 'prdClient' ,'bgm-blue'],
                    ['Detalle', 'prdPhoto' ,'bgm-blue'],
                    ['Cambiar Estado', 'prdUpdate' ,'bgm-green'],
                    ['Eliminar', 'prdDelete' ,'bgm-red'],
                    // ['reprogramar', 'reprogramar' ,'bgm-purple']
                ];

        $scope.tableConfig  =   {
            columns :   [
                {"sTitle": "Fecha de Creación", "bSortable" : true, "sWidth": "90px"},
                {"sTitle": "Producto", "bSortable" : true, "sWidth": "90px"},
                {"sTitle": "Descripción", "bSortable" : true, "sWidth": "90px"},
                {"sTitle": "Precio", "bSortable" : true, "sWidth": "90px"},
                {"sTitle": "Estado", "bSortable" : true},
                // {"sTitle": "Fecha de Pedido", "bSortable" : true, "sWidth": "90px"},                
                // {"sTitle": "Fecha de salida", "bSortable" : true, "sWidth": "90px"},
                // {"sTitle": "Codigo", "bSortable" : true},
                // {"sTitle": "Producto", "bSortable" : true},
                // {"sTitle": "Talla", "bSortable" : true},
                // {"sTitle": "Color", "bSortable" : true},
                // {"sTitle": "Precio  (S/)", "bSortable" : false},
                // {"sTitle": "Descuento (S/)", "bSortable" : false},
                // {"sTitle": "Precio Final (S/)", "bSortable" : false},
                // {"sTitle": "Venta", "bSortable" : true, "sWidth": "80px"},
                {"sTitle": "Acción" , "bSortable" : false, "bSearchable": false , "sWidth": "420px"}
            ],
            buttons :
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'status', column: 'status', render: s1},
                        ]
                    },
                    {
                        type: 'actions',
                        list:  [
                            { name: 'actions', render: a1},
                        ]
                    },
                    {
                        type: 'custom',
                        list:  [
                            { name: 'seller', call_me: function($row){
                                if($row.movement.user !== null)return $row.movement.user.first_name; else return '';
                            }},
                        ]
                    }
                ],
            data    :   ['created_at','name','description','price','status','actions'],
            configStatus : 'liquidation'
        };


        $scope.status=null;

        /**
         *  Copia de tabla principal de salida de productos
         */
         var tableDispacth = {};
         tableDispacth.columns = angular.copy($scope.tableConfig.columns);
         tableDispacth.columns.splice(-2,2);
         tableDispacth.data = ["created_at","cod_order","seller_name","date_request","date_shipment",
                                 "product.cod","product.name","product.size.name","product.color.name",
                                 "price","discount","pricefinal"
                             ];

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

        $scope.productClear = {
            id: null,
            situation: null
        };



        $scope.list = function() {
            $scope.updateList = true;
            petition.get('/api/requestproduct')
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

        $scope.estados = function() {
            $scope.updateList = true;
            petition.get('/api/requestproduct/status/get')
                .then(function(data){
                    $scope.estados = data.estados;
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.changeStatus = function() {
            $scope.updateList = true;
            petition.get('/api/requestproduct/',{params: {status: $scope.status}})
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

        $scope.prdUpdate = function(i){
            var $id=$scope.tableData[i].id;
            alertConfig.title = '¿Todo es correcto?';
            alertConfig.text=`<table class="table table-bordered w-100 table-attr text-center">
                                        <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Descripcion</th>
                                            <th>Precio</th>
                                            <th>Nuevo Estado</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <th>${$scope.tableData[i].name}</th>
                                            <th>${$scope.tableData[i].description}</th>
                                            <td>${$scope.tableData[i].price}</td>
                                            <td>${function(){
                                                switch($scope.tableData[i].status) {
                                                    case 1:
                                                        return "Atendido";
                                                        
                                                    case 2:
                                                        return "Rechazado";
                                                        
                                                    case 3:
                                                        return "Sin Cambios";
                                                        
                                                }
                                            }()}</td>                                            
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>`;
            swal(alertConfig,
                function () {
                    petition.put('api/requestproduct/'+$id)
                        .then(function (data) {
                            toastr.success(data.message);
                            $scope.formSubmit = false;
                            $scope.list();
                        }, function (error) {
                            toastr.error('Huy Huy dice: ' + error.data.message);
                            $scope.formSubmit = false;
                        });
                });
        };

        $scope.prdDelete = function(i){
            var $id=$scope.tableData[i].id;
            alertConfig.title = '¿Todo es correcto?';
            alertConfig.text=`<table class="table table-bordered w-100 table-attr text-center">
                                        <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Descripcion</th>
                                            <th>Precio</th>
                                            <th>Estado</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <th>${$scope.tableData[i].name}</th>
                                            <th>${$scope.tableData[i].description}</th>
                                            <td>${$scope.tableData[i].price}</td>
                                            <td>${$scope.tableData[i].status}</td>                                            
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>`;
            swal(alertConfig,
                function () {
                    petition.delete('api/requestproduct/'+$id)
                        .then(function (data) {
                            toastr.success(data.message);
                            $scope.formSubmit = false;
                            $scope.list();
                        }, function (error) {
                            toastr.error('Huy Huy dice: ' + error.data.message);
                            $scope.formSubmit = false;
                        });
                });
        };

        

        $scope.prdClient = function(i) {
            // var $id=$scope.tableData[i].id;
            var userId;
            if ($scope.tableData[i].userStatus === 1){
                userId=$scope.tableData[i].user_request_id;
            }else if($scope.tableData[i].userStatus === 0){
                userId=$scope.tableData[i].user_id;
            }
            // $scope.updateList = true;
            petition.get('api/requestproduct/user/get/'+userId , {params: {status:$scope.tableData[i].userStatus} })
                .then(function(data){
                    $scope.client = data.user;
                    console.log($scope.client);
                    util.modal("clientModal");
                    // $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    // $scope.updateList = false;
                });
        };

        $scope.prdPhoto = function(i){
            var id=$scope.tableData[i].id;
            petition.get('api/requestproduct/'+id)
                .then(function(data){
                    $scope.product = data.product[0];
                    // console.log($scope.product[0]);
                    util.modal("Photo");
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.prdSale = function(i){
            alertConfig.title = '¿Todo es correcto?';
            alertConfig.text=`<table class="table table-bordered w-100 table-attr text-center">
                                        <thead>
                                        <tr>
                                            <th>Cod</th>
                                            <th>Producto</th>
                                            <th>Talla</th>
                                            <th>Color</th>
                                            <th>Precio</th>
                                            <th>Desc.</th>
                                            <th>Final</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <th>${$scope.tableData[i].cod}</th>
                                            <th>${$scope.tableData[i].name}</th>
                                            <td>${$scope.tableData[i].size.name}</td>
                                            <td>${$scope.tableData[i].color.name}</td>
                                            <td>${$scope.tableData[i].price}</td>
                                            <td>${$scope.tableData[i].movement.discount}</td>
                                            <td>${$scope.tableData[i].pricefinal}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>`;
            swal(alertConfig,
                function () {
                    petition.post('api/auxmovement/set/sale', {id: $scope.tableData[i].product_id})
                        .then(function (data) {
                            toastr.success(data.message);
                            $scope.formSubmit = false;
                            $scope.list();
                        }, function (error) {
                            toastr.error('Huy Huy dice: ' + error.data.message);
                            $scope.formSubmit = false;
                        });
                });
        };

        // $scope.prdReturn = function(i){
        //     $scope.product.id = $scope.tableData[i].product_id;
        //     $scope.product.cod = $scope.tableData[i].cod;
        //     $scope.product.name = $scope.tableData[i].name;
        //     $scope.product.size = $scope.tableData[i].size.name;
        //     $scope.product.color = $scope.tableData[i].color.name;
        //     $scope.product.situation = null;
        //     util.modal();
        // };

        // $scope.submit = function () {
        //     alertConfig.title = '¿Todo es correcto?';
        //     alertConfig.text=`<table class="table table-bordered w-100 table-attr text-center">
        //                                 <thead>
        //                                 <tr>
        //                                     <th>Cod</th>
        //                                     <th>Producto</th>
        //                                     <th>Talla</th>
        //                                     <th>Color</th>
        //                                     <th>Motivo</th>
        //                                 </tr>
        //                                 </thead>
        //                                 <tbody>
        //                                 <tr>
        //                                     <th>${$scope.product.cod}</th>
        //                                     <th>${$scope.product.name}</th>
        //                                     <td>${$scope.product.size}</td>
        //                                     <td>${$scope.product.color}</td>
        //                                     <td>${( function(){
        //                                         var situation = "";                                                
        //                                         switch ($scope.product.situation){
        //                                             case 1:
        //                                                 situation="No le gusto";
        //                                                 break;
        //                                             case 2:
        //                                                 situation="La foto no es igual al producto";
        //                                                 break;
        //                                             case 3:
        //                                                 situation="Producto dañado";
        //                                                 break;
        //                                             case 4:
        //                                                 situation="No se encontro cliente";
        //                                                 break;
        //                                             case 5:
        //                                                 situation="No es la talla";
        //                                                 break;
        //                                             case 6:
        //                                                 situation="No se encontro el código";
        //                                                 break;
        //                                             case 7:
        //                                                 situation="No llegamos al cliente";
        //                                                 break;
        //                                             case 8:
        //                                                 situation="Cliente cancelo su pedido";
        //                                                 break;
        //                                             case 9:
        //                                                 situation="Retorno-Cambio";
        //                                                 break;
        //                                         }
        //                                         return situation; })()}
        //                                     </td>
        //                                 </tr>
        //                                 </tbody>
        //                             </table>
        //                         </div>`;
        //     swal(alertConfig,
        //         function () {
        //             petition.post('api/auxmovement', $scope.product)
        //                 .then(function (data) {
        //                     toastr.success(data.message);
        //                     $scope.formSubmit = false;
        //                     $scope.list();
        //                     util.modalClose();
        //                 }, function (error) {
        //                     toastr.error('Huy Huy dice: ' + error.data.message);
        //                     $scope.formSubmit = false;
        //                 });
        //         });
        // };

        $scope.cancel = function(){
          util.modalClose();
        };

        /**
         *  Reprogramar salida de producto
         *  @return movement
         */
        // $scope.prdProgram = function(){
        //     alertConfig.title = '¿Todo es correcto?';
        //     alertConfig.text=`<table class="table table-bordered w-100 table-attr text-center">
        //                                 <thead>

        //                                 <tr>
        //                                     <th>Cod</th>
        //                                     <th>Producto</th>
        //                                     <th>Talla</th>
        //                                     <th>Color</th>
        //                                     <th>Fecha salida</th>
        //                                 </tr>
        //                                 </thead>
        //                                 <tbody>
        //                                     <tr>
        //                                         <th>${$scope.tableData[$scope.index].cod}</th>
        //                                         <th>${$scope.tableData[$scope.index].name}</th>
        //                                         <td>${$scope.tableData[$scope.index].size.name}</td>
        //                                         <td>${$scope.tableData[$scope.index].color.name}</td>
        //                                         <td>${util.setDate($scope.programDate)}</td>
        //                                     </tr>
        //                                 </tbody>
        //                             </table>
        //                         </div>`;
        //     swal(alertConfig,
        //         function () {
        //             var movement_id = $scope.tableData[$scope.index].movement.id;
        //             petition.put('api/auxmovement/' + movement_id, { date:$scope.programDate})
        //                 .then(function (data) {
        //                     toastr.success(data.message);
        //                     $scope.formSubmit = false;
        //                     $scope.list();
        //                     util.modalClose('reProgram');
        //                 }, function (error) {
        //                     toastr.error('Huy Huy dice: ' + error.data.message);
        //                     $scope.formSubmit = false;
        //                 });
        //         });
        // };
        
        // $scope.reprogramar = function (i){
        //     $scope.index = i;
        //     $scope.programDate = null;
        //     util.modal("reProgram");
        // };

        /**
         *  Metodo para despacho de producto por fecha.
         *  Descarga de hoja de despacho por fecha
         */

        // $scope.filter = function(){
        //     petition.get('api/auxmovement/get/dispatch', {params: {date: $scope.dispatch}})
        //         .then(function(data){
        //             $('#dispatch').AJQtable2('view2', $scope, $compile, data.movements, tableDispacth);
        //         }, function(error){
        //             toastr.error('Huy Huy: ' + error.data.message);
        //         });
        // };

        // $scope.download = function(){
        //     $scope.downloadBtn = true;
        //     petition.post('api/auxmovement/get/dispatch/download', {date: $scope.dispatch}, {responseType:'arraybuffer'})
        //         .then(function(data){
        //             var date = new Date().getTime();
        //             var name = `${date}-ficha-de-despacho.pdf`;
        //             var file = new Blob([data],{ type : 'application/pdf'});
        //             saveAs(file, name);
        //             $scope.downloadBtn = false;
        //         }, function(error){
        //             toastr.error("El archivo es demasiado grande, no se pudo descargar");
        //             $scope.downloadBtn = false;
        //         });
        // };

        // $scope.discountEdit = function(i){
        //     $scope.idDiscount = $scope.tableData[i].movement.id;
        //     $scope.priceAux = $scope.tableData[i].price;
        //     $scope.priceAuxFinal= $scope.tableData[i].price-$scope.tableData[i].movement.discount;
        //     $scope.priceAuxFinal = Math.round($scope.priceAuxFinal*100)/100;
        //     $scope.priceAuxNew = $scope.priceAux;
        //     $scope.discountAux = parseFloat($scope.tableData[i].movement.discount);
        //     $scope.discountNew = null;
        //     util.modal('discountUpdate'); 

        //     $scope.product.cod=$scope.tableData[i].cod;
        //     $scope.product.name=$scope.tableData[i].name;
        //     $scope.product.size=$scope.tableData[i].size.name;
        //     $scope.product.color=$scope.tableData[i].color.name;   
        // };

        // function discountEmpyte(){
        //    $scope.idDiscount = null;
        //    $scope.priceAux = null;
        //    $scope.priceAuxFinal= null;
        //    $scope.priceAuxNew = null;
        //    $scope.discountAux = null;
        //    $scope.discountNew = null; 
        // }
        
        // $scope.discountUpdate = function(){
        //     if ($scope.discountNew === null){
        //         toastr.error('Huy Huy dice: Ingrese un nuevo descuento para actualizar el movimiento');
        //     }else if($scope.discountNew>$scope.priceAux){
        //         toastr.error('Huy Huy dice: El descuento no puede ser mayor que el Precio del Producto');
        //     } else{               
        //         alertConfig.title = '¿Todo es correcto?';
        //         alertConfig.text=`<table class="table table-bordered w-100 table-attr text-center">
        //                                     <thead>

        //                                     <tr>
        //                                         <th>Cod</th>
        //                                         <th>Producto</th>
        //                                         <th>Talla</th>
        //                                         <th>Color</th>
        //                                         <th>Descuento</th>
        //                                         <th>Precio Final</th>
        //                                     </tr>
        //                                     </thead>
        //                                     <tbody>
        //                                         <tr>
        //                                             <th>${$scope.product.cod}</th>
        //                                             <th>${$scope.product.name}</th>
        //                                             <td>${$scope.product.size}</td>
        //                                             <td>${$scope.product.color}</td>
        //                                             <td>${$scope.discountNew}</td>
        //                                             <td>${$scope.priceAuxNew}</td>
        //                                         </tr>
        //                                     </tbody>
        //                                 </table>
        //                             </div>`;
        //         swal(alertConfig,
        //             function () {
        //                 petition.put('api/auxmovement/get/movement/discount/'+$scope.idDiscount , {discount: $scope.discountNew})
        //                     .then(function(data){                                   
        //                         $scope.formSubmit = false;
        //                         discountEmpyte();
        //                         $scope.list();
        //                         util.modalClose('discountUpdate');
        //                         toastr.success('Se Actualizo correctamente el descuento');
        //                     }, function(error){
        //                         toastr.error('Huy Huy dice: ' + error.data.message);
        //                     });
        //             });
        //     }
        // };

        // $scope.validatePrice = function(val){
        //     var max_val = $scope.priceAux;
        //     if(typeof val !== 'undefined'){
        //         if(val <= max_val){
        //             $scope.discountValid = false;
        //             $scope.priceAuxNew = Math.round(($scope.priceAux - $scope.discountNew)*100)/100;        
        //         }
        //         else{
        //             $scope.discountValid = true;                    
        //             $scope.priceAuxNew = $scope.priceAux;         
        //         }
        //     }else{
        //         $scope.priceAuxNew = $scope.priceAux;      
        //     }
        //     // if ($scope.discountNew !== null){
        //     //     $scope.priceAuxNew = $scope.priceAux - $scope.discountNew;
        //     // }else if($scope.discountNew>$scope.priceAux){
        //     //     $scope.discountNew=0;
        //     // }else if($scope.discountNew === null){
        //     //     $scope.priceAuxNew = $scope.priceAux;
        //     // }
           
        // };

        // /**
        //  *  END
        //  */

        angular.element(document).ready(function(){
            $scope.product = angular.copy($scope.productClear);
            $scope.list();
            $scope.estados();

            // $scope.filter();
        });
    }]);
