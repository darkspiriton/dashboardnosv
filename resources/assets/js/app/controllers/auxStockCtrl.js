angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('Stock', {
                url: '/Stock-general-de-productos',
                templateUrl: 'app/partials/auxStock.html',
                controller : 'auxStockCtrl'
            });
    }])
    .controller('auxStockCtrl',["$scope", "$compile", "$state", "$log", "util", "petition", "toformData", "toastr",
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

        util.liPage('stock');

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

        var resumeConfig = {
            columns :   [
                {"sTitle": "Creacion", "bSortable" : true},
                {"sTitle": "Proveedor", "bSortable" : true},
                {"sTitle": "Tipo", "bSortable" : true},
                {"sTitle": "Modelo", "bSortable" : true},
                {"sTitle": "Stock", "bSortable" : true},
                {"sTitle": "P. Proveedor", "bSortable" : true},
                {"sTitle": "Utilidad", "bSortable" : true},
                {"sTitle": "P. Final", "bSortable" : true}
            ],
            data    :   ['create','provider.name','typesList','name','cantP','cost_provider','utility','price_final']
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxproduct/get/stockProd')
                .then(function(data){
                    $scope.tableData = data.stock;
                    $scope.tableData2 = data.resume;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $('#stockResume').AJQtable2('view2', $scope, $compile, data.resume, resumeConfig);
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
            petition.get('api/auxproduct/filter/get/search/stock', {params: dataSearch})
                .then(function(data){
                    $scope.tableData = data.stock;
                    $scope.tableData2 = [];
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

        angular.element(document).ready(function(){
            $scope.product = angular.copy($scope.productClear);
            // $scope.list();

            $scope.typesList();
            $scope.providerList();
            $scope.productList();
        });
    }]);
