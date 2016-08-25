angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('Stock Ventas', {
                url: '/Stock-general-de-productos-ventas',
                template: 
                `<div class="card">
                    <div class="card-header bgm-blue">
                        <h2>Stock general de productos</h2>
                    </div>
                    <div class="card-header bgm-lightblue">
                        <div ng-form="dates" class="row report-header">
                            <div class="col-xs-5 col-sm-1 m-t-20">
                                <h2 class="text-right">Tipo: </h2>
                            </div>
                            <div class="col-xs-7 col-sm-2 m-t-20 p-0">
                                <select ng-options="type.id as type.name for type in types" ng-model="data.type">
                                   <option value="" selected="selected">Seleccione</option>
                                </select>
                            </div>

                            <div class="col-xs-5 col-sm-1 m-t-20">
                                <h2 class="text-right">Proveedor: </h2>
                            </div>
                            <div class="col-xs-7 col-sm-2 m-t-20 p-0">
                                <select ng-options="provider.id as provider.name for provider in providers" ng-model="data.provider_id">
                                   <option value="" selected="selected">Seleccione</option>
                                </select>
                            </div>

                            <div class="col-xs-5 col-sm-1 m-t-20">
                                <h2 class="text-right">Venta: </h2>
                            </div>
                            <div class="col-xs-7 col-sm-2 m-t-20 p-0">
                                <select ng-model="data.status_sale">
                                   <option value="" selected="selected">Seleccione</option>
                                   <option value="0" selected="selected">Normal</option>
                                   <option value="1" selected="selected">Liquidacion</option>
                                </select>
                            </div>

                            <div class="clearfix"></div>

                            <div class="col-xs-5 col-sm-1 m-t-20">
                                <h2 class="text-right">Producto: </h2>
                            </div>
                            <div class="col-xs-7 col-sm-2 m-t-20 p-0">
                                <select class="bgm-white selectpicker" data-live-search="true" ng-options="product.name as product.name for product in products" ng-model="data.product">
                                   <option value=""  selected="selected">Seleccione</option>
                                </select>
                            </div>

                            <div class="col-xs-5 col-sm-1 m-t-20">
                                <h2 class="text-right">Talla: </h2>
                            </div>
                            <div class="col-xs-7 col-sm-2 m-t-20 p-0">
                                <select ng-options="size.id as size.name for size in sizes" ng-model="data.size">
                                   <option value=""  selected="selected">Seleccione</option>
                                </select>
                            </div>

                            <div class="col-xs-5 col-sm-1 m-t-20">
                                <h2 class="text-right">Color: </h2>
                            </div>
                            <div class="col-xs-7 col-sm-2 m-t-20 p-0">
                                <select class="bgm-white selectpicker" data-live-search="true" ng-options="color.id as color.name for color in colors" ng-model="data.color">
                                   <option value="" selected="selected">Seleccione</option>
                                </select>
                            </div>

                            <div class="col-xs-offset-0 col-xs-12 col-sm-offset-1 col-sm-2 p-0 m-t-20">
                                <input type="button" class="btn btn-block bgm-indigo" value="buscar" ng-disabled="btnDisable" ng-click="searchList(data)" >
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
         |  Init    DATA
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
            $scope.btnDisable = true;
            if(dataSearch.status_sale === "")dataSearch.status_sale = null;
            petition.get('api/auxproduct/filter/get/search/stock', {params: dataSearch})
                .then(function(data){
                    $scope.tableData = data.stock;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.btnDisable = false;
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                    $scope.btnDisable = false;
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

        angular.element(document).ready(function(){
            $scope.product = angular.copy($scope.productClear);

            $scope.typesList();
            $scope.providerList();
            $scope.productList();
        });
    }]);