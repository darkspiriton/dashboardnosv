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

        var lineChart;

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

        var salesConfig = {
            columns :   [
                {"sTitle": "Mes", 'class': 'f-500 c-indigo'},
                {"sTitle": "Ventas"},
                {"sTitle": "Monto", 'class': 'f-500 c-indigo', 'width': '70px'}
            ],
            data    :   ['month', 'data.sales.count','data.sales.cost_total'],
            options : {
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": false,
                "bSort": false,
                "bInfo": false,
                "bAutoWidth": false
            }
        };

        var paymentsConfig = {
            columns :   [
                {"sTitle": "Mes", 'class': 'f-500 c-indigo'},
                {"sTitle": "RAN", 'class': 'f-500 c-indigo'},
                {"sTitle": "MM", 'class': 'f-500 c-indigo'},
                {"sTitle": "D", 'class': 'hidden-xs'},
                {"sTitle": "MD", 'class': 'f-500 c-indigo'},
                {"sTitle": "P", 'class': 'hidden-xs'},
                {"sTitle": "MP", 'class': 'f-500 c-indigo'},
                {"sTitle": "PT", 'class': 'f-500 c-indigo'},
                {"sTitle": "RAC", 'class': 'f-500 c-indigo'},
                {"sTitle": "Detalle", 'class': 'hidden-xs'},
            ],
            buttons    :
                [
                    {
                        type: 'actions',
                        list:  [
                            { name: 'detail', render: [['Detalle','detail','bgm-teal']]},
                        ]
                    },
                    {
                        type: 'custom',
                        list: [
                            { name: 'residueBefore', call_me: function(row, i, rows){
                                if(i == 0){
                                    row.residueBefore = 0;
                                } else {
                                    row.residueBefore = rows[i-1].residueAfter;
                                }
                                return row.residueBefore;
                            }},
                            { name: 'payTotal', call_me: function(row){
                                row.payMonthTotal = row.data.payments.summ.disc_total + row.data.payments.summ.pay_total;
                                return row.payMonthTotal;
                            }},
                            { name: 'residueAfter', call_me: function(row, i, rows){
                                if(i == 0){
                                    row.residueAfter = row.data.sales.cost_total - row.payMonthTotal;
                                } else {
                                    row.residueAfter = Math.round((row.data.sales.cost_total - row.payMonthTotal + row.residueBefore) * 100) / 100;
                                }

                                return row.residueAfter;
                            }}
                        ]
                    }
                ],
            data    :   [
                            'month',
                            'residueBefore',
                            'data.sales.cost_total',
                            'data.payments.summ.disc_count',
                            'data.payments.summ.disc_total',
                            'data.payments.summ.pay_count',
                            'data.payments.summ.pay_total',
                            'payTotal',
                            'residueAfter',
                            'detail',
                        ],
            options : {
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": false,
                "bSort": false,
                "bInfo": false,
                "bAutoWidth": false
            }
        };

        $scope.listTopSales = function(option) {
            petition.get('api/partner/get/top/sales', {params: $scope.date})
                .then(function(data){
                    $scope.tableData = RowCount(data.TopSales);
                    $('#table').AJQtable2('view2', $scope, $compile);
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.listLessSold = function() {
            petition.get('api/partner/get/top/less-sold')
                .then(function(data){
                    $scope.tableData = RowCount(data.TopLessSold);
                    $('#table-2').AJQtable2('view2', $scope, $compile);
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.productSales = function() {
            petition.get('api/partner/get/products/sales', {params: $scope.date})
                .then(function(response){
                    response || (response = []);
                    $('#product-sales').AJQtable2('view2', $scope, $compile, response.products);
                    chartDraw(response.chart);
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.salesAndPayments = function() {
            petition.get('api/partner/get/payments')
                .then(function(response){
                    $scope.sAndP = response.movements;
                    $('#sales').AJQtable2('view2', $scope, $compile, response.movements, salesConfig);
                    $('#payments').AJQtable2('view2', $scope, $compile, response.movements, paymentsConfig);
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.detail = function(i){
            $scope.salesPaymentsDetail = $scope.sAndP[i];
            util.modal("salesPaymentsDetail");
        };

        function chartDraw(data){
            if(data.constructor === Array)return;
            if(data.data.length == 0)return;
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

        $scope.dateChange = function(date){
            $scope.date = angular.copy(date);
        }

        $scope.list = function(){
            $scope.listTopSales();
            $scope.listLessSold();
            $scope.productSales();
            $scope.salesAndPayments();
            $scope.listProductMovements();
            $scope.salesDate();
        }

        /**
         * Controller by cesar
         *    
         */


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

        var C_tableConfig  =   {
            columns:   [
                {"sTitle": "Fecha", "bSortable" : true, 'sWidth': '80px'},
                {"sTitle": "Producto", "bSortable" : true, 'sWidth': '1px'},
                {"sTitle": "Código", "bSortable" : true},
                {"sTitle": "Costo Unitario S/.", "bSortable" : true},
                {"sTitle": "Venta", "bSortable" : true}
            ],
            buttons:
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'liquidacion', column: 'liquidacion', render : s1}
                        ]
                    }
                ],
            data:   ['fecha','product','codigo','cost','liquidacion'],
        };

        $scope.listProductMovements = function(){
            $scope.updateList = true;
            petition.get('api/partner/get/products/movements' , { params : $scope.date })
                .then(function(data){
                    $scope.tableData = data.movements;
                    $scope.monto = 0;
                    for(var i in $scope.tableData){
                        if(parseFloat($scope.tableData[i].cost) != undefined){
                            $scope.monto += parseFloat($scope.tableData[i].cost)
                        }
                    }
                    $('#ProductMovements').AJQtable2('view2', $scope, $compile, data.movements, C_tableConfig);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
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
            if(typeof lineChart != 'undefined')lineChart.destroy();
            chart = document.getElementById("lineChart");
            lineChart = new Chart(chart, {
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

        $scope.salesDate = function() {
            $scope.updateList = true;
            $scope.dateSave = angular.copy($scope.data2);
            petition.get('api/partner/get/products/provider/sale', { params : $scope.date })
                .then(function(data){
                    $scope.daysData = data.days_lists;
                    $scope.dataData = data.data_lists;
                    $scope.graphy();
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                    $scope.updateList = false;
                });
        };
             
        angular.element(document).ready(function(){
            $scope.list();

            $scope.day = $scope.mtn = $scope.range = {};
            $scope.searchView = 0;
            $scope.dateNow = new Date();
        });
    });