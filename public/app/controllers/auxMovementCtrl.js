angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Movimientos', {
                url: '/Generar-movimientos',
                templateUrl: 'app/partials/auxMovement.html',
                controller : 'auxMovementCtrl'
            });
    })
    .controller('auxMovementCtrl', function($scope, $compile, $state, $log, util, petition, toformData, toastr){

        util.liPage('products');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Codigo", "bSortable" : true},
                {"sTitle": "Nombre", "bSortable" : true},
                {"sTitle": "Color", "bSortable" : true},
                {"sTitle": "Size", "bSortable" : true},
                {"sTitle": "Camt", "bSortable" : true},
                {"sTitle": "Acción" , "bSearchable": false , "sWidth": "80px"}
            ],
            actions	:  	[
                ['actions', [
                    ['Agregar', 'addProduct' ,'bgm-teal']
                ]
                ]
            ],
            data  	: 	['cod','name','color','size','cant','actions'],
            configStatus : 'status'
        };

        var alertConfig = {
            title: "¿Esta seguro?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "SI",
            cancelButtonColor: "#212121",
            cancelButtonText: "CANCELAR",
            closeOnConfirm: true
        };

        $scope.productsClear = [];


        $scope.list = function() {
            petition.get('api/auxmovement')
                .then(function(data){
                    $scope.tableData = data.products;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.submit = function () {
            alertConfig.title = '¿Todo es correcto?'
            swal(alertConfig ,
                function() {
                    petition.post('api/auxproduct', $scope.product).then(function(data){
                        toastr.success(data.message);
                        $scope.formSubmit=false;
                        $scope.list();
                        util.ocultaformulario();
                    }, function(error){
                        toastr.error('Uyuyuy dice: ' + error.data.message);
                        $scope.formSubmit=false;
                    })
                });
        };

        $scope.addProduct = function(ind){
            var count = 0;
            for(i in  $scope.products){
                if(angular.equals($scope.tableData[ind],$scope.products[i])){
                    count++;
                }
            }

            if (count == 0)$scope.products.push(angular.copy($scope.tableData[ind]));
        };

        $scope.removeProduct = function(i){
            $scope.products.splice(i,1);
        };

        $scope.cancel = function () {
            resetProduct();
            util.ocultaformulario();
        };

        $scope.new = function(){
            resetProduct();
            util.muestraformulario();
        };

        function resetProduct(){
            $scope.products = angular.copy($scope.productsClear);
        }

        angular.element(document).ready(function(){
            resetProduct();
            $scope.list();
        });
    });