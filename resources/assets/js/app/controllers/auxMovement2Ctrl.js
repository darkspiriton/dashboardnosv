angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('Movimientos2', {
                url: '/Generar-retorno-y-ventas',
                templateUrl: 'app/partials/auxMovement2.html',
                controller : 'auxMovement2Ctrl'
            });
    }])
    .controller('auxMovement2Ctrl', ["$scope", "$compile", "$state", "$log", "util", "petition","toastr",
        function($scope, $compile, $state, $log, util, petition, toastr){

        util.liPage('movimientos2');

        var s1 = {
            0: ['Normal','bgm-green',false],
            1: ['Liquidacion','btn-info',false],
            'fail': ['otros','bgm-red',false]
        };

        var a1 = [
                    ['Descuento', 'discountEdit' ,'bgm-orange'],
                    ['Retornado', 'prdReturn' ,'bgm-teal'],
                    ['Vendido', 'prdSale' ,'bgm-blue'],
                    ['Reprogramar', 'reprogramar' ,'bgm-purple'],
                ];

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha de creación", "bSortable" : true, "sWidth": "90px"},
                {"sTitle": "Código de Pedido", "bSortable" : true, "sWidth": "90px"},
                {"sTitle": "Vendedor(a)", "bSortable" : true},
                {"sTitle": "Fecha de Pedido", "bSortable" : true, "sWidth": "90px"},                
                {"sTitle": "Fecha de salida", "bSortable" : true, "sWidth": "90px"},
                {"sTitle": "Codigo", "bSortable" : true},
                {"sTitle": "Producto", "bSortable" : true},
                {"sTitle": "Talla", "bSortable" : true},
                {"sTitle": "Color", "bSortable" : true},
                {"sTitle": "Precio  (S/)", "bSortable" : true},
                {"sTitle": "Descuento (S/)", "bSortable" : true},
                {"sTitle": "Precio Final (S/)", "bSortable" : true},
                {"sTitle": "Venta", "bSortable" : true, "sWidth": "80px"},
                {"sTitle": "Acción" , "bSearchable": false , "sWidth": "90px"}
            ],
            buttons :
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'status_product', column: 'liquidation', render: s1},
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
            data  	: 	['movement.created_at','movement.cod_order','seller','movement.date_request','movement.date_shipment',
                            'cod','name','size.name','color.name','price','movement.discount','pricefinal','status_product','actions'
                        ],
        };

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

        $scope.situations = [
            {id: 1, name:'No le gusto' },
            {id: 2, name:'La foto no es igual al producto' },
            {id: 3, name:'Producto dañado' },
            {id: 4, name:'No se encontro al cliente' },
            {id: 5, name:'No es la talla' },
            {id: 6, name:'No se encontro el código' },
            {id: 7, name:'No llegamos al cliente' },
            {id: 8, name:'Cliente cancelo su pedido'},
            {id: 9, name:'Retorno-Cambio'}
        ];
        $scope.prdDelete = function(i){
            alertConfig.title = '¿Todo es correcto?';
            alertConfig.text=`<table class="table table-bordered w-100 table-attr text-center">
                                        <thead>
                                        <tr>
                                            <th>Cod</th>
                                            <th>Producto</th>
                                            <th>Talla</th>
                                            <th>Color</th>
                                            <th>Precio</th>
                                            <th>Descuento</th>
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
                    petition.delete('api/auxmovement/' +  $scope.tableData[i].movement_id)
                        .then(function (data) {
                            toastr.success(data.message);
                            $scope.formSubmit = false;
                            $scope.list();
                        }, function (error) {
                            toastr.error('Uyuyuy dice: ' + error.data.message);
                            $scope.formSubmit = false;
                        });
                });
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxmovement/get/movement')
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
                            toastr.error('Uyuyuy dice: ' + error.data.message);
                            $scope.formSubmit = false;
                        });
                });
        };

        $scope.prdReturn = function(i){
            $scope.product.id = $scope.tableData[i].product_id;
            $scope.product.cod = $scope.tableData[i].cod;
            $scope.product.name = $scope.tableData[i].name;
            $scope.product.size = $scope.tableData[i].size.name;
            $scope.product.color = $scope.tableData[i].color.name;
            $scope.product.situation = null;
            util.modal();
        };

        $scope.submit = function () {
            alertConfig.title = '¿Todo es correcto?';
            alertConfig.text=`<table class="table table-bordered w-100 table-attr text-center">
                                        <thead>
                                        <tr>
                                            <th>Cod</th>
                                            <th>Producto</th>
                                            <th>Talla</th>
                                            <th>Color</th>
                                            <th>Motivo</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <th>${$scope.product.cod}</th>
                                            <th>${$scope.product.name}</th>
                                            <td>${$scope.product.size}</td>
                                            <td>${$scope.product.color}</td>
                                            <td>${( function(){
                                                var situation = "";                                                
                                                switch ($scope.product.situation){
                                                    case 1:
                                                        situation="No le gusto";
                                                        break;
                                                    case 2:
                                                        situation="La foto no es igual al producto";
                                                        break;
                                                    case 3:
                                                        situation="Producto dañado";
                                                        break;
                                                    case 4:
                                                        situation="No se encontro cliente";
                                                        break;
                                                    case 5:
                                                        situation="No es la talla";
                                                        break;
                                                    case 6:
                                                        situation="No se encontro el código";
                                                        break;
                                                    case 7:
                                                        situation="No llegamos al cliente";
                                                        break;
                                                    case 8:
                                                        situation="Cliente cancelo su pedido";
                                                        break;
                                                    case 9:
                                                        situation="Retorno-Cambio";
                                                        break;
                                                }
                                                return situation; })()}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>`;
            swal(alertConfig,
                function () {
                    petition.post('api/auxmovement', $scope.product)
                        .then(function (data) {
                            toastr.success(data.message);
                            $scope.formSubmit = false;
                            $scope.list();
                            util.modalClose();
                        }, function (error) {
                            toastr.error('Uyuyuy dice: ' + error.data.message);
                            $scope.formSubmit = false;
                        });
                });
        };

        $scope.cancel = function(){
          util.modalClose();
        };


        /**
         *  Reprogramar salida de producto
         *  @return movement
         */

        $scope.prdProgram = function(){
            alertConfig.title = '¿Todo es correcto?';
            alertConfig.text=`<table class="table table-bordered w-100 table-attr text-center">
                                        <thead>
                                        <tr>
                                            <th>Cod</th>
                                            <th>Producto</th>
                                            <th>Talla</th>
                                            <th>Color</th>
                                            <th>Fecha salida</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>${$scope.tableData[$scope.index].cod}</th>
                                                <th>${$scope.tableData[$scope.index].name}</th>
                                                <td>${$scope.tableData[$scope.index].size.name}</td>
                                                <td>${$scope.tableData[$scope.index].color.name}</td>
                                                <td>${util.setDate($scope.programDate)}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>`;
            swal(alertConfig,
                function () {
                    var movement_id = $scope.tableData[$scope.index].movement.id;
                    petition.put('api/auxmovement/' + movement_id, { date:$scope.programDate})
                        .then(function (data) {
                            toastr.success(data.message);
                            $scope.formSubmit = false;
                            $scope.list();
                            util.modalClose('reProgram');
                        }, function (error) {
                            toastr.error('Uyuyuy dice: ' + error.data.message);
                            $scope.formSubmit = false;
                        });
                });
        };
        
        $scope.reprogramar = function (i){
            $scope.index = i;
            $scope.programDate = null;
            util.modal("reProgram");
        };

        /**
         *  Metodo para despacho de producto por fecha.
         *  Descarga de hoja de despacho por fecha
         */

        $scope.filter = function(){
            petition.get('api/auxmovement/get/dispatch', {params: {date: $scope.dispatch}})
                .then(function(data){
                    $('#dispatch').AJQtable2('view2', $scope, $compile, data.movements, tableDispacth);
                }, function(error){
                    toastr.error('Huy Huy: ' + error.data.message);
                });
        };

        $scope.download = function(){
            $scope.downloadBtn = true;
            petition.post('api/auxmovement/get/dispatch/download', {date: $scope.dispatch}, {responseType:'arraybuffer'})
                .then(function(data){
                    var date = new Date().getTime();
                    var name = `${date}-ficha-de-despacho.pdf`;
                    var file = new Blob([data],{ type : 'application/pdf'});
                    saveAs(file, name);
                    $scope.downloadBtn = false;
                }, function(error){
                    toastr.error("El archivo es demasiado grande, no se pudo descargar");
                    $scope.downloadBtn = false;
                });
        };

        /**
         *  END
         */

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.product = angular.copy($scope.productClear);
            $scope.list();

            $scope.filter();
        });
    }]);
