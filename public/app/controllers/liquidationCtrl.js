angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Liquidacion', {
                url: '/Gestion-de-liquidaciones-de-productos',
                templateUrl: 'app/partials/liquidation.html',
                controller : 'liquidationCtrl'
            });
    })
    .controller('liquidationCtrl', function($scope, $compile, $state, util, petition, toastr){

        util.liPage('liquidation');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha", "bSortable" : true, 'sWidth': '90px'},
                {"sTitle": "Codigo", "bSortable" : true, 'sWidth': '1px'},
                {"sTitle": "Nombre", "bSortable" : true},
                {"sTitle": "Proveedor", "bSortable" : true},
                {"sTitle": "Talla", "bSortable" : true},
                {"sTitle": "Color" , "bSearchable": true},
                {"sTitle": "Precio" , "bSearchable": true},
                {"sTitle": "Status" , "bSearchable": true},
                {"sTitle": "Acción" , "bSearchable": true, "sWidth": '80px'}
            ],
            actions	:   	[
                ['status',   {
                    0 : { txt : 'no disponible' , cls : 'btn-danger', dis : false},
                    1 : { txt : 'disponible' ,  cls : 'btn-success', dis : false},
                    2 : { txt : 'vendido' ,  cls : 'bgm-teal', dis : false}
                }
                ],
                ['actions', [
                    ['eliminar', 'delete' ,'bgm-red']
                ]
                ]
            ],
            data  	: 	[['product.created_at',10],'product.cod','product.name','product.provider.name','product.size.name','product.color.name','price','status','actions'],
            configStatus : 'product.status'
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

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/liquidation')
                .then(function(data){
                    $scope.tableData = data.products;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                    $scope.updateList = false;
                });
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

        $scope.ListCodes = function(name) {
            if(name == undefined)return;
            petition.get('api/auxproduct/get/uniques/'+ name +'/codes')
                .then(function(data){
                    $scope.codes = data.codes;
                }, function(error){
                    $scope.codes = [];
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.delete = function(i) {
            alertConfig.title = '¿Desea retirar el producto del area de liquidacion?';
            swal(alertConfig ,
                function() {
                    var id = $scope.tableData[i].id;
                    petition.delete('api/liquidation/' + id)
                        .then(function(data){
                            $scope.list();
                            toastr.success(data.message);
                        }, function(error){
                            console.log(error);
                            toastr.error('Huy Huy dice: ' + error.data.message);
                        });
                });
        };

        $scope.submit = function () {
            alertConfig.title = '¿Todo es correcto?';
            swal(alertConfig ,
                function() {
                    $scope.formSubmit=true;
                    petition.post('api/liquidation', $scope.liquidation ).then(function(data){
                        toastr.success(data.message);
                        $scope.formSubmit=false;
                        $scope.list();
                        util.modalClose();
                    }, function(error){
                        toastr.error('Huy Huy dice: ' + error.data.message);
                        $scope.formSubmit=false;
                    })
                });
        };

        $scope.new = function(){
            $scope.liquidation = {};
            util.modal();
        };

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.list();
            $scope.listProduct();
            $scope.liquidation = {};
        });
    });