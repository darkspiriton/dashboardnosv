angular.module('App')
    .config(["$stateProvider",function($stateProvider) {
        $stateProvider
            .state('listapagos', {
                url: '/Generar-pagos-proveedores',
                templateUrl: 'app/partials/PaymentList.html',
                controller : 'paymentListCtrl'
            });
    }])
    .controller('paymentListCtrl', ["$scope", "$compile", "$state", "$log", "$filter", "util", "petition", "toformData", "toastr",
        function($scope, $compile, $state, $log, $filter, util, petition, toformData, toastr){

        util.liPage('listapagos');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha", "bSortable" : true},
                {"sTitle": "Tipo de Pago", "bSortable" : true},
                {"sTitle": "Banco", "bSortable" : true},
                {"sTitle": "Monto", "bSortable" : true},
                {"sTitle": "Tipo de Descuento", "bSortable" : true},
                {"sTitle": "Descuento", "bSortable" : true},
                // {"sTitle": "Status" , "bSearchable": false , "sWidth": "80px"},
                {"sTitle": "Acción" , "bSearchable": false , "sWidth": "190px"}
            ],
            actions	:  	[
                ['status',   {
                    0 : { txt : 'Falta Pagar' , cls : 'bgm-red', dis : false },
                    1 : { txt : 'Pagado' ,  cls : 'bgm-green',dis: false}
                }
                ],
                ['actions', [
                    ['Eliminar', 'removePayment' ,'bgm-red'],
                ]
                ]
            ],
            data  	: 	['date','typeP','bank','amount','typeD','amount_discount','actions'],
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
            closeOnConfirm: true,
            html: true
        };

        $scope.providerChange = function(){
            $scope.id = $scope.p.provider_id;
        };
        
        $scope.monto=0;
        $scope.productsClear = [];

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/payment/get'+'?id='+$scope.id)
                .then(function(data){
                    $scope.tableData = data.payments;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Uyuyuy dice: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.removePayment = function(ind){
            $scope.idPayment=angular.copy($scope.tableData[ind].id);
            console.log($scope.idPayment);
            $scope.updateList = true;
            petition.delete('api/payment/'+$scope.idPayment)
                .then(function(){
                    $scope.list();
                    $scope.updateList = false;
                }, function(error){
                    toastr.error('Uyuyuy dice: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.submit = function() {
            valid_product_date(function() {
                $scope.dataProduct2 = {};
                $scope.dataProduct2.amount=angular.copy($scope.monto);
                $scope.dataProduct2.provider_id=angular.copy($scope.id);
                $scope.dataProduct2.datePayment=angular.copy($scope.p.datePayment);
                $scope.dataProduct2.typeP=angular.copy($scope.p.typeP);
                $scope.dataProduct2.bank=angular.copy($scope.p.bank);
                $scope.dataProduct2.typeD=angular.copy($scope.p.typeD);
                $scope.dataProduct2.reason=angular.copy($scope.p.reason);
                $scope.dataProduct2.discount=angular.copy($scope.p.discount);

                console.log($scope.dataProduct2);

                alertConfig.title = '¿Todo es correcto?';
                swal(alertConfig,
                    function () {
                        petition.post('api/payment', {products: $scope.dataProducts, data:$scope.dataProduct2}).then(function (data) {
                            toastr.success(data.message);
                            $scope.formSubmit = false;
                            $scope.list();
                            util.ocultaformulario();
                            $scope.anadir = false;
                        }, function (error) {
                            toastr.error('Uyuyuy dice: ' + error.data.message);
                            $scope.formSubmit = false;
                        })
                    });
            });
        };

        // $scope.preciofinal=function (i){
        //     if($scope.products[i].discount>$scope.products[i].price ){
        //         $scope.products[i].discount = 0;
        //         $scope.products[i].preciofinal=$scope.products[i].price;
        //         return ;
        //     } else if($scope.products[i].discount == undefined){
        //         $scope.products[i].preciofinal = $scope.products[i].price;
        //         return ;
        //     }
        //
        //     // $scope.products[i].discount = parseInt($scope.products[i].discount);
        //     $scope.products[i].preciofinal = Math.round(($scope.products[i].price - $scope.products[i].discount)*100)/100;
        //     $scope.dataProducts[i].discount = angular.copy($scope.products[i].discount);
        //
        // }


        function valid_product_date(callback){
            if ($scope.p.datePayment == undefined){
                toastr.error('Uyuyuy dice: Falta ingresar fecha de Pago');
                return;
            }
            return callback();
        }

        $scope.addProduct = function(ind){
            var count = 0;
            for(var i in  $scope.products){
                if(angular.equals($scope.tableData[ind],$scope.products[i])){
                    count++;
                }
            }
            if (count == 0 && $scope.anadir && $scope.tableData[ind].status == 0 ){
                toastr.success('se añadio');
                $scope.dataProducts.push({id:$scope.tableData[ind].id});
                $scope.monto = $scope.monto + $scope.tableData[ind].cost;
                $scope.products.push(angular.copy($scope.tableData[ind]));
            }
        };

        $scope.removeProduct = function(i){
            $scope.monto=$scope.monto-$scope.products[i].cost;
            $scope.dataProducts.splice(i,1);
            $scope.products.splice(i,1);
        };

        $scope.cancel = function () {
            $scope.anadir = false;
            resetProduct();
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.anadir = true;
            resetProduct();
            util.muestraformulario();
        };

        
        $scope.listProviders = function() {
            petition.get('api/auxproviders')
                .then(function(data){
                    $scope.providers = data.providers;
                    // $scope.providers.push(newProvider);
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.listBanks = function(){
            petition.get('api/payment/bank/get')
                .then(function(data){
                    $scope.banks = data.banks;
                    console.log($scope.banks);
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.listTypesD = function(){
            petition.get('api/payment/typeD/get')
                .then(function(data){
                    $scope.typesD=data.typesD;
                },function(error){
                    console.log($scope.types);
                   toastr.error('Ups ocurrio un problema: ' + error.data.message); 
                });
        };
        
        $scope.listTypesP = function(){
            petition.get('api/payment/typeP/get')
                .then(function(data){
                   $scope.typesP=data.typesP;
                },function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        function resetProduct(){
            $scope.dataProduct2= angular.copy($scope.productsClear);
            $scope.dataProducts = angular.copy($scope.productsClear);
            $scope.products = angular.copy($scope.productsClear);
        }

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            resetProduct();
            $scope.listProviders();
            $scope.listBanks();
            $scope.listTypesD();
            $scope.listTypesP();
            // $scope.list();
        });
    }]);