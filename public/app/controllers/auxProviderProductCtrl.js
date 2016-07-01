angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Provider', {
                url: '/Adminitracion-de-proveedor',
                templateUrl: 'app/partials/auxProviderProduct.html',
                controller : 'auxProviderProductCtrl'
            });
    })
    .controller('auxProviderProductCtrl', function($scope, $compile, $state, $log, util, petition, toastr,$filter){

        util.liPage('provider');

        var s1 = {
            0: ['Regular','bgm-green',false],
            1: ['Promoción','bgm-red',false],
        };

        $scope.months = [
            {id:1, name:'Enero'},
            {id:2, name:'Febrero'},
            {id:3, name:'Marzo'},
            {id:4, name:'Abril'},
            {id:5, name:'Mayo'},
            {id:6, name:'Junio'},
            {id:7, name:'Julio'},
            {id:8, name:'Agosto'},
            {id:9, name:'Setiembre'},
            {id:10, name:'Octubre'},
            {id:11, name:'Noviembre'},
            {id:12, name:'Diciembre'},
        ];

        $scope.years = [2016,2017];

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha", "bSortable" : true, 'sWidth': '80px'},
                {"sTitle": "Producto", "bSortable" : true, 'sWidth': '1px'},
                {"sTitle": "Código", "bSortable" : true},
                {"sTitle": "Costo Unitario S/.", "bSortable" : true},
                {"sTitle": "Tipo", "bSortable" : true}
            ],
            buttons	:
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'liquidacion', column: 'liquidacion', render : s1}
                        ]
                    }
                ],
            data  	: 	['fecha','product','codigo','cost','liquidacion'],
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxproduct/get/products/provider/'+3)
                .then(function(data){
                    $scope.tableData = data.movements;
                    $scope.monto=0;
                    for(var i in $scope.tableData){
                        if(parseFloat($scope.tableData[i].cost) != undefined){
                           $scope.monto+=parseFloat($scope.tableData[i].cost);
                        }
                    }
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.perDay = function(){
            $scope.updateList = true;
            $scope.dateSave = angular.copy($scope.data);
            $scope.dateSave.date1 = $filter('date')($scope.data.date1, 'yyyy-MM-dd')
            petition.get('api/auxproduct/get/products/provider/'+3, { params : $scope.dateSave })
                .then(function(data){
                    $scope.tableData = data.movements;
                    $scope.monto=0;
                    for(var i in $scope.tableData){
                        if(parseFloat($scope.tableData[i].cost) != undefined){
                            $scope.monto += parseFloat($scope.tableData[i].cost)
                        }
                    }
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.perMonth = function(){
            $scope.updateList = true;
            $scope.dateSave = angular.copy($scope.data2);

            // $scope.dateSave.date1 = new Date($scope.data2.year+'-'+$scope.data2.month.id);

            petition.get('api/auxproduct/get/products/provider/month/'+3, { params : $scope.dateSave })
                .then(function(data){
                    $scope.tableData = data.movements;
                    $scope.monto=0;
                    for(var i in $scope.tableData){
                        if(parseFloat($scope.tableData[i].cost) != undefined){
                            $scope.monto += parseFloat($scope.tableData[i].cost)
                        }
                    }
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
            $scope.salesDate();
        };

        $scope.perDate = function(){
            $scope.updateList = true;
            $scope.dateSave = angular.copy($scope.data3);
            $scope.dateSave.dateaux1 = $filter('date')($scope.data3.dateaux1, 'yyyy-MM-dd')
            $scope.dateSave.dateaux2 = $filter('date')($scope.data3.dateaux2, 'yyyy-MM-dd')
            petition.get('api/auxproduct/get/products/provider/date/'+3, { params : $scope.dateSave })
                .then(function(data){
                    $scope.tableData = data.movements;
                    $scope.monto=0;
                    for(var i in $scope.tableData){
                        if(parseFloat($scope.tableData[i].cost) != undefined){
                            $scope.monto += parseFloat($scope.tableData[i].cost)
                        }
                    }
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.amount = function(){
            for(var i in $scope.tableData){
                $scope.monto += $scope.tableData[i].cost;
            }
            return $scope.monto;
        };

        $scope.graphy = function(){
            const CHART = document.getElementById("lineChart");
            var lineChart = new Chart(CHART, {
                type: 'line',
                data: {
                    labels:  $scope.daysData,
                    datasets: [
                        {
                            label: "Ventas Mensuales",
                            fill: false,
                            lineTension: 0.1,
                            backgroundColor: "rgba(75,192,192,0.4)",
                            borderColor: "rgba(75,192,192,1)",
                            borderCapStyle: 'butt',
                            borderDash: [],
                            borderDashOffset: 0.1,
                            borderJoinStyle: 'miter',
                            pointBorderColor: "rgba(75,192,192,1)",
                            pointBackgroundColor: "#fff",
                            pointBorderWidth: 1,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(75,192,192,1)",
                            pointHoverBorderColor: "rgba(220,220,220,1)",
                            pointHoverBorderWidth: 2,
                            pointRadius: 1,
                            pointHitRadius: 10,
                            data: $scope.dataData,
                            maintainAspectRatio: true,
                            responsive: true,
                        }
                    ]
                }});
        };

        $scope.salesNow = function() {
            $scope.updateList = true;
            petition.get('api/auxproduct/get/products/provider/sale/now/'+3)
                .then(function(data){
                    $scope.daysData = data.days_lists;
                    $scope.dataData = data.data_lists;
                    // console.log($scope.daysData);
                    // console.log($scope.dataData);
                    $scope.graphy();
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.salesDate = function() {
            $scope.updateList = true;
            $scope.dateSave = angular.copy($scope.data2);
            petition.get('api/auxproduct/get/products/provider/sale/'+3, { params : $scope.dateSave })
                .then(function(data){
                    $scope.daysData = data.days_lists;
                    $scope.dataData = data.data_lists;
                    // console.log($scope.daysData);
                    // console.log($scope.dataData);
                    $scope.graphy();
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.list();
            $scope.salesNow();
        });
    });