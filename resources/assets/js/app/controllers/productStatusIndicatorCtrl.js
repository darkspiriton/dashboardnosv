angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('IndicadorProductosEstados', {
                url: '/Indicador-de-historial-de-estados-de-productos',
                templateUrl: 'app/partials/productSatatusIndicator.html',
                controller : 'productStatusIndicatorCtrl'
            });
    }])
    .controller('productStatusIndicatorCtrl', ["$scope", "$compile", "util", "petition", "toastr", "charts",
        function(vm, $compile, util, petition, toastr, charts) {

        util.liPage("ProductStatusIndicator");

        /**
         * Data
         */
        
        var chart;
        var lineChart;

        vm.months = [
            { id: 1, name: "Enero" },
            { id: 2, name: "Febrero" },
            { id: 3, name: "Marzo" },
            { id: 4, name: "Abril" },
            { id: 5, name: "Mayo" },
            { id: 6, name: "Junio" },
            { id: 7, name: "Julio" },
            { id: 8, name: "Agosto" },
            { id: 9, name: "Septiembre" },
            { id: 10, name: "Octubre" },
            { id: 11, name: "Noviembre" },
            { id: 12, name: "Diciembre" },
        ];

        vm.years = [
            { id: 2016, name: "2016" },
            { id: 2017, name: "2017" },
            { id: 2018, name: "2018" },
            { id: 2019, name: "2019" },
            { id: 2010, name: "2020" },
        ];

        vm.tableConfig = {
            columns :   [
                {"sTitle": "Estado", 'class': 'f-500 c-cyan'},
                {"sTitle": "Cant.", 'width': '50px'},
                {"sTitle": "%", 'class': 'f-500 c-cyan', 'width': '50px'}
            ],
            data    :   ['description', 'detail_statuses_count.count', 'percentage'],
            options : {
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": false,
                "bSort": false,
                "bInfo": false,
                "bAutoWidth": false
            }
        };
        
        /**
         * Methods
         */
        
        vm.filter = function(status, search) {
            petition.get('api/auxproduct/status/' + status + '/indicator', { params: search })
                .then(function(response){
                    vm.indicator = proceessResponse(response.indicator);
                    vm.tableData = vm.indicator.status_details;
                    $("#table").AJQtable2("view2", vm, $compile);
                    chartDraw(response.chart);
                }, function(error){
                    toastr.error("Huy Huy dice: " + error.data.message || "Error inesperado");
                });
        };

        vm.listStatuses = function(){
            petition.get('api/auxproduct/get/statuses')
                .then(function(response){
                    vm.statuses = response.statuses;
                }, function(error){
                    toastr.error("Huy Huy dice: " + error.data.message || "Error inesperado");
                });
        };
       
        function chartDraw(data){
            if(data.constructor === Array)return;
            if(data.data.length === 0)return;
            var ChartData = {
                labels: data.labels,
                datasets: [
                    {
                        data: data.data,
                        backgroundColor: data.colors,
                    }
                ]
            };
            if (chart !== undefined) {
                chart.destroy();
            }
            chart = charts.make("IndicatorChart", "pie", ChartData);
        }

        function proceessResponse(data){
            if (data.length === 0) {
                return data;
            }

            var total = 0;
            for (var i = data.status_details.length - 1; i >= 0; i--) {
                total += data.status_details[i].detail_statuses_count.count;
            }
            for (var y = data.status_details.length - 1; y >= 0; y--) {
                var c = data.status_details[y].detail_statuses_count.count;
                data.status_details[y].percentage = parseFloat((c / total)*100).toFixed(2);
            }
            return data;
        }

        /**
         * Ready
         */
        angular.element(document).ready(function(){
            vm.listStatuses();
        });
    }]);
