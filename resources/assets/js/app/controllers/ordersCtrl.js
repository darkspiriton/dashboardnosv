angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Pedidos', {
                url: '/Adminitracion-de-pedidos',
                templateUrl: 'app/partials/orders.html',
                controller : 'ordersCtrl'
            });
    })
    .controller('ordersCtrl', function($scope, $compile, $state, $log, util, petition, toformData, toastr){

        util.liPage('pedidos');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha", "sWidth": "160px", "aaSorting": 'desc'},
                {"sTitle": "Vendedor(a)", "bSortable" : true},
                {"sTitle": "Cliente", "bSortable" : true},
                {"sTitle": "Estado",  "bSearchable": false , "bSortable" : false , "sWidth": "80px"},
                {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "190px"}
            ],
            actions	:  	[
                ['status',   {
                    1 : { txt : 'Emitida' , cls : 'btn-success' , dis : false},
                    2 : { txt : 'Pagada' ,  cls : 'bgm-teal', dis : false },
                    3 : { txt : 'Cancelada' ,  cls : 'btn-danger', dis : false}
                }
                ],
                ['actions',
                    [
                        ['llamadas', 'view' ,'btn-primary'],
                        ['envios', 'view' ,'btn-info']
                    ]
                ]
            ],
            data  	: 	['created_at','user.full_name','customer.name','status','actions'],
            configStatus : 'status_id'
        };

        //var alertConfig = {
        //    title: "¿Esta seguro?",
        //    text: "",
        //    type: "warning",
        //    showCancelButton: true,
        //    confirmButtonColor: "#DD6B55",
        //    confirmButtonText: "SI",
        //    cancelButtonColor: "#212121",
        //    cancelButtonText: "CANCELAR",
        //    closeOnConfirm: true
        //};

        $scope.orderClear = {
            id : null,
            customer_id: null,
            interest_id: null,
            products: []
        };

        //$scope.viewProducts = [];

        $scope.productClear = {
            index : null,
            type : null
        };

        $scope.list = function() {
            petition.get('api/order')
                .then(function(data){
                    $scope.tableData = data.orders;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.listSearch = function() {
            $scope.listView = true;
            $scope.order.customer_id = null;
            petition.get('api/customer/search/' + $scope.search)
                .then(function(data){
                    $scope.customers = data.customers;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.selectCustomer = function(i){
            $scope.order.customer_id = $scope.customers[i].id;
            $scope.search = $scope.customers[i].name;
        };

        $scope.listProductTypes = function() {
            petition.get('api/product/types')
                .then(function(data){
                    $scope.productTypes = data.types;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.listProduct = function( i ) {
            petition.get('api/product/type/' + $scope.productTypes[i].id)
                .then(function(data){
                    $scope.products = data.type.products;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.listKardex = function( i ) {
            petition.get('api/kardex/stock/' + $scope.products[i].id)
                .then(function(data){
                    $scope.kardexs = data.kardexs;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        //$scope.view = function( ind ){
        //    var id = $scope.tableData[ind].id;
        //    petition.get('api/interest/' + id )
        //        .then(function(data){
        //            $scope.orderDetail = data.order;
        //            util.modal();
        //        }, function(error){
        //            toastr.error('Ups ocurrio un problema: ' + error.data.message);
        //        });
        //};
        //
        //$scope.submit = function () {
        //    alertConfig.title = "¿Todo es correcto? Una ves registrado no podra ser editado y/o eliminado";
        //    swal(alertConfig ,
        //        function() {
        //            var method = ( $scope.order.id ) ? 'PUT' : 'POST';
        //            var url = ( method == 'PUT') ? util.baseUrl('api/interest/' + $scope.product.id) : util.baseUrl('api/interest');
        //            var config = {
        //                method: method,
        //                url: url,
        //                data: $scope.order
        //            };
        //            $scope.formSubmit = true;
        //            $log.log($scope.order);
        //            petition.custom(config).then(function (data) {
        //                toastr.success(data.message);
        //                $scope.formSubmit = false;
        //                $scope.list();
        //                util.ocultaformulario();
        //            }, function (error) {
        //                toastr.error('Ups ocurrio un problema: ' + error.data.message);
        //                $scope.formSubmit = false;
        //            });
        //        });
        //};

        $scope.cancel = function () {
            $scope.clear();
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.clear();
            util.muestraformulario();
            $scope.listPositiontion();
        };

        $scope.clear = function(){
            $scope.search = null;
            $scope.order = angular.copy($scope.orderClear);
            $scope.viewProducts = [];
        };

        // Adds Products

        $scope.addPrd = function( prd ){
            if(!$scope.product.index)return false;
            var product = {id : $scope.products[prd.index].id};
            var productView = {
                name : $scope.products[prd.index].name,
                type_name : $scope.productTypes[prd.type].name
            };

            for(var i in $scope.order.products){
                if ( product.id == $scope.order.products[i].id)return false;
            }

            $scope.viewProducts.push(productView);
            $scope.order.products.push(product);

            $scope.product = angular.copy($scope.productClear);
            $scope.products = [];
        };

        $scope.removePrd = function( i ){
            $scope.order.products.splice(i, 1);
            $scope.viewProducts.splice(i, 1);
        };

        // end Attributes

        //$scope.getStatus = function( status ){
        //    if (status == 2)return 'Atendido';
        //    else return 'Sin atender';
        //};
        //
        $scope.listPositiontion = function(){
            var pos = $('#searchCustomer').offset();
            $("#list").css({top: pos.top - 35, left: pos.left});
        };

        angular.element(document).ready(function(){
            $scope.order = angular.copy($scope.orderClear);
            $scope.product = angular.copy($scope.productClear);
            $scope.list();
            $scope.listProductTypes();
        });
    });