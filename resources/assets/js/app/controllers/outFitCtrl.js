angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('out_fit', {
                url: '/Gestion-de-outfit',
                templateUrl: 'app/partials/outFit.html',
                controller : 'out_fitCtrl'
            });
    }])
    .controller('out_fitCtrl', ["$scope", "$compile", "$state", "util", "petition", "toastr",
        function($scope, $compile, $state, util, petition, toastr){

        util.liPage('out_fit');

        var s1 = {
            1 : ['OutFit','bgm-teal',false],
            2 : ['Pack','bgm-green',false]
        };
        var s2 = {
            0 : ['Inactivo','bgm-red',false],
            1 : ['Activo','bgm-green',false]
        };

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Descripción", "bSortable" : true},
                {"sTitle": "Código", "bSortable" : true},
                {"sTitle": "Precio S/.", "bSortable" : true},
                {"sTitle": "Tipo", "bSortable" : true},
                {"sTitle": "Estado", "bSortable" : false, "sWidth": '80px'},
                {"sTitle": "Acción" , "bSearchable": true, "sWidth": '80px'}
            ],
            buttons	:
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'type', column: 'type', render : s1},
                            { name: 'status', column: 'status', render : s2}
                        ]
                    },
                    {
                        type: 'actions',
                        list:  [
                            { name: 'actions', render: [['Detalle','detail','btn-info']]}
                        ]
                    }
                ],

            data  	: 	['name','cod','price','type','status','actions'],
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
            status: 0,
            products: []
        };

        $scope.types = [
            {id: 1, name:'OutFit' },
            {id: 2, name:'Pack' },           
        ];

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/outfit')
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
            var name = $scope.products[ind].name;
            for(var i in  $scope.outfit.products){
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
            $scope.products = [];
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
            $scope.outfitDetail = {};
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
                                            <th>Precio Venta</th>
                                            <th>Tipo</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>${$scope.outfit.description}</td>
                                            <td>${$scope.outfit.code}</td>
                                            <td>${( function(){
                                                var prd = ""; 
                                                for(var i in $scope.outfit.products){
                                                    prd += $scope.outfit.products[i].name+"<br>"
                                                }
                                                return prd; })()}
                                            </td>
                                            <td>${$scope.outfit.price}</td>
                                            <td>${( function(){
                                                var type = "";

                                                for(var i in $scope.types){
                                                    if($scope.types[i].id == $scope.outfit.type){
                                                        type += $scope.types[i].name
                                                    }                                                  
                                                }                                                
                                                
                                                return type; })()}
                                            </td>
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

        var changeButton = function (ind, dom){
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
            $scope.outfit = angular.copy($scope.outfitClear);
            $scope.productsView = [];
            $scope.list();
            $scope.listProduct();
        });
    }]);