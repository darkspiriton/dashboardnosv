angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('RequestProduct', {
                url: '/Pedidos-venta-productos',
                templateUrl: 'http://' + location.hostname + '/app/partials/requestProduct.html',
                controller : 'RequestProductCtrl'
            });
    }])
    .controller('RequestProductCtrl', ["$scope", "$compile", "$state", "$log", "util", "petition", "toformData", "toastr", "$auth",
        function($scope, $compile, $state, $log, util, petition, toformData, toastr, $auth){

        /**
            Declaracion de variables
        */

        $scope.product = {};

        /**
            END
        */

        util.liPage('RequestProduct');

        var s1 = {
            1: ['No Atendido','bgm-teal',false],
            2: ['Atendido','bgm-green',false],
            3: ['Rechazado','bgm-red',false]
        };

        var a1 = [
                    ['Cliente', 'prdClient' ,'bgm-blue'],
                    ['Fotos', 'prdPhoto' ,'bgm-orange'],
                    ['Cambiar Estado', 'prdUpdate' ,'bgm-green'],
                    ['Eliminar', 'prdDelete' ,'bgm-red'],
                ];

        $scope.tableConfig  =   {
            columns :   [
                {"sTitle": "Fecha de Creación", "bSortable" : true, "sWidth": "90px"},
                {"sTitle": "Producto", "bSortable" : true, "sWidth": "90px"},
                {"sTitle": "Descripción", "bSortable" : true, "sWidth": "90px"},
                {"sTitle": "Precio", "bSortable" : true, "sWidth": "90px"},
                {"sTitle": "Estado", "bSortable" : true},
                {"sTitle": "Acción" , "bSortable" : false, "bSearchable": false , "sWidth": "90px"}
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
                                            <td>${function(){
                                                switch($scope.tableData[i].status) {
                                                    case 1:
                                                        return "No Atendido";
                                                        
                                                    case 2:
                                                        return "Atendido";
                                                        
                                                    case 3:
                                                        return "Rechazado";
                                                        
                                                }
                                            }()}</td>                                             
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
            var userId;
            if ($scope.tableData[i].userStatus === 1){
                userId=$scope.tableData[i].user_request_id;
            }else if($scope.tableData[i].userStatus === 0){
                userId=$scope.tableData[i].user_id;
            }
            petition.get('api/requestproduct/user/get/'+userId , {params: {status:$scope.tableData[i].userStatus} })
                .then(function(data){
                    $scope.client = data.user;
                    console.log($scope.client);
                    util.modal("clientModal");
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.prdPhoto = function(i){
            var id=$scope.tableData[i].id;
            petition.get('api/requestproduct/'+id)
                .then(function(data){
                    $scope.product = data.product[0];
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

        $scope.submit = function(product){
            $scope.formSubmit = true;
            isAuthenticated();
            petition.post("api/requestproduct", toformData.dataFile(product), {headers: {'Content-Type': undefined}})
                .then(function(data){
                    toastr.success(data.message || "No se pudo comprobar estado de publicación");
                    $scope.list();
                    util.ocultaformulario();
                    $scope.formSubmit = false;
                }, function(error){
                    toastr.error(error.data.message || "Ocurrio un problema al intentar guardar su publicación");
                    $scope.formSubmit = false;
                });
        };

        $scope.cancel = function(){
          util.modalClose();
        };

        $scope.new = function(){
            $scope.product = {};
            $("#formproduct")[0].reset();
            util.muestraformulario();
        };

        function isAuthenticated(){
            if (!$auth.isAuthenticated()) {
                $scope.GuestUser = true;
            } else {
                $scope.product.token = $auth.getToken();
            }
        }

        angular.element(document).ready(function(){
            $scope.list();
            $scope.estados();
            isAuthenticated();
        });
    }]);
