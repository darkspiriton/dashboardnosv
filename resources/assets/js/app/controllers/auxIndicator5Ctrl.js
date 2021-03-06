angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('Indicator5', {
                url: '/stock-general-de-productos-por-proveedor',
                template: '<div class="card" >'+
                '    <div class="card-header bgm-blue">'+
                '        <h2>Indicador de movimientos por producto por proveedor</h2>'+
                '        <button ng-disabled="updateList" class="btn bgm-green btn-float waves-effect btnLista" ng-click="list()"'+
                '                data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Pulse para actualizar los registros"'+
                '                title="" data-original-title="Actualizar"><i class="md md-sync"></i></button>'+
                '    </div>'+
                '    <div class="card-body card-padding table-responsive">'+
                '        <div class="col-sm-12">'+
                '            <table id="table" class="table table-bordered table-striped w-100" style="text-align:center;"></table>'+
                '        </div><br>'+
                '    </div><br>'+
                '</div>',
                controller : 'auxIndicator5Ctrl'
            });
    }])
    .controller('auxIndicator5Ctrl', ["$scope", "$compile", "$log", "util", "petition", "toastr",
        function($scope, $compile, $log, util, petition, toastr){

        util.liPage('indicator5');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Proveedor", "bSortable" : true},
                {"sTitle": "Nombre", "bSortable" : true},
                {"sTitle": "Stock", "bSortable" : true, "sWidth": "80px"}
            ],
            data  	: 	['provider','name','cant'],
        };


        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxproduct/get/prodOutProvider')
                .then(function(data){
                    $scope.tableData = data.products;
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
    }]);