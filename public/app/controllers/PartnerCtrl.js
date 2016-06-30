angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Socios', {
                url: '/Reporte-de-productividad-socios',
                templateUrl: 'app/partials/Partner.html',
                controller : 'PartnerCtrl'
            });
    })
    .controller('PartnerCtrl', function($scope, $compile, $log, util, petition, toastr, $filter, charts){

        util.liPage('partners');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Producto", "bSortable" : true},
                {"sTitle": "Cantidad", "bSortable" : true, 'class': 'f-500 c-cyan', 'width': '50px'}
            ],
            data  	: 	['name', 'quantity'],
            options : {
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": false,
                "bSort": false,
                "bInfo": false,
                "bAutoWidth": false
            }
        };

        var data2 = [
            {name: 'Samsung Galaxy Mega', quantity: '25'},
            {name: 'Huawei Ascend P6', quantity: '24'},
            {name: 'HTC One M8', quantity: '17'},
            {name: 'Samsung Galaxy Alpha', quantity: '15'},
            {name: 'LG G3', quantity: '15'}
        ];

        $scope.listTopSales = function(option) {
            petition.get('api/partner/get/top/sales', {params:{filter:option}})
                .then(function(data){
                    $scope.tableData = RowCount(data.TopSales);
                    $('#table').AJQtable2('view2', $scope, $compile);
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.listLessSold = function() {
            petition.get('api/partner/get/top/less-sold')
                .then(function(data){
                    $scope.tableData = RowCount(data.TopLessSold);
                    $('#table-2').AJQtable2('view2', $scope, $compile);
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.productSales = function() {
            petition.get('api/partner/get/products/sales')
                .then(function(response){
                    response || (response = []);
                    $('#product-sales').AJQtable2('view2', $scope, $compile, response.products);
                    chartDraw(response.chart);
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        function chartDraw(data){
            var ChartData = {
                labels: data.labels,
                datasets: [
                    {
                        data: data.data,
                        backgroundColor: data.colors,
                    }
                ]
            };
            charts.make("SalesChart", "pie", ChartData);
        }
        
        function RowCount(data) {
            if(typeof data !== 'object')return [];
            var length = data.length;
            for(var i = 0; i < 5 - length;i++ ){
                data.push({name:'-',quantity: '-'})
            }
            return data
        }

        // Any of the following formats may be used
        
        angular.element(document).ready(function(){
            $scope.listTopSales();
            $scope.listLessSold();
            $scope.productSales();
        });
    });