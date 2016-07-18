angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('q_AnswerIndicator', {
                url: '/Indicador-de-coincidencias-cuestionarios',
                templateUrl: 'app/partials/q_AnswerIndicator.html',
                controller : 'q_AnswerIndicatorCtrl'
            });
    }])
    .controller('q_AnswerIndicatorCtrl', ["$scope", "$compile", "$state", "$log", "util", "petition", "chart", "toastr",
        function($scope, $compile, $state, $log, util, petition, chart, toastr){

        util.liPage('q_AnswerIndicator');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "0%", "bSortable" : true, 'sWidth': '80px'},
                {"sTitle": "0% - 20%]", "bSortable" : true},
                {"sTitle": "20% - 40%]", "bSortable" : true},
                {"sTitle": "40% - 60%]", "bSortable" : true},
                {"sTitle": "60% - 80%]", "bSortable" : true},
                {"sTitle": "80% - 100%" , "bSearchable": true},
                {"sTitle": "100%" , "bSearchable": true}
            ],
            data  	: 	['col_1','col_2','col_3','col_4','col_5','col_6','col_7'],
            configStatus : 'status'
        };

        $scope.listProducts = function() {
            petition.get('api/auxqproduct')
                .then(function(data){
                    $scope.products = data.productos;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.listCustomer = function() {
            petition.get('api/auxqcustomer')
                .then(function(data){
                    $scope.customers = data.customers;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.list = function(s) {
            $scope.codeDetail = null;
            var controller = (s == 'p')?'auxqproduct':'auxqcustomer';
            var id;
            if(s == 'p'){
                id = $scope.searchP;
                $scope.searchC = null;
            }else{
                id = $scope.searchC;
                $scope.searchP = null;
            }
            petition.get('api/'+ controller +'/'+id)
                .then(function(data){
                    $scope.cantidades = data.cantidades;
                    $scope.codigos = data.codigos;
                    $scope.tableData = data.row;
                    $('#table').AJQtable('view', $scope, $compile);
                    chart.draw(data.cantidades, {data: 'count', label: 'label'});
                    if(Object.keys($scope.codigos).length > 0) {
                        $scope.showDetail = true;
                    } else {
                        $scope.showDetail = false;
                    }
                }, function(error){
                    $scope.cantidades=[];
                    $scope.codigos=[];
                    util.resetTable($scope,$compile);
                    chart.draw([], {});
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.submit = function () {
            $scope.list();
        };

        $scope.detail = function (i) {
            console.log('=)');
            if($scope.searchP != null){
                $scope.detailForProducts(i);
            } else if($scope.searchC != null){
                $scope.detailForCustomers(i);
            }
        };

        $scope.detailForCustomers = function (i) {
            $scope.codeDetail = null;
            petition.post('api/indicator/questionnaire/for/products', {codes : $scope.codigos[i].codes})
                .then(function(data){
                    $scope.records = data.records;
                    util.modal('forProducts');
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.detailForProducts = function (i) {
            $scope.codeDetail = null;
            petition.post('api/indicator/questionnaire/for/customers', {codes : $scope.codigos[i].codes})
                .then(function(data){
                    $scope.records = data.records;
                    util.modal('forCustomers');
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.cancel = function () {
            $scope.product = angular.copy($scope.productClear);
            $scope.productState = true;
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.product = angular.copy($scope.productClear);
            $scope.productState = true;
            util.muestraformulario();
        };

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.codigos = [];
            $scope.showDetail = false;
            $scope.listProducts();
            $scope.listCustomer();
        });
    }]);