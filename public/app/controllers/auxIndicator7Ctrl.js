angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Indicator7', {
                url: '/Reporte-de-productos-entre-fechas',
                templateUrl: 'app/partials/auxIndicator7.html',
                controller : 'auxIndicator7Ctrl'
            });
    })
    .controller('auxIndicator7Ctrl', function($scope, $compile, $log, util, petition, toastr, $filter, chart){

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

        $scope.data = {
            date1 : null,
            date2: null,
            status: 'Vendido'
        };

        $scope.list = function() {
            $scope.updateList = true;
            $scope.reportDownload = false;
            petition.get('api/auxmovement/get/movementDay')
                .then(function(data){
                    $scope.tableData = data.movements;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                    $scope.drawShow=false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.listProducts = function( select ) {
            if (select == 's'){
                $scope.data.size = null;
                $scope.data.color = null;
            }
            $scope.updateList = true;
            $scope.reportDownload = false;
            petition.get('api/auxproduct/get/report', { params: $scope.data })
                .then(function(data){
                    $scope.data.provider = null;
                    if (select == 'p'){
                        $scope.products = data;
                        $scope.sizes = [];
                        $scope.colors = [];
                    } else if (select == 's'){
                        $scope.sizes = data;
                        $scope.colors = [];
                    } else if (select == 'c'){
                        $scope.colors = data;
                    }
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.listProviders = function() {
            petition.get('api/auxproviders')
                .then(function(data){
                    $scope.providers = data.providers;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.filter = function(){
            $scope.updateList = true;
            $scope.dateSave = angular.copy($scope.data);
            $scope.dateSave.date1 = $filter('date')($scope.data.date1, 'yyyy-MM-dd')
            $scope.dateSave.date2 = $filter('date')($scope.data.date2, 'yyyy-MM-dd')
            petition.get('api/auxmovement/get/movementDays', { params : $scope.dateSave })
                .then(function(data){
                    $scope.reportDownload = true;
                    $scope.tableData = data.movements;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                    if ( data.movements.length > 0){
                        chart.drawColummn(data.draw,data.days);
                        $scope.drawShow=true;
                    } else {
                        $scope.drawShow=false;
                    }
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.download = function(){
            petition.post('api/auxmovement/get/movementDays/download', $scope.dateSave, {responseType:'arraybuffer'})
                .then(function(data){
                    var date = new Date().getTime();
                    var name = date + '-reporte-de-movimiento-'+ $scope.dateSave.date1+'-al-'+$scope.dateSave.date2+'.pdf';
                    var file = new Blob([data],{ type : 'application/pdf'});
                    saveAs(file, name);
                }, function(error){
                    console.info(error);
                });
        };

        $scope.noProduct = function(){
            $scope.data.name = null;
            $scope.data.size = null;
            $scope.data.color = null;

            $scope.sizes = [];
            $scope.colors = [];
        };

        function providerName(p, providers){
            for(i in providers){
                if (p == providers[i].id){
                    return providers[i].name;
                }
            }
        }

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.product = angular.copy($scope.productClear);
            $scope.list();
            $scope.listProviders();
            $scope.listProducts('p');
        });
    });