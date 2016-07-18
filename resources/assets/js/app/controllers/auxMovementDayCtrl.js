angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('MovementDay', {
                url: '/Movimientos-diarios',
                templateUrl: 'app/partials/auxMovementDay.html',
                controller : 'auxMovementDayCtrl'
            });
    }])
    .controller('auxMovementDayCtrl', ["$scope", "$compile", "$state", "$log", "util", "petition", "toastr", "$filter",
        function($scope, $compile, $state, $log, util, petition, toastr,$filter){

        util.liPage('movementday');

        var s1 = {
            "Retornado": ['Retorno','bgm-teal',false],
            "Vendido": ['Vendido','bgm-green',false]
        };



        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha", "bSortable" : true, 'sWidth': '80px'},
                {"sTitle": "Codigo", "bSortable" : true, 'sWidth': '1px'},
                {"sTitle": "Producto", "bSortable" : true},
                {"sTitle": "Color", "bSortable" : true},
                {"sTitle": "Talla", "bSortable" : true},
                {"sTitle": "Precio S/.", "bSortable" : true},
                {"sTitle": "Descuento S/.", "bSortable" : true},
                {"sTitle": "P. Final S/.", "bSortable" : true},
                {"sTitle": "Estatus" , "bSearchable": true},
            ],
            buttons	:
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'status', column: 'status', render : s1}
                        ]
                    }
                ],
            data  	: 	['fecha','codigo','product','color','talla','price','discount','pricefinal','status'],
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxmovement/get/movementDay')
                .then(function(data){
                    $scope.tableData = data.movements;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.filter = function(){
            $scope.updateList = true;
            $scope.dateSave = angular.copy($scope.data);
            $scope.dateSave.date1 = $filter('date')($scope.data.date1, 'yyyy-MM-dd')
            // $scope.dateSave.date2 = $filter('date')($scope.data.date2, 'yyyy-MM-dd')
            petition.get('api/auxmovement/get/movementDay/get', { params : $scope.dateSave })
                .then(function(data){
                    $scope.tableData = data.movements;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                    // if ( data.movements.length > 0){
                    //     chart.drawColummn(data.draw,data.days);
                    //     $scope.drawShow=true;
                    // } else {
                    //     $scope.drawShow=false;
                    // }
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.list();
        });
    }]);