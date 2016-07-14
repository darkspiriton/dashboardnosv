angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Movimientos', {
                url: '/Generar-movimientos',
                templateUrl: 'app/partials/auxMovement.html',
                controller : 'auxMovementCtrl'
            });
    })
    .controller('auxMovementCtrl', function($scope, $compile, $state, $log, $filter, util, petition, toformData, toastr){

        util.liPage('movimientos');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Codigo", "bSortable" : true},
                {"sTitle": "Nombre", "bSortable" : true},
                {"sTitle": "Color", "bSortable" : true},
                {"sTitle": "Talla", "bSortable" : true},
                {"sTitle": "Precio Venta (S/.)", "bSortable" : true},
                {"sTitle": "Precio" , "bSearchable": false , "sWidth": "80px"},
                {"sTitle": "Acción" , "bSearchable": false , "sWidth": "190px"}
            ],
            actions	:  	[
                ['status',   {
                    0 : { txt : 'regular' , cls : 'bgm-green', dis : false },
                    1 : { txt : 'liquidacion' ,  cls : 'btn-info',dis: false}
                }
                ],
                ['actions', [
                    ['Agregar', 'addProduct' ,'bgm-teal'],
                    ['x codigo', 'otherProduct' ,'bgm-purple']
                ]
                ]
            ],
            data  	: 	['cod','name','color','size','price','status','actions'],
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

        var alertConfirmation = {
            title: "¿Se genero la salida de productos?",
            text: "",
            type: "success",
            html: true
        };

        $scope.productsClear = [];

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxmovement')
                .then(function(data){
                    $scope.tableData = data.products;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    toastr.error('Uyuyuy dice: ' + error.data.message);
                    util.resetTable($scope,$compile);
                    $scope.updateList = false;
                });
        };

        $scope.addProduct = function(ind){
            var count = 0;
            for(i in  $scope.products){
                if(angular.equals($scope.tableData[ind],$scope.products[i])){
                    count++;
                }
            }

            if (count == 0 && $scope.anadir){
                toastr.success('se añadio');
                $scope.dataProducts.push({id: $scope.tableData[ind].id, discount:0});
                $scope.tableData[ind].discount = 0;
                $scope.tableData[ind].preciofinal = $scope.tableData[ind].price-$scope.tableData[ind].discount;
                $scope.products.push(angular.copy($scope.tableData[ind]));
            }
        };

        $scope.addProduct2 = function(ind){
            $scope.prdTemp.id = $scope.codes[ind].id;
            $scope.prdTemp.cod =  $scope.codes[ind].cod;
            var count = 0;
            for(i in  $scope.products){
                if(angular.equals($scope.prdTemp,$scope.products[i])){
                    count++;
                }
            }

            if (count == 0 && $scope.anadir){
                toastr.success('se añadio');
                $scope.dataProducts.push({id: $scope.codes[ind].id, discount:0});
                $scope.prdTemp.discount = 0;
                $scope.prdTemp.preciofinal =$scope.prdTemp.price-$scope.prdTemp.discount;
                $scope.products.push(angular.copy($scope.prdTemp));
            }

            $scope.otherCod = null;
            util.modalClose('codes');
        };

        $scope.otherProduct = function (i) {
            if ($scope.anadir){
                $scope.codes = [];
                petition.get('api/auxmovement/get/codes' , {params: {id: $scope.tableData[i].id}})
                    .then(function(data){
                        $scope.codes = data.codes;
                        $scope.prdTemp = angular.copy($scope.tableData[i]);
                        util.modal('codes');
                    }, function(error){
                        toastr.error('Uyuyuy dice: ' + error.data.message);
                        $scope.updateList = false;
                    });
            }
        };

        $scope.submit = function() {
            valid_product_date($scope.dataProducts, function() {
                alertConfig.title = '¿Todo es correcto?';
                alertConfig.text=`<table class="table table-bordered w-100 table-attr text-center">
                                        <thead>
                                        <tr>
                                            <th>Cod</th>
                                            <th>Nombre</th>
                                            <th>P. Venta</th>
                                            <th>Descuento</th>
                                            <th>preciofinal</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>                                            
                                            <td>${( function(){
                                                var cod = "";
                                                for(i in $scope.products){                                                    
                                                    cod += $scope.products[i].cod+"<br>"                                                    
                                                }
                                                return cod; })()}
                                            </td>                                            
                                            <td>${( function(){
                                                var cod = "";
                                                for(i in $scope.products){
                                                    cod += "-"+$scope.products[i].name+"<br>"
                                                }
                                                return cod; })()}
                                            </td>
                                            <td>${( function(){
                                                var cod = "";
                                                for(i in $scope.products){
                                                    cod += $scope.products[i].price+"<br>"
                                                }
                                                return cod; })()}
                                            </td>
                                            <td>${( function(){
                                                var cod = "";
                                                for(i in $scope.dataProducts){
                                                    cod += $scope.dataProducts[i].discount+"<br>"
                                                }
                                                return cod; })()}
                                            </td>
                                            <td>${( function(){
                                                var cod = "";
                                                for(i in $scope.products){
                                                    cod += $scope.products[i].preciofinal+"<br>"
                                                }
                                                return cod; })()}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>`;
                swal(alertConfig,
                    function () {
                        petition.post('api/auxmovement/out', {products: $scope.dataProducts}).then(function (data) {
                            toastr.success(data.message);
                            alertConfirmation.text = confirmationOuts(data.products, data.movements);
                            swal(alertConfirmation);
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

        /**
         *  Template para confirmnacion de productos en salida
         */

         var confirmationOuts = function(products, movements){
            var tdList = "";

            for(var i in movements)
            {
                for(var j in products)
                {
                    if(movements[i].product_id == products[j].id)
                    {
                        movements[i].product_cod = products[j].cod;
                        movements[i].product_name = products[j].name;
                        continue;
                    }
                }
                tdList +=`<tr>                                            
                            <td>${movements[i].created_at}</td>
                            <td>${movements[i].date_shipment}</td>
                            <td>${movements[i].product_cod}</td>
                            <td>${movements[i].product_name}</td>
                            <td>${movements[i].discount}</td>
                        </tr>`;
            }

            var template = `<table class="table table-bordered w-100 table-attr text-center">
                                        <thead>
                                        <tr>
                                            <th style="Width:100px">creacion</th>
                                            <th style="Width:100px">fecha</th>
                                            <th>Cod</th>
                                            <th>Nombre</th>
                                            <th>desc.</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>                                            
                                            ${tdList}
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>`;

            return template;
         };

         /**
          * END
          */

        $scope.preciofinal=function (i){
            if($scope.products[i].discount>$scope.products[i].price ){
                $scope.products[i].discount = 0;
                $scope.products[i].preciofinal=$scope.products[i].price;
                return ;
            } else if($scope.products[i].discount == undefined){
                $scope.products[i].preciofinal = $scope.products[i].price;
                return ;
            }

            // $scope.products[i].discount = parseInt($scope.products[i].discount);
            $scope.products[i].preciofinal = Math.round(($scope.products[i].price - $scope.products[i].discount)*100)/100;
            $scope.dataProducts[i].discount = angular.copy($scope.products[i].discount);
            
        }


        function valid_product_date(prd, callback){
            for(i in prd){
                if (prd[i].date == undefined){
                    toastr.error('Uyuyuy dice: Falta ingresar fecha de salida');
                    return;
                }
            }
            return callback();
        }



        $scope.removeProduct = function(i){
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

        function resetProduct(){
            $scope.dataProducts = angular.copy($scope.productsClear);
            $scope.products = angular.copy($scope.productsClear);
        }

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            resetProduct();
            $scope.list();
        });
    });