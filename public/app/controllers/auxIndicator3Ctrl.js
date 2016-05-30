angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Indicator3', {
                url: '/stock-general-de-productos-por-color',
                template: '<div class="card" >'+
                '    <div class="card-header bgm-blue">'+
                '        <h2>Stock general de productos por color</h2>'+
                '        <button ng-disabled="updateList" class="btn bgm-green btn-float waves-effect btnLista" ng-click="list()"'+
                '                data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Pulse para actualizar los registros"'+
                '                title="" data-original-title="Actualizar"><i class="md md-sync"></i></button>'+
                '    </div>'+
                '    <div class="card-body card-padding table-responsive">'+
                '        <div class="col-sm-12">'+
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
                controller : 'auxIndicator3Ctrl'
            });
    })
    .controller('auxIndicator3Ctrl', function($scope, $compile, $log, util, petition, toastr, chart){

        util.liPage('indicator3');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Nombre", "bSortable" : true},
                {"sTitle": "Color", "bSortable" : true, "sWidth": "150px"},
                {"sTitle": "Stock", "bSortable" : true, "sWidth": "80px"}
            ],
            data  	: 	['name','color','cant'],
            configStatus : 'status'
        };


        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxproduct/get/prodColor')
                .then(function(data){
                    $scope.tableData = data.products;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                    chart.draw($scope.tableData, {data: 'cant', label: 'name'})
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