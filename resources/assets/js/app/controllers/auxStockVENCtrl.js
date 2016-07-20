angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('Stock Ventas', {
                url: '/Stock-general-de-productos-ventas',
                template: 
                `<div class="card">
                    <div class="card-header bgm-blue">
                        <h2>Stock general de productos</h2>
                        <button ng-disabled="updateList" class="btn bgm-green btn-float waves-effect btnLista" ng-click="list()"
                                data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Pulse para actualizar los registros"
                                title="" data-original-title="Actualizar"><i class="md md-sync"></i></button>
                    </div>
                    <div class="card-header bgm-lightblue">
                       <div ng-form="dates" class="row report-date">

                            <h2 class="col-xs-5 col-sm-1 text-right m-t-20">Tipo</h2>
                            <select class="col-xs-7 col-sm-2 m-t-20" ng-options="type.id as type.name for type in types" ng-model="data.type">
                               <option value="" selected="selected">Seleccione</option>
                            </select>

                            <h2 class="col-xs-5 col-sm-1 text-right  m-t-20">Proveedor</h2>
                            <select class="col-xs-7 col-sm-2 m-t-20" ng-change="productList(provider)" ng-options="provider.id as provider.name for provider in providers" ng-model="provider.provider_id">
                               <option value="" selected="selected">Seleccione</option>
                            </select>

                            <h2 class="col-xs-5 col-sm-1 text-right m-t-20">Venta</h2>
                            <select class="col-xs-7 col-sm-2 m-t-20" ng-model="data.status_sale">
                               <option value="" selected="selected">Seleccione</option>
                               <option value="0" selected="selected">Normal</option>
                               <option value="1" selected="selected">Liquidacion</option>
                            </select>

                            <div class="col-xs-12 col-sm-3 m-t-20" style="height:30px;"></div>

                            <h2 class="col-xs-5 col-sm-1 m-t-20 text-right">Producto</h2>
                            <select class="col-xs-7 col-sm-2 m-t-20" ng-change="productList(product)" ng-options="product.name as product.name for product in products" ng-model="product.name">
                               <option value=""  selected="selected">Seleccione</option>
                            </select>

                            <h2 class="col-xs-5 col-sm-1 m-t-20 text-right">Talla</h2>
                            <select class="col-xs-7 col-sm-2 m-t-20" ng-options="size.id as size.name for size in sizes" ng-model="data.size">
                               <option value=""  selected="selected">Seleccione</option>
                            </select>

                            <h2 class="col-xs-5 col-sm-1 m-t-20 text-right">Color</h2>
                            <select class="col-xs-7 col-sm-2 m-t-20" ng-options="color.id as color.name for color in colors" ng-model="data.color">
                               <option value="" selected="selected">Seleccione</option>
                            </select>

                            <div class="col-xs-offset-0 col-xs-12 col-sm-offset-1 col-sm-2 p-0 m-t-20">
                                <input type="button" ng-disabled="!dates.$valid" ng-click="searchList(data)" class="btn btn-block bgm-indigo" value="buscar">
                            </div>
                       </div>
                    </div>
                    <div class="card-body card-padding table-responsive">
                        <div class="col-sm-12">
                            <table id="table" class="table table-bordered table-striped w-100" style="text-align:center;"></table>
                        </div><br>
                    </div><br>
                </div>`,
                controller : 'auxStockVENCtrl'
            });
    }])
    .controller('auxStockVENCtrl',["$scope", "$compile", "$state", "$log", "util", "petition", "toformData", "toastr",
            function($scope, $compile, $state, $log, util, petition, toformData, toastr){

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

        util.liPage('stockVEN');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Modelo", "bSortable" : true},
                {"sTitle": "Proveedor", "bSortable" : true},
                {"sTitle": "Tipo", "bSortable" : true},
                {"sTitle": "Color", "bSortable" : true},
                {"sTitle": "Talla", "bSortable" : true},
                {"sTitle": "Precio", "bSortable" : true, "sWidth": "80px"},
                {"sTitle": "Stock", "bSortable" : true, "sWidth": "80px"}
            ],
            data : 	['name','provider.name','typesList','color.name','size.name','price_final','cantP'],
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxproduct/get/stockProd')
                .then(function(data){
                    $scope.tableData = data.stock;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    toastr.error('Huy Huy dice: ' + (error.data.message || "Mordi lo cables =("));
                    $scope.updateList = false;
                });
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

        $scope.productList = function(s){
            s || (s = {});

            if (s.name){
                $scope.data.product = s.name;
                resetColorSize();
            } else if (s.provider_id){
                $scope.data.provider_id = s.provider_id;
                resetProduct();
            } else {
                resetProduct();
                $scope.provider.provider_id = null;
                $scope.data.provider_id = null;
            } 

            petition.get(`api/auxproduct/filter/get/products`, {params: s})
                .then(function(data){
                    if(data.products){
                        $scope.products = data.products;
                    } else {
                        $scope.colors = data.colors;
                        $scope.sizes = data.sizes;
                    }
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        }

        $scope.searchList = function(dataSearch){
            $scope.updateList = true;
            if(dataSearch.status_sale === "")dataSearch.status_sale = null;
            petition.get(`api/auxproduct/filter/get/search/stock`, {params: dataSearch})
                .then(function(data){
                    $scope.tableData = data.stock;
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
            $scope.list();

            $scope.typesList();
            $scope.providerList();
            $scope.productList();
        });
    }]);