angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Movimientos Out Fit', {
                url: '/Generar-movimientos-out-fit',
                templateUrl: 'app/partials/auxMovementOutFit.html',
                controller : 'auxMovementOutFitCtrl'
            });
    })
    .controller('auxMovementOutFitCtrl', function($scope, $compile, $state, $log, $filter, util, petition, toastr){

        util.liPage('movimientos_outfit');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Codigo", "bSortable" : true, "sWidth": '80px'},
                {"sTitle": "Descripción", "bSortable" : true},
                {"sTitle": "Precio", "bSortable" : true},
                {"sTitle": "Acción" , "bSearchable": true, "sWidth": '190px'}
            ],
            actions	:     	[
                ['actions', [
                    ['generar', 'new' ,'bgm-teal'],
                    ['detalle', 'detail' ,'btn-info']
                ]
                ]
            ],
            data  	: 	['cod','name','price','actions'],
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
            closeOnConfirm: true
        };

        $scope.movementClear = {
            outfit_id: null,
            date: null,
            products: []
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/outfit/get/actives')
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
            var name = $scope.codes[ind].n;
            for(i in  $scope.movement.products){
                if(name == $scope.productsView[i].n){
                    count++;
                    $scope.code = null;
                    break;
                }
            }

            if (count == 0){
                toastr.success('se añadio');
                $scope.movement.products.push({id: $scope.codes[ind].id});
                $scope.productsView.push($scope.codes[ind]);
                $scope.code = null;
            }
        };

        $scope.removeProduct = function (i) {
            $scope.movement.products.splice(i,1);
            $scope.productsView.splice(i,1);
        };

        $scope.ListCodes = function(name) {
            if(name == undefined)return;
            petition.get('api/outfit/get/products/'+ name)
                .then(function(data){
                    $scope.codes = data.codes;
                }, function(error){
                    $scope.codes = [];
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

        $scope.submit = function () {
            if($scope.movement.products.length != $scope.products.length)return toastr.error('Debe seleccionar un codigo por cada producto');
            alertConfig.title = '¿Todo es correcto?';
            swal(alertConfig ,
                function() {
                    var method = ( $scope.movement.id ) ? 'PUT' : 'POST';
                    var url = ( method == 'PUT') ? util.baseUrl('api/auxmovements-outfit/' + $scope.movement.id) : util.baseUrl('api/auxmovements-outfit');
                    var config = {
                        method: method,
                        url: url,
                        data: $scope.movement
                    };
                    $scope.formSubmit=true;
                    petition.custom(config).then(function(data){
                        toastr.success(data.message);
                        $scope.formSubmit=false;
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
            clear();
            util.ocultaformulario();
        };

        $scope.new = function(i){
            products(i);
            clear();
            $scope.OutFitPrice = $scope.tableData[i].price;
            $scope.movement.outfit_id = $scope.tableData[i].id;
            util.muestraformulario();
        };
        
        function clear() {
            $scope.movement = angular.copy($scope.movementClear);
            $scope.productsView = [];
            $scope.products = [];
            $scope.codes = [];
            $scope.OutFitList = '';
            $scope.OutFitPrice = '';
        }

        function products(ind) {
            var id = $scope.tableData[ind].id;
            petition.get('api/outfit/' + id )
                .then(function(data){
                    $scope.products = data.outfit.products;
                    for(y in data.outfit.products){
                        $scope.OutFitList += ' / ' + data.outfit.products[y].name;
                    }
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        }

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.movement = angular.copy($scope.movementClear);
            $scope.productsView = [];
            $scope.OutFitList = '';
            $scope.list();
        });
    });