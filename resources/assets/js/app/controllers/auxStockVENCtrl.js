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

        angular.element(document).ready(function(){
            $scope.product = angular.copy($scope.productClear);
            $scope.list();
        });
    }]);