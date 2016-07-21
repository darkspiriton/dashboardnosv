angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('Productos', {
                url: '/Adminitracion-de-productos',
                templateUrl: 'app/partials/auxProduct.html',
                controller : 'productsCtrl'
            });
    }])
    .controller('productsCtrl', ["$scope", "$compile", "$state", "$log", "util", "petition", "toastr",
        function($scope, $compile, $state, $log, util, petition, toastr){

        /*
         |
         |  Init
         |
         */

        $scope.provider = {};
        $scope.product = {};
        $scope.data = {};

        /*
         |  END
         */

        var actions = [
                        ['eliminar', 'delete' ,'bgm-red'],
                        ['editar', 'edit' ,'btn-primary'],
                        ['movimientos','movements','bgm-teal']
                    ];

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

        util.liPage('products');

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
                {"sTitle": "Accion", "bSortable" : false, "bSearchable": false, "sWidth" : "360px"}
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
                            { name: 'actions', render: actions}
                        ]
                    }
                ],
            data  	: 	['date','cod','name','provider','size','color','types','statusForSale','price_real','precio','status','actions'],
            configStatus : 'status'
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

        $scope.productClear = {
            name: null,
            cant: null,
            cod: null,
            provider_id: null,
            size_id : null,
            color_id: null,
            day: null,
            count: null,
            cost:null,
            uti:null,
            types: []
        };

        var newProvider =  {
            id: 0,
            name: '>>---> (Nuevo Proveedor) <---<<'
        };

        var newColor =  {
            id: 0,
            name: '>>---> (Nuevo Color) <---<<'
        };

        var newType =  {
            id: 0,
            name: '>>---> (Nuevo Tipo) <---<<'
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
                    toastr.error('Huy Huy dice: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.edit = function (i) {
            petition.get('api/auxproduct/' + $scope.tableData[i].id)
                .then(function (data) {
                    console.log(data);
                    productEdit(data.product);
                    $scope.productState = false;
                    util.muestraformulario();
                }, function (error) {
                    toastr.error('Uyuyuy dice: '+ error.data.message);
                });
        };

        function productEdit(data){
            for(var i in data.types){
                delete data.types[i].pivot;
            }
            data.cod = parseInt(data.cod);
            data.provider_id = parseInt(data.provider_id);
            data.color_id = parseInt(data.color_id);
            data.size_id = parseInt(data.size_id);
            data.cost = parseFloat(data.cost);
            data.uti = parseFloat(data.uti);
            data.alarm.day=parseInt(data.alarm.day);
            data.alarm.count=parseInt(data.alarm.count);
            $scope.product = data;
        }

        $scope.delete = function (i) {
            alertConfig.title = "¿Desea eliminar?";
            alertConfig.text = `<table class="table table-bordered w-100 table-attr text-center">
                                        <thead>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Nombre</th>
                                            <th>Talla</th>
                                            <th>Color</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>${$scope.tableData[i].cod}</td>
                                            <td>${$scope.tableData[i].name}</td>
                                            <td>${$scope.tableData[i].size}</td>
                                            <td>${$scope.tableData[i].color}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>`;
            sweetAlert(alertConfig, function () {
                petition.delete('api/auxproduct/' + $scope.tableData[i].id)
                    .then(function (data) {
                        $scope.list();
                        toastr.success(data.message);
                    }, function (error) {
                        toastr.error('Uyuyuy dice: ' + error.data.message);
                    });
            });
        };

        $scope.listProviders = function() {
            $scope.providers = [];
            petition.get('api/auxproviders')
                .then(function(data){
                    $scope.providers = data.providers;
                    $scope.providers.push(newProvider);
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.listSizes = function() {
            petition.get('api/sizes')
                .then(function(data){
                    $scope.sizes = data.sizes;
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.listColors = function() {
            petition.get('api/colors')
                .then(function(data){
                    $scope.colors = data.colors;
                    $scope.colors.push(newColor);
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.listTypes = function() {
            petition.get('api/auxproduct/get/type')
                .then(function(data){
                    $scope.types = data.types;
                    $scope.types.push(newType);
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.listCodes = function() {
            $scope.btnCods = true;
            $scope.codes = [];
            petition.get('api/auxproduct/get/code')
                .then(function(data){
                    $scope.codes = data.codes;
                     $scope.btnCods = false;
                }, function(error){
                     $scope.btnCods = false;
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.view = function( ind ){
            $scope.productGroupAttributes = [];
            $scope.productDetail = {};
            var id = $scope.tableData[ind].id;
            petition.get('api/product/group_attributes/' + id )
                .then(function(data){
                    $scope.productGroupAttributes = data.grp_attributes;
                    $scope.productDetail = angular.copy($scope.tableData[ind]);
                    util.modal();
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.submit = function () {
            alertConfig.title = '¿Todo es correcto?'
            swal(alertConfig ,
                function() {
                    var method = ( $scope.product.id ) ? 'PUT' : 'POST';
                    var url = ( method == 'PUT') ? util.baseUrl('api/auxproduct/' + $scope.product.id) : util.baseUrl('api/auxproduct');
                    var config = {
                        method: method,
                        url: url,
                        data: $scope.product
                    };
                    $scope.formSubmit=true;
                    petition.custom(config).then(function(data){
                        toastr.success(data.message);
                        $scope.formSubmit=false;
                        $scope.list();
                        $scope.listCodes();
                        util.ocultaformulario();
                    }, function(error){
                        toastr.error('Huy Huy dice: ' + error.data.message);
                        $scope.formSubmit=false;
                    })
                });

        };

        $scope.cancel = function () {
            $scope.product = angular.copy($scope.productClear);
            $scope.productState = true;
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.product = angular.copy($scope.productClear);
            $scope.productState = true;
            util.muestraformulario();
        };


        // Events

        $scope.eventProvider = function( v ){
            if ( v != 0)return true;
            $scope.product.provider_id = null;
            $scope.newFeature.tittle = 'Nuevo Proveedor';
            $scope.newFeature.label = 'Ingrese nombre de proveedor';
            $scope.newFeature.url = 'api/auxproduct/set/provider';
            $scope.newFeature.name = null;
            util.modal('feature');
        };

        $scope.eventColor = function( v ){
            if ( v != 0)return true;
            $scope.product.color_id = null;
            $scope.newFeature.tittle = 'Nuevo Color';
            $scope.newFeature.label = 'Ingrese nombre de color';
            $scope.newFeature.url = 'api/auxproduct/set/color';
            $scope.newFeature.name = null;
            util.modal('feature');
        };

        $scope.addType = function( i ){
            if ( $scope.types[i].id == 0) {
                $scope.typeSelect = null;
                $scope.newFeature.tittle = 'Nuevo Tipo de Producto';
                $scope.newFeature.label = 'Ingrese el tipo';
                $scope.newFeature.url = 'api/auxproduct/get/type';
                $scope.newFeature.name = null;
                util.modal('feature');
            }else{
                var count = 0;
                for(ind in  $scope.product.types){
                    if(angular.equals($scope.types[i],$scope.product.types[ind])){
                        count++;
                    }
                }

                if (count == 0){
                    $scope.product.types.push(angular.copy($scope.types[i]));
                }
                $scope.typeSelect = null;
            }

        };

        $scope.removeType = function(i){
            $scope.product.types.splice(i,1);
        };

        $scope.addFeature = function () {
            petition.post($scope.newFeature.url,
                {name: $scope.newFeature.name})
                .then(function(data){
                    toastr.success(data.message);
                    util.modalClose('feature');
                    $scope.listProviders();
                    $scope.listColors();
                    $scope.listTypes();
                }, function(error){
                    toastr.error('Uyuyuy dice: ' + error.data.message);
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
         *
         * Helper para filtro
         *
         */

        $scope.typesList = function(){
            petition.get(`api/auxproduct/filter/get/types`)
                .then(function(data){
                    $scope.types = data.types;
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        }

        $scope.providerList = function(){
            petition.get(`api/auxproduct/filter/get/providers`)
                .then(function(data){
                    $scope.providers = data.providers;
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        }

        $scope.productList = function(){
            petition.get(`api/auxproduct/filter/get/products`)
                .then(function(data){
                    $scope.products = data.products;
                    $scope.colors = data.colors;
                    $scope.sizes = data.sizes;
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        }

        $scope.searchList = function(dataSearch){
            $scope.updateList = true;
            if(dataSearch.status_sale === "")dataSearch.status_sale = null;
            petition.get(`api/auxproduct/filter/get/search`, {params: dataSearch})
                .then(function(data){
                    $scope.tableData = data.products;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                    $scope.updateList = false;
                });
        }

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
            $scope.product = angular.copy($scope.productClear);
            $scope.newFeature = {};
            $scope.list();
            $scope.listProviders();
            $scope.listSizes();
            $scope.listColors();
            $scope.listTypes();

            $scope.typesList();
            $scope.providerList();
            $scope.productList();
        });
    }]);