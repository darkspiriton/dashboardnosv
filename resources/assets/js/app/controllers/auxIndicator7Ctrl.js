angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('Indicator7', {
                url: '/Reporte-de-productos-entre-fechas',
                templateUrl: 'app/partials/auxIndicator7.html',
                controller : 'auxIndicator7Ctrl'
            });
    }])
    .controller('auxIndicator7Ctrl', ["$scope", "$compile", "$log", "util", "petition", "toastr", "$filter", "chart",
        function($scope, $compile, $log, util, petition, toastr, $filter, chart){

        util.liPage('indicator7');

        var s1 = {
            0: ['Normal','bgm-green',false],
            1: ['Liquidacion','btn-info',false],
            'fail': ['otros','bgm-red',false]
        };

        var s2 = {
            0: ['Retornado','bgm-red',false],
            1: ['Vendido','bgm-teal',false],
            2: ['Salida','bgm-deeppurple',false],
            3: ['Reprogramado','bgm-purple',false]
        };

        var status_mov = function($row){
                                if($row.status == 'retornado'){
                                    return 0;
                                } else if($row.status == 'Vendido'){
                                    return 1;
                                } else if($row.status == 'salida'){
                                    if($row.situation == 'reprogramado'){
                                        return 3;
                                    } else {
                                        return 2;
                                    }
                                }
                             };

        var statusSituation = function($row){
                if($row.status == 'salida'){
                    if($row.situation != 'reprogramado'){
                        return $row.situation;
                    } else return '';
                } else return ''; 
            };

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "F. Pedido", "bSortable" : true, "sWidth" : '80px'},
                {"sTitle": "F. Entrega", "bSortable" : true, "sWidth" : '80px'},
                {"sTitle": "Pedido", "bSortable" : true, "sWidth" : '1px'},
                {"sTitle": "Codigo", "bSortable" : true, "sWidth" : '1px'},
                {"sTitle": "Producto", "bSortable" : true},
                {"sTitle": "Color", "bSortable" : true},
                {"sTitle": "Talla", "bSortable" : true},
                {"sTitle": "Estado", "bSortable" : true},
                {"sTitle": "Detalle", "bSortable" : true},
                {"sTitle": "Estado V.", "bSortable" : true},
                {"sTitle": "P. Real", "bSortable" : true},
                {"sTitle": "Precio", "bSortable" : true},
                {"sTitle": "Desc.", "bSortable" : true},
                {"sTitle": "P. Final", "bSortable" : true}
            ],
            buttons :
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'status_product', column: 'liquidation', render: s1},
                            { name: 'status_mov', render: s2, call_me: status_mov},
                        ]
                    },
                    {
                        type: 'custom',
                        list:  [
                            { name: 'statusDetail', call_me: statusSituation}
                        ]
                    }
                ],
            data  	: 	['date_request','fecha','cod_order','codigo','product','color','talla','status_mov','statusDetail','status_product','price_real','price','discount','price_final']
        };

        $scope.data = {
            date1 : null,
            date2: null,
            status: null
        };

        $scope.list = function() {
            $scope.updateList = true;
            $scope.reportDownload = false;
            petition.get('api/auxmovement/get/movementDays')
                .then(function(data){
                    $scope.tableData = data.movements;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                    $scope.drawShow=false;
                }, function(error){
                    toastr.error('Huy huy dice: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.listProducts = function(select) {
            $scope.data.size = null;
            $scope.data.color = null;

            $scope.data.provider = null;

            $scope.updateList = true;
            $scope.reportDownload = false;

            petition.get('api/auxproduct/get/report', { params: $scope.data })
                .then(function(data){
                    if (select == 'p'){
                        $scope.products = data.products;
                        $scope.sizes = [];
                        $scope.colors = [];
                    } else {
                        $scope.sizes = data.sizes;
                        $scope.colors = data.colors;
                    }
                    $scope.updateList = false;
                }, function(error){
                    toastr.error('Huy huy dice: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.listProviders = function() {
            petition.get('api/auxproviders')
                .then(function(data){
                    $scope.providers = data.providers;
                }, function(error){
                    toastr.error('Huy huy dice: ' + error.data.message);
                });
        };

        $scope.filter = function(){
            $scope.updateList = true;
            $scope.dateSave = angular.copy($scope.data);
            // $scope.dateSave.date1 = $filter('date')($scope.data.date1, 'yyyy-MM-dd');
            // $scope.dateSave.date2 = $filter('date')($scope.data.date2, 'yyyy-MM-dd');
            petition.get('api/auxmovement/get/movementDays', { params : $scope.dateSave })
                .then(function(data){
                    $scope.reportDownload = true;
                    $scope.tableData = data.movements;
                    $scope.infoMonth = data.month;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                    if ( data.movements.length > 0){
                        chart.drawColummn(data.draw,data.days);
                        $scope.drawShow=true;
                    } else {
                        $scope.drawShow=false;
                    }
                }, function(error){
                    toastr.error('Huy huy dice: ' + error.data.message);
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
            for(var i in providers){
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
    }]);
