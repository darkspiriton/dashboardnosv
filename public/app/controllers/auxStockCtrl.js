angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Stock', {
                url: '/Stock-general-de-productos',
                template: `<div class="card" >
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
    })
    .controller('auxStockCtrl', function($scope, $compile, $state, $log, util, petition, toformData, toastr){

        util.liPage('stock');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Nombre", "bSortable" : true},
                {"sTitle": "Color", "bSortable" : true, "sWidth": "150px"},
                {"sTitle": "Talla", "bSortable" : true, "sWidth": "80px"},
                {"sTitle": "Stock", "bSortable" : true, "sWidth": "80px"},
                {"sTitle": "Accion", "bSortable" : true, "sWidth": "80px"}
            ],
            actions	:  	[
                ['actions', [
                                ['caracterisitica', 'view' ,'btn-primary']
                            ]
                ]
            ],
            data  	: 	['name','color','size','cantP','actions'],
            configStatus : 'status'
        };

        $scope.view = function(i){
            $scope.types = [];
            petition.get('api/auxproduct/get/stockProd/type/'+ $scope.tableData[i].id)
                .then(function(data){
                    $scope.types = data.types;
                    util.modal();
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxproduct/get/stockProd')
                .then(function(data){
                    $scope.tableData = data.stock;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.product = angular.copy($scope.productClear);
            $scope.list();
        });
    });