angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('Indicator1', {
                url: '/stock-general-de-productos',
                template: 'app/partials/auxIndicator1.html',
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



        $scope.download = function(dataSearch){
            $scope.btnDownload = true;
            if(dataSearch.status_sale === "")dataSearch.status_sale = null;
            petition.post('api/auxproduct/filter/get/search/download', dataSearch, {responseType:'arraybuffer'})
                .then(function(data){
                    var date = new Date().getTime();
                    var name = date + '-reporte-de-kardex.xls';
                    var file = new Blob([data],{ type : 'application/vnd.ms-excel; charset=UTF-8'});
                    saveAs(file, name);
                    $scope.btnDownload = false;
                }, function(error){
                    toastr.error("El archivo es demasiado grande, no se pudo descargar");
                    $scope.btnDownload = false;
                });
        };

        angular.element(document).ready(function(){
            // $scope.product = angular.copy($scope.productClear);
            // $scope.list();
            $scope.btnDownload=false;
        });
    }]);
