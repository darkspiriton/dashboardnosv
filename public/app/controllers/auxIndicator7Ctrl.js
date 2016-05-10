angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Indicator7', {
                url: '/Reporte-de-productos-entre-fechas',
                template: '<div class="card" >'+
                '    <div class="card-header bgm-blue">'+
                '        <h2>Reporte de movimientos entre fechas</h2>'+
                '        <button ng-disabled="updateList" class="btn bgm-green btn-float waves-effect btnLista" ng-click="list()"'+
                '                data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Pulse para actualizar los registros"'+
                '                title="" data-original-title="Actualizar"><i class="md md-sync"></i></button>'+
                '    </div>'+
                '    <div class="card-header bgm-blue">'+
                '       <div ng-form="dates" class="row report-date">'+
                    '       <h2 class="col-xs-12 col-sm-1">Fecha Inicio</h2>'+
                    '       <input type="date" class="btn btn-default col-sm-2" ng-model="date.date1" required>'+
                    '       <h2 class="col-xs-12 col-sm-1">Fecha Final</h2>'+
                    '       <input type="date" class="btn btn-default col-sm-2" ng-model="date.date2" required>'+
                    '       <input type="button" ng-disabled="!dates.$valid" ng-click="filter()" class="btn bgm-indigo m-l-30" value="buscar">'+
                '       <input type="button" ng-if="reportDownload" ng-click="download()" class="btn bgm-purple m-l-30" value="DESCARGAR PDF">'+
                '       </div>'+
                '    </div>'+
                '    <div class="card-body card-padding table-responsive">'+
                '        <div class="col-sm-12">'+
                '            <table id="table" class="table table-bordered table-striped w-100" style="text-align:center;"></table>'+
                '        </div><br>'+
                '    </div><br>'+
                '</div>',
                controller : 'auxIndicator7Ctrl'
            });
    })
    .controller('auxIndicator7Ctrl', function($scope, $compile, $log, util, petition, toastr, $filter){

        util.liPage('indicator7');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha", "bSortable" : true, "sWidth" : '80px'},
                {"sTitle": "Codigo", "bSortable" : true, "sWidth" : '1px'},
                {"sTitle": "Producto", "bSortable" : true},
                {"sTitle": "Color", "bSortable" : true},
                {"sTitle": "Talla", "bSortable" : true},
                {"sTitle": "Estado", "bSortable" : true}
            ],
            data  	: 	['fecha', 'codigo','product','color','talla','status']
        };

        $scope.list = function() {
            $scope.updateList = true;
            $scope.reportDownload = false;
            petition.get('api/auxmovement/get/movementDay')
                .then(function(data){
                    $scope.tableData = data.movements;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.filter = function(){
            $scope.updateList = true;
            $scope.dateSave = {};
            $scope.dateSave.date1 = $filter('date')($scope.date.date1, 'yyyy-MM-dd')
            $scope.dateSave.date2 = $filter('date')($scope.date.date2, 'yyyy-MM-dd')
            petition.get('api/auxmovement/get/movementDays', { params : $scope.dateSave })
                .then(function(data){
                    $scope.reportDownload = true;
                    $scope.tableData = data.movements;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.download = function(){

        };

        angular.element(document).ready(function(){
            $scope.product = angular.copy($scope.productClear);
            $scope.list();
        });
    });