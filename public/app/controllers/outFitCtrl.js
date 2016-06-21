angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('out_fit', {
                url: '/Gestion-de-outfit',
                templateUrl: 'app/partials/outFit.html',
                controller : 'out_fitCtrl'
            });
    })
    .controller('out_fitCtrl', function($scope, $compile, $state, util, petition, toastr){

        util.liPage('out_fit');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Descripción", "bSortable" : true},
                {"sTitle": "Codigo", "bSortable" : true},
                {"sTitle": "Precio", "bSortable" : true},
                {"sTitle": "Estado", "bSortable" : false, "sWidth": '80px'},
                {"sTitle": "Acción" , "bSearchable": true, "sWidth": '80px'}
            ],
            actions	:     	[
                ['status',   {
                    0 : { txt : 'inactivo' , cls : 'btn-danger'},
                    1 : { txt : 'activo' ,  cls : 'btn-success'}
                }
                ],
                ['actions', [
                    ['detalle', 'detail' ,'btn-info']
                ]
                ]
            ],
            data  	: 	['name','cod','price','status','actions'],
            configStatus: 'status'
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
            html:true
        };

        $scope.outfitClear = {
            description: null,
            code: null,
            price: null,
            status: false,
            products: []
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/outfit')
                .then(function(data){
                    $scope.tableData = data.outfits;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.addProduct = function (ind) {
            var count = 0;
            var name = $scope.products[ind].name;
            for(i in  $scope.outfit.products){
                if(name == $scope.outfit.products[i].name){
                    count++;
                    $scope.product = null;
                    break;
                }
            }

            if (count == 0){
                toastr.success('se añadio');
                $scope.outfit.products.push({name: $scope.products[ind].name});
                $scope.productsView.push($scope.products[ind]);
                $scope.product = null;
            }
        };

        $scope.removeProduct = function (i) {
            $scope.outfit.products.splice(i,1);
            $scope.productsView.splice(i,1);
        };

        $scope.listProduct = function() {
            petition.get('api/auxproduct/get/uniques')
                .then(function(data){
                    $scope.products = data.products;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.detail = function( ind ){
            var id = $scope.tableData[ind].id;
            petition.get('api/outfit/' + id )
                .then(function(data){
                    $scope.outfitDetail = data.outfit;
                    util.modal();
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.status = function( ind, dom ) {
            var id = $scope.tableData[ind].id;
            petition.delete('api/outfit/' + id )
                .then(function(data){
                    changeButton(ind, dom.target);
                    toastr.success(data.message);
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.submit = function () {
            console.log($scope.outfit.products);
            if($scope.outfit.products.length < 2)return toastr.error('El outfit debe tener al menos 2 productos');
            alertConfig.title = '¿Todo es correcto?';
            alertConfig.text = `<table class="table table-bordered w-100 table-attr text-center">
                                        <thead>
                                        <tr>
                                            <th>Descripción</th>
                                            <th>Código</th>
                                            <th>Productos</th>
                                            <th>Status</th>
                                            <th>Precio Venta</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>${$scope.outfit.description}</td>
                                            <td>${$scope.outfit.code}</td>
                                            <td>${( function(){
                                                var prd = ""; 
                                                for(i in $scope.outfit.products){
                                                    prd += $scope.outfit.products[i].name+"<br>"
                                                }
                                                return prd; })()}
                                            </td>
                                            <td>${$scope.outfit.status}</td>
                                            <td>${$scope.outfit.price}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>`;
            swal(alertConfig ,
                function() {
                    var method = ( $scope.outfit.id ) ? 'PUT' : 'POST';
                    var url = ( method == 'PUT') ? util.baseUrl('api/outfit/' + $scope.outfit.id) : util.baseUrl('api/outfit');
                    var config = {
                        method: method,
                        url: url,
                        data: $scope.outfit
                    };
                    $scope.formSubmit=true;
                    petition.custom(config).then(function(data){
                        toastr.success(data.message);
                        $scope.formSubmit=false;
                        $scope.list();
                        util.ocultaformulario();
                    }, function(error){
                        toastr.error('Huy Huy dice: ' + error.data.message);
                        $scope.formSubmit=false;
                    })
                });
        };
        
        $scope.nameStatus = function (e) {
            if(e == 0)
                return 'Inactivo';
            else
                return 'Activo';
        };

        $scope.cancel = function () {
            $scope.outfit = angular.copy($scope.outfitClear);
            $scope.productsView = [];
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.outfit = angular.copy($scope.outfitClear);
            $scope.productsView = [];
            util.muestraformulario();
        };

        changeButton = function (ind, dom){
            $scope.tableData[ind].status = ($scope.tableData[ind].status == 0)? 1 : 0;
            if ( $scope.tableData[ind].status == 1){
                $(dom).removeClass('btn-danger');
                $(dom).addClass('btn-success');
                $(dom).html('Activo');
            }else{
                $(dom).removeClass('btn-success');
                $(dom).addClass('btn-danger');
                $(dom).html('Inactivo');
            }
        };

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.outfit = angular.copy($scope.outfitClear);
            $scope.productsView = [];
            $scope.list();
            $scope.listProduct();
        });
    });