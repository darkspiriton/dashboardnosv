angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('Indicator1', {
                url: '/stock-general-de-productos',
                template: '<div class="card" >'+
                '    <div class="card-header bgm-blue">'+
                '        <h2>Stock general de productos</h2>'+
                '        <button ng-disabled="updateList" class="btn bgm-green btn-float waves-effect btnLista" ng-click="list()"'+
                '                data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Pulse para actualizar los registros"'+
                '                title="" data-original-title="Actualizar"><i class="md md-sync"></i></button>'+
                '    </div>'+
                '    <div class="card-body card-padding table-responsive">'+
                '       <div class="col-sm-12" ng-show="updateList">'+
                '           <i class="fa fa-refresh fa-spin fa-5x" style="display: table;margin: 0 auto;"></i>'+
                '        </div><br>'+
                '        <div class="col-sm-12" ng-hide="updateList">'+
                '            <table id="table" class="table table-bordered table-striped w-100" style="text-align:center;"></table>'+
                '        </div><br>'+
                '    </div><br>'+
                '</div>'+
                '<div class="card">'+
                '    <div class="card-header">'+
                '        <h2>Vista Grafica</h2>'+
                '    </div>'+
                '    <div class="card-body card-padding">'+
                '        <div id="pie-chart" class="flot-chart-pie"></div>'+
                '        <div class="flc-pie hidden-xs"></div>'+
                '    </div>'+
                '</div>',
                controller : 'auxIndicator1Ctrl'
            });
    }])
    .controller('auxIndicator1Ctrl',["$scope", "$compile", "$log", "util", "petition", "toastr", "chart",
        function($scope, $compile, $log, util, petition, toastr, chart){

        util.liPage('indicator1');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Nombre", "bSortable" : true},
                {"sTitle": "Color", "bSortable" : true, "sWidth": "150px"},
                {"sTitle": "Talla", "bSortable" : true, "sWidth": "80px"},
                {"sTitle": "Stock", "bSortable" : true, "sWidth": "80px"}
            ],
            data  	: 	['name','color','size','cantP'],
            configStatus : 'status'
        };


        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxproduct/get/stockProd')
                .then(function(data){
                    $scope.tableData = data.stock;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                    chart.draw($scope.tableData, {data: 'cantP', label: 'name'})
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        angular.element(document).ready(function(){
            $scope.product = angular.copy($scope.productClear);
            $scope.list();
        });
    }]);