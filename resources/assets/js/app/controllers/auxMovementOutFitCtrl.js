angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('Movimientos Out Fit', {
                url: '/Generar-movimientos-out-fit',
                templateUrl: 'app/partials/auxMovementOutFit.html',
                controller : 'auxMovementOutFitCtrl'
            });
    }])
    .controller('auxMovementOutFitCtrl', ["$scope", "$compile", "$state", "$log", "$filter", "util", "petition", "toastr",
        function($scope, $compile, $state, $log, $filter, util, petition, toastr){

        util.liPage('movimientos_outfit');

        var s1 = {
            1 : ['OutFit','bgm-teal',false],
            2 : ['Pack','bgm-green',false]
        };

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Codigo", "bSortable" : true, "sWidth": '80px'},
                {"sTitle": "Descripción", "bSortable" : true},
                {"sTitle": "Precio S/.", "bSortable" : true},
                {"sTitle": "Tipo", "bSortable" : true},
                {"sTitle": "Acción" , "bSearchable": true, "sWidth": '190px'}
            ],

            buttons	:
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'type', column: 'type', render : s1},
                        ]
                    },
                    {
                        type: 'actions',
                        list:  [
                            { name: 'actions', render: [['Generar','new','bgm-green'],['Detalle','detail','btn-info']]}
                        ]
                    }
                ],

            data  	: 	['cod','name','price','type','actions'],
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
                    $('#table').AJQtable2('view2', $scope, $compile);
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
            for(var i in  $scope.movement.products){
                if(name == $scope.productsView[i].n){
                    count++;
                    $scope.code = null;
                    break;
                }
            }

            if (count === 0){
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
            if(name === undefined)return;
            $scope.codes = [];
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
            $scope.outfitDetail = {};
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
            alertConfig.title = '¿Todo es correcto?';
            alertConfig.text=`<table class="table table-bordered w-100 table-attr text-center">
                                        <thead>
                                        <tr>                                            
                                            <th>Producto</th>                                            
                                            <th>Precio</th>
                                            <th>Fecha</th>    
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>${$scope.OutFitList}</td>                                                                          
                                            <td>${$scope.OutFitPrice}</td>
                                            <td>${(function(){
                                                var day = $scope.movement.date.getDate();
                                                var month = ($scope.movement.date.getMonth().toString().length == 1)?
                                                    '0'.concat(($scope.movement.date.getMonth() + 1).toString()):
                                                    ($scope.movement.date.getMonth() + 1).toString();
                                                var year = $scope.movement.date.getFullYear();
                                                var date = '';
                                                return date.concat(day,'/',month,'/',year)})()}</td>
                                                                                    
                                        </tr>
                                        </tbody>
                                    </table>`;
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
                    });
                });
        };

        $scope.nameStatus = function (e) {
            if(e === 0)
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
            if($scope.tableData[i].type==1){
                $scope.OutFitType = "OutFit";
            }else if($scope.tableData[i].type==2){
                $scope.OutFitType = "Pack";
            }
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
            $scope.OutFitType= '';
        }

        function products(ind) {
            var id = $scope.tableData[ind].id;
            petition.get('api/outfit/' + id )
                .then(function(data){
                    $scope.products = data.outfit.products;
                    for(var y in data.outfit.products){
                        $scope.OutFitList += '/' + data.outfit.products[y].name;
                    }
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        }

        angular.element(document).ready(function(){
            $scope.movement = angular.copy($scope.movementClear);
            $scope.productsView = [];
            $scope.OutFitList = '';
            $scope.list();
        });
    }]);
