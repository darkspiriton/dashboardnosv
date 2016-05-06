angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Indicator4', {
                url: '/stock-general-de-por-proveedor',
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
                controller : 'auxIndicator4Ctrl'
            });
    })
    .controller('auxIndicator4Ctrl', function($scope, $compile, $log, util, petition, toastr, chart){

        util.liPage('indicator4');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Nombre", "bSortable" : true},
                {"sTitle": "Stock", "bSortable" : true, "sWidth": "80px"}
            ],
            data  	: 	['name','cant'],
        };


        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxproduct/get/cantPro')
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
            $scope.product = angular.copy($scope.productClear);
            $scope.list();
        });
    });