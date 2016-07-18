angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('Stock', {
                url: '/Stock-general-de-productos',
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
                </div>
                <div class="card">
                    <div class="card-header bgm-blue">
                        <h2>Stock general de productos</h2>
                        <button ng-disabled="updateList" class="btn bgm-green btn-float waves-effect btnLista" ng-click="list()"
                                data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Pulse para actualizar los registros"
                                title="" data-original-title="Actualizar"><i class="md md-sync"></i></button>
                    </div>
                    <div class="card-body card-padding table-responsive">
                        <div class="col-sm-12">
                            <table id="stockResume" class="table table-bordered table-striped w-100" style="text-align:center;"></table>
                        </div><br>
                    </div><br>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="Modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bgm-teal">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title c-white">Caracteristicas</h4>
                            </div>
                            <div class="modal-body card-padding">
                                <form class="form-horizontal" autocomplete="off" submit="return false;">
                                     <div class="form-group">
                                        <label class="col-sm-2 control-label">caracteristicas: </label>
                                        <div class="col-sm-10 m-t-15 imput-pad">
                                            <div class="fg-line">
                                                <div class="col-sm-10" ng-bind-html="types"></div>
                                            </div>
                                        </div>
                                    </div>
                
                                </form>
                            </div>
                        </div>
                    </div>
                </div>`,
                controller : 'auxStockCtrl'
            });
    }])
    .controller('auxStockCtrl',["$scope", "$compile", "$state", "$log", "util", "petition", "toformData", "toastr",
            function($scope, $compile, $state, $log, util, petition, toformData, toastr){

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
        }

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxproduct/get/stockProd')
                .then(function(data){
                    $scope.tableData = data.stock;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $('#stockResume').AJQtable2('view2', $scope, $compile, data.resume, resumeConfig);
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